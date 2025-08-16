<?php
/*
 * Script ini memeriksa status session login user PLN.
 * Alur:
 * - Memastikan session sudah aktif, jika belum maka session_start()
 * - Mengecek apakah user sudah login dengan memeriksa $_SESSION['id_user']
 * - Jika belum login, redirect ke halaman login
 * - Digunakan untuk proteksi halaman yang membutuhkan login
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}
?> 