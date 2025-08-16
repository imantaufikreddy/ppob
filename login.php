<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
 * Nama Modul   : login.php
 * Fungsi       : Halaman login untuk user (admin atau pelanggan) aplikasi PLN.
 * Input        : Username dan password dari form login.
 * Output       : Session login dibuat jika autentikasi berhasil; jika gagal, tampilkan pesan error.
 * Alur         :
 * - Memastikan session aktif
 * - Menangkap input username dan password dari form
 * - Melakukan validasi data login menggunakan query SELECT
 * - Verifikasi password dengan password_verify()
 * - Mengatur session sesuai level user (admin/pelanggan) jika login berhasil
 * - Menampilkan pesan error jika login gagal
 * Tanggal Revisi: 21 Juli 2025
 */
session_start();
include 'config/koneksi.php';

/**
 * Handles the login form submission.
 * Checks if the 'login' button was pressed, then retrieves the username and password
 * from the POST request for authentication.
 *
 * Variables:
 * @var string $user The username submitted by the user.
 * @var string $pass The password submitted by the user.
 */
if (isset($_POST['login'])) {
    $user = $_POST['user_login'];
    $pass = $_POST['pass_login'];

    // Cek admin dulu (login pakai username atau email)
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE (username='$user' OR email='$user')");
    $cek = mysqli_fetch_assoc($query);

    if ($cek && password_verify($pass, $cek['password'])) {
        $_SESSION['id_user'] = $cek['id_user'];
        $_SESSION['level'] = $cek['id_level'];
        header("Location: dashboard.php");
        exit;
    } else {
        // Cek pelanggan (login pakai id_pelanggan atau email)
        $q_pel = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE (id_pelanggan='$user' OR email='$user')");
        $pel = mysqli_fetch_assoc($q_pel);
        if ($pel && password_verify($pass, $pel["password"])) {
            $_SESSION['id_user'] = $pel['id_pelanggan'];
            $_SESSION['level'] = 2;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Login gagal!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistem PLN</title>
    <?php include 'assets/bootstrap.html'; ?>
    <style>
        body {
            background: #f4faff; /* Hapus url gambar, hanya warna */
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #0074c7;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .login-header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .form-label {
            font-weight: bold;
            color: #0074c7;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #0074c7;
            box-shadow: 0 0 0 0.2rem rgba(0, 116, 199, 0.25);
        }
        .btn-login {
            background: #0074c7;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .register-link a {
            color: #0074c7;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #ffe600;
        }
        .pln-accent {
            color: #ffe600;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-container">
        <div class="login-header">
            <h2>Login <span class="pln-accent">PLN</span></h2>
            <div class="subtitle">Sistem Informasi Tagihan Listrik</div>
        </div>
        
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Email/ID Pelanggan:</label>
                <input type="text" name="user_login" placeholder="Masukkan email atau ID pelanggan" required class="form-control" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="pass_login" placeholder="Masukkan password" required class="form-control" autocomplete="new-password">
            </div>
            
            <div class="mb-3">
                <button name="login" class="btn btn-login btn-primary">Login</button>
            </div>
        </form>
        
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar Akun</a>
        </div>
    </div>
</div>
</body>
</html>
