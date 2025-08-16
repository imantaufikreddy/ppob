<?php
/*
 * Script ini menampilkan data tarif listrik untuk admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengambil data tarif dari database
 * - Menampilkan tabel data tarif dengan fitur tambah, edit, dan hapus
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/koneksi.php';
$data = mysqli_query($koneksi, "SELECT * FROM tarif");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Tarif Listrik</title>
    <?php include '../assets/bootstrap.html'; ?>
</head>
<body class="container mt-4">
    <h2>Data Tarif Listrik</h2>
    <a href="tambah_tarif.php" class="btn btn-success mb-2">Tambah Tarif</a>
    <table class="table table-bordered">
      <tr>
        <th>Daya (Watt)</th><th>Tarif per kWh</th><th>Aksi</th>
      </tr>
      <?php while($row = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td><?= $row['daya'] ?></td>
        <td><?= $row['tarif_per_kwh'] ?></td>
        <td>
          <a href="tambah_tarif.php?daya=<?= $row['daya'] ?>" class="btn btn-warning btn-sm">Edit</a> |
          <a href="../proses/tarif_proses.php?hapus=<?= $row['daya'] ?>" onclick="return confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
    <a href="../dashboard.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</body>
</html> 