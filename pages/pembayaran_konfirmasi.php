<?php
/*
 * Script ini menangani konfirmasi pembayaran tagihan oleh pelanggan PLN.
 * Alur:
 * - Memastikan user adalah pelanggan (akses_pelanggan.php) dan koneksi database aktif
 * - Memvalidasi request POST dan parameter id_tagihan, metode, dan nomor rekening/VA
 * - Mengupdate status tagihan menjadi Lunas di database
 * - Mengambil data tagihan yang sudah dibayar
 * - Menampilkan detail pembayaran dan tombol cetak bukti lunas
 */
include '../config/akses_pelanggan.php';
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_tagihan']) || !isset($_POST['metode']) || !isset($_POST['nomor'])) {
    echo '<div class="alert alert-danger">Akses tidak valid.</div>';
    exit;
}

$id_tagihan = $_POST['id_tagihan'];
$metode = $_POST['metode'];
$nomor = $_POST['nomor'];

// Update status tagihan menjadi Lunas
mysqli_query($koneksi, "UPDATE tagihan SET status='Lunas' WHERE id_tagihan='$id_tagihan'");

$tagihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT t.*, pg.tanggal, p.nama FROM tagihan t JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan WHERE t.id_tagihan='$id_tagihan'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran</title>
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
        .alert-pln {
            background: #d1f7d6;
            color: #0074c7;
            border-radius: 8px;
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 24px;
            border-left: 8px solid #ffe600;
        }
        .detail-tagihan {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 24px;
            margin-bottom: 24px;
        }
        .btn-pln {
            background: #ffe600;
            color: #222;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 24px;
            margin-right: 8px;
        }
        .btn-pln:hover {
            background: #fff200;
            color: #0074c7;
        }
        .btn-kembali {
            background: #0074c7;
            color: #fff;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 24px;
        }
        .btn-kembali:hover {
            background: #ffe600;
            color: #0074c7;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="pln-header">
            <h2 class="mb-0">Konfirmasi Pembayaran</h2>
        </div>
        <div class="alert-pln">Pembayaran berhasil! Tagihan sudah <strong>Lunas</strong>.</div>
        <div class="detail-tagihan mb-3">
            <strong>Nama:</strong> <?= $tagihan['nama'] ?><br>
            <strong>Tanggal:</strong> <?= $tagihan['tanggal'] ?><br>
            <strong>Total Tagihan:</strong> Rp <?= number_format($tagihan['jumlah_tagihan']) ?><br>
            <strong>Status:</strong> <?= $tagihan['status'] ?><br>
            <strong>Metode:</strong> <?= htmlspecialchars($metode) ?><br>
            <strong>Nomor Rekening/VA:</strong> <?= $nomor ?>
        </div>
        <a href="cetak_tagihan.php?id=<?= $id_tagihan ?>" target="_blank" class="btn btn-pln">Cetak Bukti Lunas</a>
        <a href="tagihan.php" class="btn btn-kembali">Kembali ke Tagihan</a>
    </div>
</body>
</html> 