# Product Manager Vutri

Aplikasi Product Manager berbasis PHP dan MySQL untuk mengelola data produk.

## Fitur

- Menampilkan daftar produk
- Menambahkan produk
- Mengedit produk
- Menghapus produk
- Mencari produk berdasarkan nama atau kategori
- Validasi nama produk
- Validasi harga
- Validasi stok
- Mencegah nama produk duplikat
- Prepared Statement PDO
- Perlindungan CSRF
- Pengamanan output menggunakan htmlspecialchars()
- Tampilan responsif

## Teknologi

- PHP
- MySQL
- PDO
- HTML
- CSS
- XAMPP
- Git & GitHub

## Struktur Project

```text
product-manager-vutri/
│
├── config/
│   └── database.php
│
├── includes/
│   └── csrf.php
│
├── public/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── style.css
│
└── README.md

