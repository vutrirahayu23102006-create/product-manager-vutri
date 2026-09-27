<?php

require_once "../config/database.php";
require_once "../includes/csrf.php";

$csrfToken = csrf_token();

$search = trim($_GET["search"] ?? "");

/*
|--------------------------------------------------------------------------
| AMBIL DATA PRODUK
|--------------------------------------------------------------------------
*/

if ($search !== "") {

    $stmt = $pdo->prepare(
        "SELECT * FROM products
         WHERE name LIKE ? OR category LIKE ?
         ORDER BY id DESC"
    );

    $keyword = "%" . $search . "%";

    $stmt->execute([
        $keyword,
        $keyword
    ]);

} else {

    $stmt = $pdo->query(
        "SELECT * FROM products
         ORDER BY id DESC"
    );
}

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Product Manager</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <!-- =========================
         HEADER
    ========================== -->

    <h1>Product Manager</h1>


    <!-- =========================
         TOP BAR
    ========================== -->

    <div class="top-bar">

        <a
            href="create.php"
            class="btn-add"
        >
            + Tambah Produk
        </a>


        <!-- FORM PENCARIAN -->

        <form
            method="GET"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Cari produk atau kategori..."
            >

            <button type="submit">
                Cari
            </button>


            <?php if ($search !== ""): ?>

                <a
                    href="index.php"
                    class="btn-reset"
                >
                    Reset
                </a>

            <?php endif; ?>

        </form>

    </div>


    <!-- =========================
         TABLE / CARD PRODUK
    ========================== -->

    <div class="table-container">

        <table>

            <!-- HEADER TABLE -->

            <thead>

                <tr>

                    <th>
                        No
                    </th>

                    <th>
                        Nama Produk
                    </th>

                    <th>
                        Kategori
                    </th>

                    <th>
                        Harga
                    </th>

                    <th>
                        Stok
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <!-- DATA PRODUK -->

            <tbody>

                <?php if (count($products) > 0): ?>

                    <?php $no = 1; ?>


                    <?php foreach ($products as $product): ?>

                        <tr>

                            <!-- NOMOR -->

                            <td data-label="No">

                                <?= $no++ ?>

                            </td>


                            <!-- NAMA -->

                            <td data-label="Nama Produk">

                                <?= htmlspecialchars($product["name"]) ?>

                            </td>


                            <!-- KATEGORI -->

                            <td data-label="Kategori">

                                <?= htmlspecialchars($product["category"]) ?>

                            </td>


                            <!-- HARGA -->

                            <td data-label="Harga">

                                Rp
                                <?= number_format(
                                    $product["price"],
                                    0,
                                    ",",
                                    "."
                                ) ?>

                            </td>


                            <!-- STOK -->

                            <td data-label="Stok">

                                <?= htmlspecialchars($product["stock"]) ?>

                            </td>


                            <!-- AKSI -->

                            <td
                                data-label="Aksi"
                                class="action-cell"
                            >

                                <!-- EDIT -->

                                <a
                                    href="edit.php?id=<?= $product["id"] ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>


                                <!-- HAPUS -->

                                <form
                                    method="POST"
                                    action="delete.php"
                                    class="delete-form"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $product["id"] ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars($csrfToken) ?>"
                                    >


                                    <button
                                        type="submit"
                                        class="btn-delete"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>


                <?php else: ?>

                    <!-- JIKA TIDAK ADA DATA -->

                    <tr>

                        <td
                            colspan="6"
                            class="empty"
                        >
                            <?php if ($search !== ""): ?>

                                Produk dengan kata
                                "<strong><?= htmlspecialchars($search) ?></strong>"
                                tidak ditemukan.

                            <?php else: ?>

                                Belum ada produk.

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>