# Product Manager

Aplikasi web sederhana untuk mengelola data produk menggunakan PHP dan MySQL/MariaDB.

## Deskripsi

Product Manager adalah aplikasi berbasis web yang digunakan untuk mengelola data produk.
Aplikasi menyediakan fitur untuk menambah, menampilkan, mengubah, menghapus, dan mencari data produk.

Aplikasi dibuat menggunakan PHP, MySQL/MariaDB, PDO, HTML, dan CSS serta dijalankan menggunakan XAMPP.

## Fitur

- Menampilkan daftar produk
- Menambah produk
- Mengedit produk
- Menghapus produk
- Pencarian produk berdasarkan nama atau kategori
- Validasi nama produk minimal 3 karakter
- Validasi harga harus lebih dari 0
- Validasi stok tidak boleh negatif
- Validasi nama produk tidak boleh duplikat
- Perlindungan CSRF pada proses penghapusan
- Prepared statement menggunakan PDO
- Pengamanan output menggunakan `htmlspecialchars()`
- Tampilan responsif pada layar kecil

## Teknologi

- PHP
- MySQL / MariaDB
- HTML
- CSS
- PDO
- XAMPP
- phpMyAdmin

## Struktur Project

```text
product-manager-vutri/
├── config/
│   └── database.php
├── includes/
│   └── csrf.php
├── public/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── style.css
├── database/
│   └── store_db.sql
├── .gitignore
└── README.md

Persyaratan

Sebelum menjalankan aplikasi, pastikan komputer sudah memiliki:

XAMPP
Apache
MySQL atau MariaDB
Browser
PHP yang tersedia melalui XAMPP
Cara Menjalankan
1. Jalankan XAMPP

Buka XAMPP Control Panel kemudian aktifkan:

Apache
MySQL
2. Simpan project

Letakkan folder project di:

C:\xampp2026\htdocs\product-manager-vutri
3. Buat database

Buka phpMyAdmin melalui:

http://localhost/phpmyadmin/

Buat database dengan nama:

product-manager-vutri
4. Import database

Pilih database:

product-manager-vutri

Kemudian pilih menu Import.

Import file:

database/store_db.sql

Setelah proses import selesai, tabel products akan tersedia.

5. Konfigurasi koneksi database

File koneksi database terdapat pada:

config/database.php

Konfigurasi yang digunakan pada XAMPP:

Host     : localhost
Username : root
Password : kosong
Database : product-manager-vutri
6. Jalankan aplikasi

Buka browser dan akses:

http://localhost/product-manager-vutri/public/
Struktur Database

Aplikasi menggunakan tabel:

products

dengan beberapa field utama:

Field	Keterangan
id	ID produk
name	Nama produk
category	Kategori produk
price	Harga produk
stock	Stok produk
created_at	Waktu pembuatan data
updated_at	Waktu perubahan data

Nama produk dibuat unik sehingga tidak dapat menggunakan nama produk yang sama.

Validasi

Validasi yang diterapkan pada aplikasi:

Nama produk wajib diisi
Nama produk minimal 3 karakter
Kategori wajib diisi
Harga wajib diisi
Harga harus berupa angka
Harga harus lebih dari 0
Stok wajib diisi
Stok harus berupa bilangan bulat
Stok tidak boleh negatif
Nama produk tidak boleh duplikat
Keamanan

Beberapa mekanisme keamanan yang diterapkan:

Prepared Statement

Input pengguna pada query database menggunakan prepared statement PDO untuk mengurangi risiko SQL Injection.

CSRF Token

Token CSRF digunakan pada proses penghapusan produk agar request tidak dapat dilakukan secara sembarangan dari sumber lain.

Output Escaping

Data yang ditampilkan ke halaman web menggunakan:

htmlspecialchars()

untuk mencegah input HTML dijalankan sebagai kode.

Contoh pengujian:

<b>Promo</b>

harus ditampilkan sebagai teks:

<b>Promo</b>

dan bukan menjadi tulisan Promo yang tebal.

Pengujian

Pengujian aplikasi dilakukan dengan beberapa skenario:

1. Tambah produk valid

Data produk valid dapat disimpan dan muncul pada daftar produk.

2. Nama kurang dari 3 karakter

Contoh:

AB

Hasil:

Ditolak
3. Harga 0 atau negatif

Contoh:

0
-1000

Hasil:

Ditolak
4. Stok negatif

Contoh:

-1

Hasil:

Ditolak
5. Nama produk duplikat

Nama produk yang sudah digunakan tidak dapat digunakan kembali.

6. Refresh setelah tambah

Setelah berhasil menambah produk dan halaman di-refresh, data tidak tersimpan dua kali.

7. Pengujian HTML

Input:

<b>Promo</b>

harus ditampilkan sebagai teks biasa.

8. Pengujian responsif

Aplikasi diuji pada layar sempit untuk memastikan tabel dapat menyesuaikan tampilan menjadi card dan tetap mudah digunakan.

Fitur Bonus

Aplikasi juga menyediakan fitur pencarian berdasarkan:

Nama produk
Kategori produk

Pencarian menggunakan parameter GET.

File Database

File database untuk menjalankan kembali aplikasi tersedia pada:

database/store_db.sql

File tersebut merupakan hasil export database yang digunakan oleh aplikasi.

Penutup

Product Manager dibuat sebagai aplikasi sederhana untuk mengelola data produk dengan menerapkan konsep CRUD, validasi input, keamanan dasar, prepared statement, CSRF protection, dan responsive interface.