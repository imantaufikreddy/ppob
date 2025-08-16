<?php
/*
 * Script ini membatasi akses halaman hanya untuk pelanggan PLN (level 2).
 * Alur:
 * - Memastikan session sudah aktif dan user sudah login (include session_check.php)
 * - Mengecek level user pada session, jika bukan level 2 (pelanggan) maka redirect ke dashboard
 * - Digunakan untuk proteksi halaman khusus pelanggan
 */
include 'session_check.php';
if ($_SESSION['level'] != 2) {
    header('Location: ../dashboard.php');
    exit;
}
?> 