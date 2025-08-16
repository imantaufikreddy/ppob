<?php
/*
 * Script ini adalah form tambah/edit tarif listrik untuk admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika edit, mengambil data tarif berdasarkan daya
 * - Menampilkan form tambah/edit tarif (daya, tarif per kWh)
 * - Form akan mengirim data ke tarif_proses.php
 * - Terdapat tombol kembali ke halaman tarif
 */
include '../config/koneksi.php';
$daya = $_GET['daya'] ?? '';
$data = $daya ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tarif WHERE daya='$daya'")) : ['tarif_per_kwh' => ''];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $daya ? 'Edit' : 'Tambah' ?> Tarif</title>
    <?php include '../assets/bootstrap.html'; ?>
</head>
<body class="container mt-5">
    <h2><?= $daya ? 'Edit' : 'Tambah' ?> Tarif Listrik</h2>
    <form method="POST" action="../proses/tarif_proses.php">
      Daya (Watt): <input type="number" name="daya" value="<?= $daya ?>" required class="form-control mb-2" <?= $daya ? 'readonly' : '' ?>><br>
      Tarif per kWh: <input type="number" name="tarif" value="<?= $data['tarif_per_kwh'] ?>" required class="form-control mb-2"><br>
      <button name="<?= $daya ? 'update' : 'tambah' ?>" class="btn btn-primary">Simpan</button>
    </form>
    <a href="tarif.php" class="btn btn-secondary mt-3">Kembali</a>
</body>
</html> 