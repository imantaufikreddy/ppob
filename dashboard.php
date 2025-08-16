<?php
/*
 * Script ini adalah halaman dashboard utama untuk admin dan pelanggan PLN.
 * Alur:
 * - Memastikan session aktif dan user sudah login
 * - Menampilkan menu navigasi sesuai level user (admin/pelanggan)
 * - Menampilkan informasi selamat datang dan menu utama
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/session_check.php';
include 'config/koneksi.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

if ($level == 1) {
    $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM admin WHERE id_user='$id_user'"));
    $nama_pengguna = $user['nama'] ? $user['nama'] : $user['username'];
    $level_text = 'Admin';
} else {
    $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='$id_user'"));
    $nama_pengguna = $user['nama'];
    $level_text = 'Pelanggan';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <?php include 'assets/bootstrap.html'; ?>
    <style>
        body {
            background: #f4faff;
        }
        .pln-header {
            background: #0074c7;
            color: #fff;
            padding: 24px 0 16px 0;
            border-left: 10px solid #ffe600;
            border-radius: 0 0 12px 12px;
            margin-bottom: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .menu-pln {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 32px 24px;
            margin-bottom: 24px;
        }
        .menu-pln a {
            display: block;
            margin-bottom: 16px;
            padding: 12px 0 12px 18px;
            background: #e3f2fd;
            color: #0074c7;
            border-left: 6px solid #ffe600;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .menu-pln a:hover {
            background: #ffe600;
            color: #222;
        }
        .alert-pln {
            background: #fffbe6;
            color: #0074c7;
            border-left: 6px solid #ffe600;
            border-radius: 6px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 1.1rem;
        }
        .footer-pln {
            background: #0074c7;
            color: #fff;
            text-align: center;
            padding: 14px 0 10px 0;
            border-top: 4px solid #ffe600;
            font-size: 1rem;
            margin-top: 40px;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="pln-header">
                <h2 class="mb-0">Dashboard</h2>
            </div>
            <div class="alert-pln">Selamat datang, <b><?= $nama_pengguna ?></b> (<?= $level_text ?>)</div>
            <div class="menu-pln">
                <?php if ($level == 1): ?>
                <a href="pages/pelanggan.php">Kelola Pelanggan</a>
                <a href="pages/penggunaan.php">Kelola Penggunaan</a>
                <a href="pages/tagihan.php">Kelola Tagihan</a>
                <a href="pages/laporan_tagihan.php">Laporan Tagihan</a>
                <?php else: ?>
                <a href="pages/tagihan.php">Lihat Tagihan</a>
                <a href="pages/riwayat_pembayaran.php">Riwayat Pembayaran</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="footer-pln">&copy; 2025</div>
</div>
</body>
</html>
