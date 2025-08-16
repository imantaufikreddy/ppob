<?php
/*
 * Script ini digunakan untuk mengirim reminder tagihan otomatis ke pelanggan (cron job).
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengambil data tagihan yang belum dibayar dan sudah jatuh tempo
 * - Mengirim (simulasi) reminder ke email pelanggan
 */
include 'config/koneksi.php';
$data = mysqli_query($koneksi, "
  SELECT p.email, pg.bulan, pg.tahun, t.jumlah_tagihan
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  WHERE t.status = 'Belum Bayar' AND DATEDIFF(NOW(), pg.tahun*12 + MONTH(STR_TO_DATE(pg.bulan, '%M'))) >= 5
");

while ($row = mysqli_fetch_assoc($data)) {
  echo "Reminder ke {$row['email']} untuk tagihan bulan {$row['bulan']} - Rp {$row['jumlah_tagihan']}<br>";
}
?> 