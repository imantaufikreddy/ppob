<?php
/*
 * Script ini adalah halaman permintaan reset password untuk user yang lupa password.
 * Alur:
 * - Menampilkan form input email untuk permintaan reset password
 * - Form akan mengirim data ke proses/reset_request.php
 * - Terdapat tombol kembali ke login
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
    <?php include 'assets/bootstrap.html'; ?>
</head>
<body class="container mt-5">
    <h2>Lupa Password</h2>
    <form method="POST" action="proses/reset_request.php">
      <input type="email" name="email" class="form-control mb-2" placeholder="Masukkan email terdaftar" required>
      <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
    </form>
    <a href="login.php">Kembali ke Login</a>
</body>
</html> 