<?php

require_once "../config/database.php";
require_once "../includes/csrf.php";

$csrfToken = csrf_token();

$errors = [];

$name = "";
$category = "";
$price = "";
$stock = "";


/*
|--------------------------------------------------------------------------
| PROSES FORM
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // =========================
    // CEK CSRF
    // =========================

    $token = $_POST["csrf_token"] ?? "";

    if (!verify_csrf_token($token)) {
        $errors[] = "CSRF token tidak valid.";
    }


    // =========================
    // AMBIL DATA FORM
    // =========================

    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $stock = trim($_POST["stock"] ?? "");


    // =========================
    // VALIDASI NAMA
    // =========================

    if ($name === "") {

        $errors[] = "Nama produk wajib diisi.";

    } elseif (strlen($name) < 3) {

        $errors[] = "Nama produk minimal 3 karakter.";
    }


    // =========================
    // VALIDASI KATEGORI
    // =========================

    if ($category === "") {

        $errors[] = "Kategori wajib diisi.";
    }


    // =========================
    // VALIDASI HARGA
    // Ketentuan dosen: harga > 0
    // =========================

    if ($price === "") {

        $errors[] = "Harga wajib diisi.";

    } elseif (!is_numeric($price)) {

        $errors[] = "Harga harus berupa angka.";

    } elseif ($price <= 0) {

        $errors[] = "Harga harus lebih dari 0.";
    }


    // =========================
    // VALIDASI STOK
    // Ketentuan dosen: stok >= 0
    // =========================

    if ($stock === "") {

        $errors[] = "Stok wajib diisi.";

    } elseif (
        filter_var($stock, FILTER_VALIDATE_INT) === false
    ) {

        $errors[] = "Stok harus berupa angka bulat.";

    } elseif ($stock < 0) {

        $errors[] = "Stok tidak boleh negatif.";
    }


    // =========================
    // CEK NAMA DUPLIKAT
    // =========================

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id
             FROM products
             WHERE name = ?"
        );

        $stmt->execute([$name]);

        if ($stmt->fetch()) {

            $errors[] = "Nama produk sudah digunakan.";
        }
    }


    // =========================
    // SIMPAN PRODUK
    // =========================

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "INSERT INTO products
            (name, category, price, stock)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $name,
            $category,
            $price,
            $stock
        ]);


        // =========================
        // PRG
        // Redirect setelah berhasil
        // =========================

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

    <title>Tambah Produk - Product Manager</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h1>Tambah Produk</h1>


    <!-- =========================
         PESAN ERROR
    ========================== -->

    <?php if (!empty($errors)): ?>

        <div class="error-box">

            <?php foreach ($errors as $error): ?>

                <p>
                    <?= htmlspecialchars($error) ?>
                </p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <!-- =========================
         FORM PRODUK
    ========================== -->

    <form
        method="POST"
        class="product-form"
    >

        <!-- CSRF TOKEN -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken) ?>"
        >


        <!-- =========================
             NAMA PRODUK
        ========================== -->

        <div class="form-group">

            <label for="name">
                Nama Produk
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
                placeholder="Masukkan nama produk"
            >

        </div>


        <!-- =========================
             KATEGORI
        ========================== -->

        <div class="form-group">

            <label for="category">
                Kategori
            </label>

            <input
                type="text"
                id="category"
                name="category"
                value="<?= htmlspecialchars($category) ?>"
                placeholder="Contoh: Elektronik"
            >

        </div>


        <!-- =========================
             HARGA
        ========================== -->

        <div class="form-group">

            <label for="price">
                Harga
            </label>

            <input
                type="number"
                id="price"
                name="price"
                value="<?= htmlspecialchars($price) ?>"
                min="1"
                step="0.01"
                placeholder="Masukkan harga"
            >

        </div>


        <!-- =========================
             STOK
        ========================== -->

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
                step="1"
                placeholder="Masukkan stok"
            >

        </div>


        <!-- =========================
             TOMBOL
        ========================== -->

        <div class="form-actions">

            <button
                type="submit"
                class="btn-add"
            >
                Simpan Produk
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