<?php
/*
 * Script ini adalah halaman khusus untuk menambah admin baru pada sistem PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Menyediakan form tambah admin dengan username unik otomatis
 * - Validasi username/email unik sebelum insert
 * - Menyimpan data admin baru ke database
 * - Menampilkan pesan sukses/gagal
 * - Terdapat fungsi PHP dan JS untuk generate username admin
 */
include '../config/koneksi.php';
$msg = '';

// Fungsi PHP untuk generate username unik otomatis untuk admin
function generateAdminUsername($koneksi) {
    /*
     * Fungsi ini menghasilkan username admin unik dengan prefix '21' dan 6 digit acak.
     * Mengecek ke database agar tidak ada duplikasi username.
     * Return: string username unik
     */
    $prefix = '21';
    do {
        $rand = str_pad(strval(rand(0, 999999)), 6, '0', STR_PAD_LEFT);
        $username = $prefix . $rand;
        $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'"));
    } while ($cek > 0);
    return $username;
}

$username = isset($_POST['username']) ? $_POST['username'] : generateAdminUsername($koneksi);

if (isset($_POST['tambah_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Validasi username/email unik
    $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' OR email='$email'"));
    if ($cek > 0) {
        $msg = '<div class="alert alert-danger">Username atau Email sudah terdaftar!</div>';
    } else {
        // Password disimpan dengan hash
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "INSERT INTO admin (username, password, id_level, nama, email) VALUES ('$username', '$password_hash', 1, '$nama', '$email')");
        $msg = '<div class="alert alert-success">Admin berhasil ditambahkan! Username: <b>'.$username.'</b></div>';
        $username = generateAdminUsername($koneksi);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Admin - Sistem PLN</title>
    <?php include '../assets/bootstrap.html'; ?>
    <style>
        body {
            background: #f4faff;
        }
        .pln-header {
            background: #0074c7;
            color: #fff;
            padding: 20px 0 10px 0;
            border-left: 10px solid #ffe600;
            border-radius: 0 0 10px 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-label {
            font-weight: bold;
            color: #0074c7;
        }
        .btn-primary {
            background: #0074c7;
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .link-kembali {
            color: #0074c7;
            font-weight: bold;
        }
        .link-kembali:hover {
            color: #ffe600;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
    <script>
    // Fungsi JS untuk generate username random 8 digit (prefix 21)
    function randomAdminUsername() {
        var prefix = '21';
        var rand = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
        return prefix + rand;
    }
    window.onload = function() {
        document.getElementById('username').value = randomAdminUsername();
    };
    </script>
</head>
<body>
<div class="container mt-4">
    <div class="pln-header">
        <h2 class="mb-0">Tambah Admin Baru</h2>
    </div>
    
    <div class="form-container">
        <?= $msg ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username (8 digit):</label>
                <input type="text" name="username" id="username" class="form-control" readonly required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap:</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" name="tambah_admin" class="btn btn-primary">Tambah Admin</button>
                <a href="../login.php" class="link-kembali">&larr; Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>