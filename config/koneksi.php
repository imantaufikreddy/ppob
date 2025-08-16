<?php
// Coba koneksi ke MySQL tanpa database dulu
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn) die('Koneksi Gagal: ' . mysqli_connect_error());

// Cek dan buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS pln_db";
if (!mysqli_query($conn, $sql)) {
    die('Gagal membuat database: ' . mysqli_error($conn));
}

// Baru koneksi ke database pln_db
$koneksi = mysqli_connect('localhost', 'root', '', 'pln_db');
if (!$koneksi) die('Koneksi Gagal: ' . mysqli_connect_error());
?> 