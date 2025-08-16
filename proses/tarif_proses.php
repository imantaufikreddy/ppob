<?php
/*
 * Script ini memproses tambah, edit, dan hapus data tarif listrik PLN oleh admin.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika tambah: validasi daya unik, insert data tarif
 * - Jika update: update data tarif berdasarkan daya
 * - Jika hapus: menghapus data tarif berdasarkan daya
 * - Redirect ke halaman tarif setelah aksi
 */
include '../config/koneksi.php';
if (isset($_POST['tambah'])) {
  $daya = $_POST['daya'];
  $tarif = $_POST['tarif'];
  // Validasi daya unik
  $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tarif WHERE daya='$daya'"));
  if ($cek == 0) {
    mysqli_query($koneksi, "INSERT INTO tarif VALUES('$daya', '$tarif')");
  }
}
if (isset($_POST['update'])) {
  $daya = $_POST['daya'];
  $tarif = $_POST['tarif'];
  mysqli_query($koneksi, "UPDATE tarif SET tarif_per_kwh='$tarif' WHERE daya='$daya'");
}
if (isset($_GET['hapus'])) {
  $daya = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM tarif WHERE daya='$daya'");
}
header("Location: ../pages/tarif.php");
?> 