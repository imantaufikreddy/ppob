<?php
/*
 * Script ini membatasi akses halaman hanya untuk admin PLN (level 1).
 * Alur:
 * - Memastikan session sudah aktif dan user sudah login (include session_check.php)
 * - Mengecek level user pada session, jika bukan level 1 (admin) maka redirect ke dashboard
 * - Digunakan untuk proteksi halaman khusus admin
 */
include 'session_check.php';
if ($_SESSION['level'] != 1) {
    header('Location: ../dashboard.php');
    exit;
}
?> 