<?php
/*
 * Script ini memproses tambah, edit, dan hapus data penggunaan listrik PLN oleh admin.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika tambah: validasi tanggal, insert data penggunaan baru
 * - Jika update: validasi tanggal, update data penggunaan
 * - Jika hapus: menghapus data penggunaan berdasarkan id
 * - Redirect ke halaman penggunaan setelah aksi
 */
include '../config/koneksi.php';

if (isset($_POST['tambah'])) {
  $id_pelanggan = $_POST['id_pelanggan'];
  $tanggal = $_POST['tanggal'];
  $meter_awal = $_POST['meter_awal'];
  $meter_akhir = $_POST['meter_akhir'];

  // Validasi tanggal
  $pel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT tanggal_registrasi FROM pelanggan WHERE id_pelanggan='$id_pelanggan'"));
  if ($tanggal < $pel['tanggal_registrasi']) {
    die('Tanggal penggunaan tidak boleh sebelum tanggal registrasi pelanggan!');
  }

  mysqli_query($koneksi, "INSERT INTO penggunaan (id_pelanggan, tanggal, meter_awal, meter_akhir)
                          VALUES ('$id_pelanggan','$tanggal','$meter_awal','$meter_akhir')");
  header("Location: ../pages/penggunaan.php");
}

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $id_pelanggan = $_POST['id_pelanggan'];
  $tanggal = $_POST['tanggal'];
  $meter_awal = $_POST['meter_awal'];
  $meter_akhir = $_POST['meter_akhir'];

  // Validasi tanggal
  $pel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT tanggal_registrasi FROM pelanggan WHERE id_pelanggan='$id_pelanggan'"));
  if ($tanggal < $pel['tanggal_registrasi']) {
    die('Tanggal penggunaan tidak boleh sebelum tanggal registrasi pelanggan!');
  }

  mysqli_query($koneksi, "UPDATE penggunaan SET 
      id_pelanggan='$id_pelanggan',
      tanggal='$tanggal',
      meter_awal='$meter_awal',
      meter_akhir='$meter_akhir' 
      WHERE id_penggunaan='$id'");
  header("Location: ../pages/penggunaan.php");
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM penggunaan WHERE id_penggunaan='$id'");
  header("Location: ../pages/penggunaan.php");
}
?> 