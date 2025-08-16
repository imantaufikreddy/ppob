<?php
/*
 * Script ini digunakan untuk reset data penggunaan listrik dan reset AUTO_INCREMENT ke 1.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Menghapus seluruh data pada tabel penggunaan (TRUNCATE)
 * - Menampilkan alert berhasil/gagal dan redirect ke halaman penggunaan
 */
include dirname(__DIR__) . '/config/koneksi.php';

// Hapus semua data
$db_query = mysqli_query($koneksi, "TRUNCATE TABLE penggunaan");

if ($db_query) {
    echo "<script>alert('Data penggunaan berhasil direset!'); window.location='../pages/penggunaan.php';</script>";
} else {
    echo "<script>alert('Gagal reset data penggunaan!'); window.location='../pages/penggunaan.php';</script>";
}
?> 