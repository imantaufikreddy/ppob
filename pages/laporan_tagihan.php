<?php
/*
 * Script ini menampilkan laporan tagihan listrik untuk admin PLN.
 * Alur:
 * - Memastikan user sudah login (session_check.php) dan koneksi database aktif
 * - Mengambil filter bulan dan tahun dari parameter GET
 * - Mengambil data tagihan dari database sesuai filter
 * - Menampilkan tabel laporan tagihan
 * - Terdapat fitur export ke Excel dan cetak laporan
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/session_check.php';
include '../config/koneksi.php';

// Ambil filter
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Array nama bulan untuk konversi
$nama_bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

// Query data tagihan
$where = [];
if ($bulan) $where[] = "MONTH(pg.tanggal)='".str_pad(array_search($bulan, $nama_bulan)+1,2,'0',STR_PAD_LEFT)."'";
if ($tahun) $where[] = "YEAR(pg.tanggal)='$tahun'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "SELECT 
    t.id_tagihan, p.nama, pg.tanggal, (pg.meter_akhir - pg.meter_awal) AS pemakaian, tf.tarif_per_kwh, t.jumlah_tagihan, t.status
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  JOIN tarif tf ON p.daya = tf.daya
  $where_sql
  ORDER BY pg.tanggal DESC";
$data = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tagihan</title>
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
        <h2 class="mb-0">Laporan Tagihan Listrik</h2>
    </div>
    <form method="GET" class="row g-3 mb-3">
      <div class="col-auto">
        <select name="bulan" class="form-select">
          <option value="">- Bulan -</option>
          <?php foreach (["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"] as $b): ?>
            <option value="<?= $b ?>" <?= $bulan==$b?'selected':'' ?>><?= $b ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <input type="number" name="tahun" class="form-control" placeholder="Tahun" value="<?= $tahun ?>">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-success">Filter</button>
      </div>
      <div class="col-auto">
        <a href="export_excel.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn btn-pln" target="_blank">Export Excel</a>
      </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-pln">
          <thead>
            <tr>
              <th>Nama</th><th>Tanggal</th><th>Pemakaian</th><th>Tarif/kWh</th><th>Total</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php if (mysqli_num_rows($data) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
            <tr>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['tanggal'] ?></td>
              <td><?= $row['pemakaian'] ?></td>
              <td><?= $row['tarif_per_kwh'] ?></td>
              <td>Rp <?= number_format($row['jumlah_tagihan']) ?></td>
              <td><?= $row['status'] ?></td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">Belum ada tagihan listrik di bulan ini.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
    </div>
    <a href="../dashboard.php" class="link-kembali">&larr; Kembali ke Dashboard</a>
</div>
</body>
</html> 