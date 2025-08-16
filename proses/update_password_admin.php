<?php
/*
 * Script ini digunakan untuk update password admin yang masih plain text menjadi hash MD5.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengupdate password admin tertentu (berdasarkan username) menjadi hash MD5
 * - Menampilkan alert jika update berhasil dan redirect ke dashboard
 */
include '../config/koneksi.php';

// Update password Dean Threean Eleazar
$username1 = '21514402';
$password1 = 'dean123';
$hashed_password1 = md5($password1);
mysqli_query($koneksi, "UPDATE admin SET password='$hashed_password1' WHERE username='$username1'");

// Update password Vallen Zefanya
$username2 = '21426332';
$password2 = 'vallen123';
$hashed_password2 = md5($password2);
mysqli_query($koneksi, "UPDATE admin SET password='$hashed_password2' WHERE username='$username2'");

echo "<script>alert('Password admin berhasil di-update ke hash MD5!'); window.location='../dashboard.php';</script>";
?> 