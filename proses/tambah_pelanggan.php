<?php
/*
 * Script ini memproses tambah pelanggan PLN (versi lama/legacy).
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika form disubmit, insert data pelanggan ke database
 * - Redirect ke halaman pelanggan setelah aksi
 * - Tidak digunakan jika sudah memakai proses/pelanggan_proses.php
 */
include '../config/koneksi.php';
if (isset($_POST['simpan'])) {
    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $daya = $_POST['daya'];
    mysqli_query($koneksi, "INSERT INTO pelanggan VALUES('$id','$nama','$alamat','$daya')");
    header("Location: ../pages/pelanggan.php");
}
?> 