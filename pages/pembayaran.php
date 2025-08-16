<?php
/*
 * Script ini menampilkan halaman pembayaran tagihan untuk pelanggan PLN.
 * Alur:
 * - Memastikan user adalah pelanggan (akses_pelanggan.php) dan koneksi database aktif
 * - Mengambil id_tagihan dari parameter GET
 * - Mengambil data tagihan dari database
 * - Menampilkan detail tagihan dan pilihan metode pembayaran (bank/virtual account)
 * - Form akan mengirim data ke pembayaran_lanjut.php untuk instruksi pembayaran
 */
include '../config/akses_pelanggan.php';
include '../config/koneksi.php';

$id_tagihan = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id_tagihan) {
    echo '<div class="alert alert-danger">ID Tagihan tidak ditemukan.</div>';
    exit;
}

// Ambil data tagihan
$tagihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT t.*, pg.tanggal, p.nama FROM tagihan t JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan WHERE t.id_tagihan='$id_tagihan'"));
if (!$tagihan) {
    echo '<div class="alert alert-danger">Tagihan tidak ditemukan.</div>';
    exit;
}

$metode_bank = ['BCA', 'Mandiri', 'BRI', 'BNI'];
$metode_va = ['OVO', 'Gopay', 'Shopeepay'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Tagihan</title>
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
        .table-pembayaran {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .table-pembayaran th {
            background: #0074c7;
            color: #fff;
        }
        .table-pembayaran tbody tr:hover {
            background: #e3f2fd;
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
        .detail-tagihan {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 24px;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="pln-header">
            <h2 class="mb-0">Pembayaran Tagihan</h2>
        </div>
        <div class="detail-tagihan mb-3">
            <strong>Nama:</strong> <?= $tagihan['nama'] ?><br>
            <strong>Tanggal:</strong> <?= $tagihan['tanggal'] ?><br>
            <strong>Total Tagihan:</strong> Rp <?= number_format($tagihan['jumlah_tagihan']) ?><br>
            <strong>Status:</strong> <?= $tagihan['status'] ?>
        </div>
        <form method="POST" action="pembayaran_lanjut.php">
            <input type="hidden" name="id_tagihan" value="<?= $id_tagihan ?>">
            <div class="row">
                <div class="col-md-6">
                    <h5>Transfer Bank</h5>
                    <table class="table table-bordered table-pembayaran">
                        <thead><tr><th>Pilih</th><th>Bank</th></tr></thead>
                        <tbody>
                            <?php foreach ($metode_bank as $bank): ?>
                            <tr>
                                <td><input type="radio" name="metode" value="Bank:<?= $bank ?>"></td>
                                <td><?= $bank ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Virtual Account</h5>
                    <table class="table table-bordered table-pembayaran">
                        <thead><tr><th>Pilih</th><th>Virtual Account</th></tr></thead>
                        <tbody>
                            <?php foreach ($metode_va as $va): ?>
                            <tr>
                                <td><input type="radio" name="metode" value="VA:<?= $va ?>"></td>
                                <td><?= $va ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" class="btn btn-pln mt-3">Konfirmasi Pembayaran</button>
            <a href="tagihan.php" class="btn btn-kembali mt-3">Kembali</a>
        </form>
    </div>
</body>
</html> 