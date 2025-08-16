<?php
/*
 * Script ini adalah form edit data pelanggan untuk admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif dan user adalah admin
 * - Mengambil data pelanggan berdasarkan id
 * - Mengambil data tarif untuk pilihan daya
 * - Menampilkan form edit pelanggan (nama, alamat, email, password, daya, tanggal registrasi)
 * - Form akan mengirim data ke edit_pelanggan.php di folder proses
 */
include '../config/koneksi.php';
include '../config/akses_admin.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='$id'"));
$tarif = mysqli_query($koneksi, "SELECT * FROM tarif ORDER BY daya ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Pelanggan</title>
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
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
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
        <h2 class="mb-0">Edit Pelanggan</h2>
    </div>
    
    <div class="form-container">
        <form method="POST" action="../proses/edit_pelanggan.php">
            <input type="hidden" name="id_pelanggan" value="<?= $data['id_pelanggan'] ?>">
            
            <div class="mb-3">
                <label class="form-label">ID Pelanggan:</label>
                <input type="text" class="form-control" value="<?= $data['id_pelanggan'] ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nama:</label>
                <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= $data['alamat'] ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= isset($data['email']) ? $data['email'] : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tanggal Registrasi:</label>
                <input type="date" class="form-control" value="<?= isset($data['tanggal_registrasi']) ? $data['tanggal_registrasi'] : '' ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Daya (Watt):</label>
                <select name="daya" class="form-control" required>
                    <option value="">-- Pilih Daya --</option>
                    <?php mysqli_data_seek($tarif, 0); while($row = mysqli_fetch_assoc($tarif)): ?>
                        <option value="<?= $row['daya'] ?>" <?= $row['daya'] == $data['daya'] ? 'selected' : '' ?>><?= $row['daya'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" name="update" class="btn btn-primary">Simpan</button>
                <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html> 