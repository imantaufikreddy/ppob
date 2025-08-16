<?php
/*
 * Script ini memproses penghapusan data pelanggan PLN beserta relasi (penggunaan, tagihan).
 * Alur:
 * - Memastikan koneksi database aktif
 * - Fungsi hapusPelanggan: menghapus tagihan, penggunaan, dan pelanggan berdasarkan id
 * - Jika parameter id ada, jalankan hapusPelanggan dan redirect ke halaman pelanggan
 */
include '../config/koneksi.php';

// Fungsi untuk menghapus data pelanggan beserta seluruh relasi (tagihan dan penggunaan)
function hapusPelanggan($id_pelanggan, $koneksi) {
    /*
     * Fungsi ini menghapus seluruh data tagihan, penggunaan, dan pelanggan berdasarkan id_pelanggan.
     * Urutan: hapus tagihan → hapus penggunaan → hapus pelanggan.
     */
    // Hapus tagihan → penggunaan → pelanggan
    $hapus_tagihan = "DELETE t FROM tagihan t
                      JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
                      WHERE pg.id_pelanggan = '$id_pelanggan'";
    mysqli_query($koneksi, $hapus_tagihan);

    $hapus_penggunaan = "DELETE FROM penggunaan WHERE id_pelanggan = '$id_pelanggan'";
    mysqli_query($koneksi, $hapus_penggunaan);

    $hapus_pelanggan = "DELETE FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'";
    mysqli_query($koneksi, $hapus_pelanggan);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    hapusPelanggan($id, $koneksi);
    header("Location: ../pages/pelanggan.php");
}
?> 