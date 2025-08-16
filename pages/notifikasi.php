<?php
/*
 * Script ini menampilkan notifikasi tagihan listrik untuk pelanggan PLN.
 * Alur:
 * - Memastikan user adalah pelanggan (akses_pelanggan.php) dan koneksi database aktif
 * - Mengambil id_pelanggan dari session
 * - Mengambil data tagihan yang belum dibayar dari database
 * - Menampilkan tabel notifikasi tagihan belum dibayar
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/akses_pelanggan.php';
include '../config/koneksi.php';

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'"));
$pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='{$user['username']}'"));
$id_pelanggan = $pelanggan['id_pelanggan'];

$query = "SELECT t.id_tagihan, pg.bulan, pg.tahun, t.jumlah_tagihan, t.status
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  WHERE p.id_pelanggan = '$id_pelanggan' AND t.status = 'Belum Bayar'";
$data = mysqli_query($koneksi, $query);
$count = mysqli_num_rows($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Tagihan</title>
    <?php include '../assets/bootstrap.html'; ?>
</head>
<body class="container mt-4">
    <h2>🔔 Notifikasi Tagihan
      <?php if ($count > 0): ?>
        <span class="badge bg-danger"><?= $count ?></span>
      <?php endif; ?>
    </h2>
    <table class="table table-bordered table-responsive">
      <tr>
        <th>Bulan</th><th>Tahun</th><th>Total</th><th>Status</th><th>Aksi</th>
      </tr>
      <?php if ($count == 0): ?>
        <tr><td colspan="5" class="text-center">Tidak ada tagihan belum dibayar</td></tr>
      <?php endif; ?>
      <?php while ($row = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td><?= $row['bulan'] ?></td>
        <td><?= $row['tahun'] ?></td>
        <td>Rp <?= number_format($row['jumlah_tagihan']) ?></td>
        <td><span class="badge bg-warning text-dark">Belum Bayar</span></td>
        <td><a href="tagihan.php" class="btn btn-sm btn-primary">Bayar</a></td>
      </tr>
      <?php endwhile; ?>
    </table>
    <a href="../dashboard.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</body>
</html> 