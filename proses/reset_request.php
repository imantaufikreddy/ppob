<?php
/*
 * Script ini memproses permintaan reset password user PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mencari user berdasarkan email
 * - Jika user ditemukan, generate token reset dan simpan ke database
 * - Menampilkan link reset password (simulasi kirim email)
 * - Jika user tidak ditemukan, tampilkan pesan error
 */
include '../config/koneksi.php';
$email = $_POST['email'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'"));
if ($user) {
  $token = bin2hex(random_bytes(16));
  $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
  mysqli_query($koneksi, "UPDATE user SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");
  $link = "http://localhost/ppob/reset_password.php?token=$token";
  echo "Kirim email ke $email: <a href='$link'>$link</a>";
} else {
  echo "Email tidak ditemukan.";
}
?> 