<?php
/*
 * Script ini menampilkan instruksi pembayaran tahap kedua untuk pelanggan PLN.
 * Alur:
 * - Memastikan user adalah pelanggan (akses_pelanggan.php) dan koneksi database aktif
 * - Memvalidasi request POST dan parameter id_tagihan serta metode pembayaran
 * - Menentukan data rekening/VA berdasarkan metode pembayaran yang dipilih
 * - Mengambil data tagihan dari database berdasarkan id_tagihan
 * - Jika data valid, menampilkan detail tagihan dan instruksi transfer ke rekening/VA
 * - Form konfirmasi pembayaran akan mengirim data ke pembayaran_konfirmasi.php
 */
include '../config/akses_pelanggan.php';
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_tagihan']) || !isset($_POST['metode'])) {
    echo '<div class="alert alert-danger">Akses tidak valid.</div>';
    exit;
}

$id_tagihan = $_POST['id_tagihan'];
$metode = $_POST['metode'];

// Data rekening/VA
$rekening = [
    'Bank:BCA' => ['nomor' => '1234567890', 'nama' => 'pembayaran listrik'],
    'Bank:Mandiri' => ['nomor' => '9876543210', 'nama' => 'pembayaran listrik'],
    'Bank:BRI' => ['nomor' => '1122334455', 'nama' => 'pembayaran listrik'],
    'Bank:BNI' => ['nomor' => '5566778899', 'nama' => 'pembayaran listrik'],
    'VA:OVO' => ['nomor' => '081234567890', 'nama' => 'pembayaran listrik'],
    'VA:Gopay' => ['nomor' => '081298765432', 'nama' => 'pembayaran listrik'],
    'VA:Shopeepay' => ['nomor' => '081212345678', 'nama' => 'pembayaran listrik'],
];

$info = isset($rekening[$metode]) ? $rekening[$metode] : null;
if (!$info) {
    echo '<div class="alert alert-danger">Metode pembayaran tidak valid.</div>';
    exit;
}

$tagihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT t.*, pg.tanggal, p.nama FROM tagihan t JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan WHERE t.id_tagihan='$id_tagihan'"));
if (!$tagihan) {
    echo '<div class="alert alert-danger">Tagihan tidak ditemukan.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instruksi Pembayaran</title>
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
        .detail-tagihan {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 24px;
            margin-bottom: 24px;
        }
        .alert-pln {
            background: #ffe600;
            color: #222;
            border-radius: 8px;
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 24px;
        }
        .btn-pln {
            background: #0074c7;
            color: #fff;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 24px;
        }
        .btn-pln:hover {
            background: #ffe600;
            color: #0074c7;
        }
        .btn-kembali {
            background: #ffe600;
            color: #222;
            border: none;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 24px;
        }
        .btn-kembali:hover {
            background: #fff200;
            color: #0074c7;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="pln-header">
            <h2 class="mb-0">Instruksi Pembayaran</h2>
        </div>
        <div class="detail-tagihan mb-3">
            <strong>Nama:</strong> <?= $tagihan['nama'] ?><br>
            <strong>Tanggal:</strong> <?= $tagihan['tanggal'] ?><br>
            <strong>Total Tagihan:</strong> Rp <?= number_format($tagihan['jumlah_tagihan']) ?><br>
            <strong>Status:</strong> <?= $tagihan['status'] ?><br>
            <strong>Metode:</strong> <?= htmlspecialchars($metode) ?>
        </div>
        <div class="alert-pln">
            Silakan transfer ke nomor berikut:<br>
            <strong>Nomor Rekening/VA:</strong> <?= $info['nomor'] ?><br>
            <strong>Atas Nama:</strong> <?= $info['nama'] ?>
        </div>
        <form method="POST" action="pembayaran_konfirmasi.php">
            <input type="hidden" name="id_tagihan" value="<?= $id_tagihan ?>">
            <input type="hidden" name="metode" value="<?= htmlspecialchars($metode) ?>">
            <input type="hidden" name="nomor" value="<?= $info['nomor'] ?>">
            <button type="submit" class="btn btn-pln">Bayar</button>
            <a href="tagihan.php" class="btn btn-kembali">Batal</a>
        </form>
    </div>
</body>
</html> 