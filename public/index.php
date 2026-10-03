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
        "SELECT *
         FROM products
         WHERE name LIKE ?
            OR category LIKE ?
         ORDER BY id DESC"
    );

    $keyword = "%" . $search . "%";

    $stmt->execute([
        $keyword,
        $keyword
    ]);

} else {

    $stmt = $pdo->query(
        "SELECT *
         FROM products
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

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="style.css?v=2"
    >

</head>


<body>

<div class="container">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="header">

        <div class="header-text">

            <h1>
                Product Manager
            </h1>

            <p>
                Manajemen data produk
            </p>

        </div>


        <a
            href="create.php"
            class="btn-tambah"
        >
            + Tambah Produk
        </a>

    </div>



    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <div class="content">

        <h2>
            Daftar Produk
        </h2>



        <!-- =================================================
             SEARCH
        ================================================== -->

        <form
            method="GET"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                value="<?= htmlspecialchars(
                    $search,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
                placeholder="Cari nama atau kategori produk..."
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



        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            ID
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
                            Dibuat
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (count($products) > 0): ?>


                        <?php foreach ($products as $product): ?>


                            <tr>


                                <!-- ID -->

                                <td data-label="ID">

                                    <?= (int) $product["id"] ?>

                                </td>



                                <!-- NAMA PRODUK -->

                                <td
                                    data-label="Nama Produk"
                                    class="nama-produk"
                                >

                                    <?= htmlspecialchars(
                                        $product["name"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>

                                </td>



                                <!-- KATEGORI -->

                                <td data-label="Kategori">

                                    <?= htmlspecialchars(
                                        $product["category"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>

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

                                    <?= (int) $product["stock"] ?>

                                </td>



                                <!-- DIBUAT -->

                                <td data-label="Dibuat">

                                    <?= htmlspecialchars(
                                        $product["created_at"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>

                                </td>



                                <!-- AKSI -->

                                <td
                                    data-label="Aksi"
                                    class="aksi"
                                >


                                    <!-- EDIT -->

                                    <a
                                        href="edit.php?id=<?= (int) $product["id"] ?>"
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
                                            value="<?= (int) $product["id"] ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars(
                                                $csrfToken,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>"
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


                        <!-- =================================================
                             DATA KOSONG
                        ================================================== -->

                        <tr>

                            <td
                                colspan="7"
                                class="empty"
                            >


                                <?php if ($search !== ""): ?>

                                    Produk
                                    "<strong><?= htmlspecialchars(
                                        $search,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?></strong>"
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

</div>


</body>

</html>