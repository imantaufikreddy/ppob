<?php
/*
 * Script ini memproses tambah, edit, dan hapus data pelanggan PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika tambah: validasi email/username unik, insert data pelanggan
 * - Jika update: update data pelanggan berdasarkan id
 * - Jika hapus: menghapus data pelanggan berdasarkan id
 * - Redirect ke halaman pelanggan setelah aksi
 */
include '../config/koneksi.php';

// Ambil data dari POST
$id_pelanggan = $_POST['id_pelanggan'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$password = $_POST['password'];
$tanggal_registrasi = $_POST['tanggal_registrasi'];
$daya = $_POST['daya'];

// Hash password sebelum simpan
if (!empty($password)) {
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
}

// Tambah
if (isset($_POST['tambah'])) {
  $id = $_POST['id_pelanggan'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $daya = $_POST['daya'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $tanggal_registrasi = $_POST['tanggal_registrasi'];
  
  // Validasi email/username unik
  $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email' OR username='$id'"));
  if ($cek > 0) {
    // Redirect kembali dengan pesan error
    header("Location: ../pages/tambah_pelanggan.php?error=Email%20atau%20Username%20sudah%20terdaftar");
    exit;
  }
  // Insert ke tabel pelanggan saja
  $query = "INSERT INTO pelanggan (id_pelanggan, nama, alamat, email, password, tanggal_registrasi, daya)
            VALUES ('$id_pelanggan', '$nama', '$alamat', '$email', '$password_hashed', '$tanggal_registrasi', '$daya')";
  mysqli_query($koneksi, $query);
  header("Location: ../pages/pelanggan.php");
  exit;
}

// Update
if (isset($_POST['update'])) {
  $old = $_POST['old_id'];
  $id = $_POST['id_pelanggan'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $daya = $_POST['daya'];
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $tanggal_registrasi = isset($_POST['tanggal_registrasi']) ? $_POST['tanggal_registrasi'] : '';
  $set = "id_pelanggan='$id', nama='$nama', alamat='$alamat', daya='$daya'";
  if ($email !== '') $set .= ", email='$email'";
  if ($password !== '') $set .= ", password='$password'";
  if ($tanggal_registrasi !== '') $set .= ", tanggal_registrasi='$tanggal_registrasi'";
  // Jika password diisi, update password, jika tidak, jangan update password
  if (!empty($password)) {
      $query = "UPDATE pelanggan SET 
                  nama='$nama', 
                  alamat='$alamat', 
                  email='$email', 
                  password='$password_hashed', 
                  tanggal_registrasi='$tanggal_registrasi', 
                  daya='$daya'
                WHERE id_pelanggan='$id_pelanggan'";
  } else {
      $query = "UPDATE pelanggan SET 
                  nama='$nama', 
                  alamat='$alamat', 
                  email='$email', 
                  tanggal_registrasi='$tanggal_registrasi', 
                  daya='$daya'
                WHERE id_pelanggan='$id_pelanggan'";
  }
  mysqli_query($koneksi, $query);
  header("Location: ../pages/pelanggan.php");
}

// Hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan='$id'");
  header("Location: ../pages/pelanggan.php");
}
?>