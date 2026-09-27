<?php

require_once "../config/database.php";
require_once "../includes/csrf.php";

$csrfToken = csrf_token();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

$errors = [];

$name = $product["name"];
$category = $product["category"];
$price = $product["price"];
$stock = $product["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!verify_csrf_token($token)) {
        $errors[] = "CSRF token tidak valid.";
    }

    $name = trim($_POST["name"] ?? "");

    $category = trim($_POST["category"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $stock = trim($_POST["stock"] ?? "");

    // Validasi nama
    if ($name === "") {

        $errors[] = "Nama produk wajib diisi.";

    } elseif (strlen($name) < 3) {

        $errors[] = "Nama produk minimal 3 karakter.";
    }

    // Validasi kategori
    if ($category === "") {

        $errors[] = "Kategori wajib diisi.";
    }

    // Validasi harga
    if ($price === "") {

        $errors[] = "Harga wajib diisi.";

    } elseif (!is_numeric($price)) {

        $errors[] = "Harga harus berupa angka.";

    } elseif ($price < 0) {

        $errors[] = "Harga tidak boleh negatif.";
    }

    // Validasi stok
    if ($stock === "") {

        $errors[] = "Stok wajib diisi.";

    } elseif (filter_var($stock, FILTER_VALIDATE_INT) === false) {

        $errors[] = "Stok harus berupa angka bulat.";

    } elseif ($stock < 0) {

        $errors[] = "Stok tidak boleh negatif.";
    }

    // Cek nama duplikat
    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id
             FROM products
             WHERE name = ?
             AND id != ?"
        );

        $stmt->execute([
            $name,
            $id
        ]);

        if ($stmt->fetch()) {

            $errors[] = "Nama produk sudah digunakan.";
        }
    }

    // Update data
    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "UPDATE products
             SET name = ?,
                 category = ?,
                 price = ?,
                 stock = ?
             WHERE id = ?"
        );

        $stmt->execute([
            $name,
            $category,
            $price,
            $stock,
            $id
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Produk - Product Manager</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<div class="container">

    <h1>Edit Produk</h1>

    <?php if (!empty($errors)): ?>

        <div class="error-box">

            <?php foreach ($errors as $error): ?>

                <p>
                    <?= htmlspecialchars($error) ?>
                </p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


   <form
    method="POST"
    class="product-form"
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken) ?>"
    >

        <div class="form-group">

            <label for="name">
                Nama Produk
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
            >

        </div>


        <div class="form-group">

            <label for="category">
                Kategori
            </label>

            <input
                type="text"
                id="category"
                name="category"
                value="<?= htmlspecialchars($category) ?>"
            >

        </div>


        <div class="form-group">

            <label for="price">
                Harga
            </label>

            <input
                type="number"
                id="price"
                name="price"
                value="<?= htmlspecialchars($price) ?>"
                min="0"
            >

        </div>


        <div class="form-group">

            <label for="stock">
                Stok
            </label>

            <input
                type="number"
                id="stock"
                name="stock"
                value="<?= htmlspecialchars($stock) ?>"
                min="0"
            >

        </div>


        <div class="form-actions">

            <button
                type="submit"
                class="btn-add"
            >
                Simpan Perubahan
            </button>

            <a
                href="index.php"
                class="btn-cancel"
            >
                Batal
            </a>

        </div>

    </form>

</div>

</body>

</html>