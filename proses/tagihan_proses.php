<?php
/*
 * Script ini memproses tambah, edit, hapus, dan generate tagihan listrik PLN oleh admin.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika generate: ambil data penggunaan, hitung tagihan, insert ke tabel tagihan
 * - Jika generate_all: generate tagihan otomatis untuk seluruh penggunaan yang belum ada tagihannya
 * - Jika lunas: update status tagihan menjadi lunas
 * - Jika hapus: menghapus data tagihan berdasarkan id
 * - Redirect ke halaman tagihan setelah aksi
 */
include '../config/koneksi.php';

// Proses generate satu tagihan berdasarkan id_penggunaan
if (isset($_POST['generate'])) {
  $id = $_POST['id_penggunaan'];

  // Ambil data penggunaan
  $penggunaan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT pg.*, pl.daya FROM penggunaan pg
    JOIN pelanggan pl ON pg.id_pelanggan = pl.id_pelanggan
    WHERE pg.id_penggunaan='$id'
  "));

  // Ambil tarif
  $daya = $penggunaan['daya'];
  echo "DEBUG - Daya Pelanggan: $daya<br>";
  $tarif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tarif WHERE daya='$daya'"));

  if (!$tarif) {
    echo "❌ Tarif tidak ditemukan untuk daya $daya!<br>";
    echo "Silakan tambahkan daya $daya ke tabel tarif di database.";
    exit;
  }

  $pemakaian = $penggunaan['meter_akhir'] - $penggunaan['meter_awal'];
  $jumlah_tagihan = $pemakaian * $tarif['tarif_per_kwh'];

  // Validasi
  if ($pemakaian <= 0) {
    echo "Pemakaian tidak valid!";
    exit;
  }

  $sql = "INSERT INTO tagihan (id_penggunaan, jumlah_tagihan, status) 
          VALUES ('$id', '$jumlah_tagihan', 'Belum Bayar')";
  if (!mysqli_query($koneksi, $sql)) {
      echo mysqli_error($koneksi);
      exit;
  }
  header("Location: ../pages/tagihan.php");
}

// Proses generate semua tagihan otomatis untuk seluruh penggunaan yang belum ada tagihannya
if (isset($_GET['generate_all'])) {
  // Ambil semua penggunaan yang belum ada tagihannya
  $penggunaan = mysqli_query($koneksi, "
    SELECT pg.*, pl.daya FROM penggunaan pg
    LEFT JOIN tagihan t ON pg.id_penggunaan = t.id_penggunaan
    JOIN pelanggan pl ON pg.id_pelanggan = pl.id_pelanggan
    WHERE t.id_penggunaan IS NULL
  ");
  while ($row = mysqli_fetch_assoc($penggunaan)) {
    $id = $row['id_penggunaan'];
    $daya = $row['daya'];
    $tarif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tarif WHERE daya='$daya'"));
    if (!$tarif) {
      echo "❌ Tarif tidak ditemukan untuk daya $daya!<br>";
      echo "Silakan tambahkan daya $daya ke tabel tarif di database.";
      continue;
    }
    $pemakaian = $row['meter_akhir'] - $row['meter_awal'];
    $jumlah_tagihan = $pemakaian * $tarif['tarif_per_kwh'];
    if ($pemakaian > 0 && $tarif) {
      $sql = "INSERT INTO tagihan (id_penggunaan, jumlah_tagihan, status) 
              VALUES ('$id', '$jumlah_tagihan', 'Belum Bayar')";
      mysqli_query($koneksi, $sql);
    }
  }
  header("Location: ../pages/tagihan.php");
}

// Proses update status tagihan menjadi lunas
if (isset($_GET['lunas'])) {
  $id = $_GET['lunas'];
  mysqli_query($koneksi, "UPDATE tagihan SET status='Lunas' WHERE id_tagihan='$id'");
  header("Location: ../pages/tagihan.php");
}

// Proses hapus tagihan
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM tagihan WHERE id_tagihan='$id'");
  header("Location: ../pages/tagihan.php");
}
?> 