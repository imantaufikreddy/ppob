<?php
/*
 * Script ini adalah form tambah tagihan listrik untuk admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Mengambil data penggunaan yang belum memiliki tagihan
 * - Menampilkan form untuk memilih data penggunaan dan generate tagihan baru
 * - Form akan mengirim data ke tagihan_proses.php
 */
include '../config/koneksi.php';
$penggunaan = mysqli_query($koneksi, "
  SELECT pg.*, pl.id_pelanggan, pl.nama, pl.daya FROM penggunaan pg 
  LEFT JOIN tagihan t ON pg.id_penggunaan = t.id_penggunaan
  JOIN pelanggan pl ON pg.id_pelanggan = pl.id_pelanggan
  WHERE t.id_penggunaan IS NULL
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Tagihan Baru</title>
    <?php include '../assets/bootstrap.html'; ?>
    <style>
        body {
            background: #f4faff;
        }
        .pln-header {
            background: #0074c7;
            color: #fff;
            padding: 20px 0 10px 0;
            border-left: 10px solid #ffe600;
            border-radius: 0 0 10px 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            max-width: 600px;
            margin: 0 auto;
        }
        .form-label {
            font-weight: bold;
            color: #0074c7;
        }
        .btn-primary {
            background: #0074c7;
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .link-kembali {
            color: #0074c7;
            font-weight: bold;
        }
        .link-kembali:hover {
            color: #ffe600;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="pln-header">
        <h2 class="mb-0">Generate Tagihan Baru</h2>
    </div>
    
    <div class="form-container">
        <form method="POST" action="../proses/tagihan_proses.php">
            <div class="mb-3">
                <label class="form-label">Pilih Data Penggunaan:</label>
                <select name="id_penggunaan" class="form-control" required>
                    <option value="">-- Pilih Data Penggunaan --</option>
                    <?php while ($row = mysqli_fetch_assoc($penggunaan)): ?>
                    <option value="<?= $row['id_penggunaan'] ?>">
                        <?= $row['id_pelanggan'] ?> - <?= $row['nama'] ?> (<?= $row['tanggal'] ?> - Meter: <?= $row['meter_awal'] ?> - <?= $row['meter_akhir'] ?>)
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" name="generate" class="btn btn-primary">Generate Tagihan</button>
                <a href="tagihan.php" class="link-kembali">&larr; Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html> 