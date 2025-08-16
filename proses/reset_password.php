<?php
/*
 * Script ini memproses reset password user PLN melalui token/email.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengambil token dan password baru dari form
 * - Update password user di database dan hapus token
 * - Redirect ke halaman login dengan pesan sukses
 */
include '../config/koneksi.php';
$token = $_POST['token'];
$pass = md5($_POST['password']);
mysqli_query($koneksi, "UPDATE user SET password='$pass', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'");
header("Location: ../login.php?pesan=Password berhasil direset");
?> 