<?php
/*
 * Script ini digunakan untuk mengirim notifikasi WhatsApp pada aplikasi PLN.
 * Cara kerja:
 * - Mengambil parameter 'nomor' dan 'pesan' dari URL (GET).
 * - Menampilkan simulasi pengiriman pesan WhatsApp ke nomor tujuan dengan isi pesan yang diberikan.
 *
 * Tidak ada fungsi (function) khusus, seluruh proses berjalan secara langsung saat file diakses.
 * Contoh penggunaan:
 *   api/whatsapp_send.php?nomor=08123456789&pesan=Tagihan+Anda+Sudah+Terbit
 */
$nomor = $_GET['nomor'];
$pesan = $_GET['pesan'];
echo "Simulasi kirim WhatsApp ke $nomor: $pesan";
?> 