<?php
/*
 * Script ini adalah halaman reset password untuk user melalui token/email.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengambil token dari parameter GET
 * - Menampilkan form input password baru jika token valid
 * - Form akan mengirim data ke proses/reset_password.php
 */
include 'config/koneksi.php';
$token = $_GET['token'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM user WHERE reset_token='$token' AND token_expiry > NOW()"));
if (!$data) { echo "Token tidak valid atau kadaluarsa."; exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <?php include 'assets/bootstrap.html'; ?>
</head>
<body class="container mt-5">
    <h2>Reset Password</h2>
    <form method="POST" action="proses/reset_password.php">
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="password" name="password" class="form-control mb-2" placeholder="Password baru" required>
      <button type="submit" class="btn btn-success">Reset</button>
    </form>
</body>
</html> 