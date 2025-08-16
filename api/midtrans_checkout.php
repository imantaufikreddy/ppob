<?php
/*
 * Script ini digunakan untuk simulasi integrasi pembayaran Midtrans pada aplikasi PLN.
 * Cara kerja:
 * - Mengambil parameter 'id' (id_tagihan) dan 'jumlah' dari URL (GET).
 * - Menampilkan simulasi redirect ke halaman pembayaran Midtrans Snap dengan id tagihan dan jumlah yang diberikan.
 *
 * Tidak ada fungsi (function) khusus, seluruh proses berjalan secara langsung saat file diakses.
 * Contoh penggunaan:
 *   api/midtrans_checkout.php?id=12345&jumlah=100000
 */
$id_tagihan = $_GET['id'];
$jumlah = $_GET['jumlah'];
echo "Redirect ke Midtrans Snap: https://app.midtrans.com/snap/v2/vtweb/$id_tagihan?amount=$jumlah";
?> 