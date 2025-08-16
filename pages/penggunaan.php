<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);/*
 * Nama Modul   : penggunaan.php
 * Fungsi       : Menampilkan dan mengelola data penggunaan listrik pelanggan oleh admin PLN.
 * Input        : Tidak langsung dari user, tetapi dari database (data penggunaan).
 * Output       : Tabel data penggunaan listrik dengan opsi tambah, edit, dan hapus.
 * Alur         :
 * - Memastikan koneksi database aktif dan user memiliki akses admin
 * - Mengambil data penggunaan dari tabel 'penggunaan'
 * - Menampilkan data dalam bentuk tabel interaktif
 * - Menyediakan tombol untuk tambah data, edit, hapus, dan kembali ke dashboard
 * Tanggal Revisi: 21 Juli 2025
 */

include '../config/koneksi.php';
include '../config/akses_admin.php';
$query = "SELECT pg.*, pl.nama FROM penggunaan pg JOIN pelanggan pl ON pg.id_pelanggan = pl.id_pelanggan ORDER BY pg.tanggal DESC";
$data = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Kelola Penggunaan</title>
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
    <h2 class="mb-0">Kelola Penggunaan Listrik</h2>
  </div>
  <a href="tambah_penggunaan.php" class="btn btn-pln mb-3">Tambah Penggunaan</a>
  <div class="table-responsive">
    <table class="table table-bordered table-pln">
      <thead>
        <tr>
          <th>ID Pelanggan</th><th>Nama</th><th>Tanggal</th>
          <th>Meter Awal</th><th>Meter Akhir</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td><?= $row['id_pelanggan'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['meter_awal'] ?></td>
        <td><?= $row['meter_akhir'] ?></td>
        <td>
          <a href="tambah_penggunaan.php?id=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="../proses/penggunaan_proses.php?hapus=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus penggunaan ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <a href="../dashboard.php" class="link-kembali">&larr; Kembali ke Dashboard</a>
</div>
</body>
</html>