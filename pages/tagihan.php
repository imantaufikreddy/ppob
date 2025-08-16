<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);/*
 * Nama Modul   : tagihan.php
 * Fungsi       : Menampilkan dan mengelola data tagihan listrik untuk admin dan pelanggan PLN.
 * Input        : Data tagihan dari database, serta input aksi (generate, hapus, bayar) dari user.
 * Output       : Tabel tagihan listrik yang bisa dilihat, dikelola, atau dibayar sesuai level user.
 * Alur         :
 * - Memastikan koneksi database aktif dan session dimulai
 * - Jika admin: tampilkan seluruh data tagihan, serta fitur generate dan hapus tagihan
 * - Jika pelanggan: tampilkan tagihan milik sendiri dan fitur pembayaran
 * - Menampilkan tabel data tagihan dengan tombol aksi sesuai level user
 * - Menyediakan tombol untuk cetak tagihan dan kembali ke dashboard
 * Tanggal Revisi: 21 Juli 2025
 */


include '../config/koneksi.php';
session_start();
$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Query tagihan
if ($level == 1) { // Admin
  $query = "SELECT 
      t.id_tagihan, 
      p.nama, 
      pg.tanggal,
      pg.meter_awal, 
      pg.meter_akhir, 
      (pg.meter_akhir - pg.meter_awal) AS pemakaian,
      tf.tarif_per_kwh,
      t.jumlah_tagihan,
      t.status
    FROM tagihan t
    JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
    JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
    JOIN tarif tf ON p.daya = tf.daya";
} else { // Pelanggan
  $id_pelanggan = $id_user;
  $query = "SELECT 
      t.id_tagihan, 
      p.nama, 
      pg.tanggal,
      pg.meter_awal, 
      pg.meter_akhir, 
      (pg.meter_akhir - pg.meter_awal) AS pemakaian,
      tf.tarif_per_kwh,
      t.jumlah_tagihan,
      t.status
    FROM tagihan t
    JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
    JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
    JOIN tarif tf ON p.daya = tf.daya
    WHERE p.id_pelanggan = '$id_pelanggan'";
}
$data = $query ? mysqli_query($koneksi, $query) : false;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Tagihan</title>
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
        .table-pln {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .table-pln th {
            background: #0074c7;
            color: #fff;
        }
        .table-pln tbody tr:hover {
            background: #e3f2fd;
        }
        .btn-pln {
            background: #ffe600;
            color: #222;
            border: none;
            font-weight: bold;
        }
        .btn-pln:hover {
            background: #fff200;
            color: #0074c7;
        }
        .link-kembali {
            color: #0074c7;
            font-weight: bold;
        }
        .link-kembali:hover {
            color: #ffe600;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="pln-header">
        <h2 class="mb-0">Data Tagihan</h2>
    </div>
    <?php if ($level == 1): ?>
      <a href="tambah_tagihan.php" class="btn btn-pln mb-3">Generate Tagihan</a>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-pln">
            <thead>
                <tr>
                    <th>Nama</th><th>Tanggal</th><th>Pemakaian</th><th>Tarif/kWh</th><th>Total</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($data && mysqli_num_rows($data) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['pemakaian'] ?></td>
                    <td><?= $row['tarif_per_kwh'] ?></td>
                    <td>Rp <?= number_format($row['jumlah_tagihan']) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                      <?php if ($level == 1): ?>
                        <a href="../proses/tagihan_proses.php?hapus=<?= $row['id_tagihan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tagihan ini?')">Hapus</a>
                      <?php else: ?>
                        <?php if ($row['status'] == 'Belum Bayar'): ?>
                          <a href="pembayaran.php?id=<?= $row['id_tagihan'] ?>" class="btn btn-sm btn-success">Bayar</a>
                        <?php endif; ?>
                      <?php endif; ?>
                      <a href="cetak_tagihan.php?id=<?= $row['id_tagihan'] ?>" target="_blank" class="btn btn-sm btn-pln">Cetak</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">Tidak ada data tagihan.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="../dashboard.php" class="link-kembali">&larr; Kembali ke Dashboard</a>
</div>
</body>
</html> 
