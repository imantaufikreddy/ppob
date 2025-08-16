<?php
/*
 * Script ini menampilkan riwayat pembayaran tagihan listrik untuk pelanggan PLN.
 * Alur:
 * - Memastikan user adalah pelanggan (akses_pelanggan.php) dan koneksi database aktif
 * - Mengambil id_pelanggan dari session
 * - Mengambil data tagihan yang sudah lunas dari database
 * - Menampilkan tabel riwayat pembayaran
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/akses_pelanggan.php';
include '../config/koneksi.php';

$id_user = $_SESSION['id_user'];
$id_pelanggan = $id_user;

$query = "SELECT t.id_tagihan, pg.tanggal, t.jumlah_tagihan, t.status
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  WHERE p.id_pelanggan = '$id_pelanggan' AND t.status = 'Lunas'";
$data = mysqli_query($koneksi, $query);
$count = mysqli_num_rows($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembayaran</title>
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
        .badge-lunas {
            background: #ffe600;
            color: #222;
            font-weight: bold;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 1rem;
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="pln-header">
            <h2 class="mb-0">Riwayat Pembayaran</h2>
        </div>
        <table class="table table-bordered table-responsive table-pln">
          <thead>
            <tr>
              <th>Tanggal</th><th>Total</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($count == 0): ?>
            <tr><td colspan="3" class="text-center">Belum ada riwayat pembayaran</td></tr>
          <?php endif; ?>
          <?php while ($row = mysqli_fetch_assoc($data)): ?>
          <tr>
            <td><?= $row['tanggal'] ?></td>
            <td>Rp <?= number_format($row['jumlah_tagihan']) ?></td>
            <td><span class="badge-lunas">Lunas</span></td>
          </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
        <a href="../dashboard.php" class="btn btn-pln mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html> 