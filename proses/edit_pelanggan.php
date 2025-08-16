<?php
/*
 * Script ini memproses edit data pelanggan PLN oleh admin.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika form update disubmit, update data pelanggan di database
 * - Redirect ke halaman pelanggan setelah aksi
 */
include '../config/koneksi.php';
if (isset($_POST['update'])) {
    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $daya = $_POST['daya'];
    mysqli_query($koneksi, "UPDATE pelanggan SET nama='$nama', alamat='$alamat', daya='$daya' WHERE id_pelanggan='$id'");
    header("Location: ../pages/pelanggan.php");
}
?> 