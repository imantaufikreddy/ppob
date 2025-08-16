<?php
/*
 * Script ini digunakan untuk export data tagihan listrik ke file Excel oleh admin PLN.
 * Alur:
 * - Memastikan user sudah login (session_check.php) dan koneksi database aktif
 * - Mengambil filter bulan dan tahun dari parameter GET
 * - Mengambil data tagihan dari database sesuai filter
 * - Mengatur header agar output berupa file Excel
 * - Menampilkan tabel data tagihan dalam format Excel
 */
include '../config/session_check.php';
include '../config/koneksi.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_tagihan.xls");

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$where = [];
if ($bulan) $where[] = "pg.bulan='$bulan'";
if ($tahun) $where[] = "pg.tahun='$tahun'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "SELECT 
    t.id_tagihan, p.nama, pg.bulan, pg.tahun, (pg.meter_akhir - pg.meter_awal) AS pemakaian, tf.tarif_per_kwh, t.jumlah_tagihan, t.status
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  JOIN tarif tf ON p.daya = tf.daya
  $where_sql
  ORDER BY pg.tahun DESC, pg.bulan DESC";
$data = mysqli_query($koneksi, $query);
?>
<table border="1">
  <tr>
    <th>Nama</th><th>Bulan</th><th>Tahun</th><th>Pemakaian</th><th>Tarif/kWh</th><th>Total</th><th>Status</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($data)): ?>
  <tr>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['bulan'] ?></td>
    <td><?= $row['tahun'] ?></td>
    <td><?= $row['pemakaian'] ?></td>
    <td><?= $row['tarif_per_kwh'] ?></td>
    <td><?= $row['jumlah_tagihan'] ?></td>
    <td><?= $row['status'] ?></td>
  </tr>
  <?php endwhile; ?>
</table> 