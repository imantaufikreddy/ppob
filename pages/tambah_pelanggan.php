<?php
/*
 * Script ini adalah form tambah/edit pelanggan oleh admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Menyediakan form tambah/edit pelanggan (ID, nama, alamat, email, password, daya, tanggal registrasi)
 * - Jika edit, mengambil data pelanggan berdasarkan id
 * - Menyimpan data pelanggan baru/hasil edit ke database
 * - Terdapat fungsi PHP dan JS untuk generate ID pelanggan unik otomatis
 * - Form akan mengirim data ke pelanggan_proses.php
 */
include '../config/koneksi.php';

// Fungsi PHP untuk generate username unik otomatis untuk pelanggan
function generateUsername($koneksi) {
    /*
     * Fungsi ini menghasilkan ID pelanggan unik dengan prefix '12' dan 6 digit acak.
     * Mengecek ke database agar tidak ada duplikasi ID.
     * Return: string ID pelanggan unik
     */
    $prefix = '12';
    do {
        $rand = str_pad(strval(rand(0, 999999)), 6, '0', STR_PAD_LEFT);
        $username = $prefix . $rand;
        $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='$username'"));
    } while ($cek > 0);
    return $username;
}

$id = isset($_GET['id']) ? $_GET['id'] : generateUsername($koneksi);
$nama = '';
$alamat = '';
$daya = '';
$email = '';
$password = '';
$tanggal_registrasi = '';

if (isset($_GET['id'])) {
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='$id'"));
    $nama = $data['nama'];
    $alamat = $data['alamat'];
    $daya = $data['daya'];
    $email = isset($data['email']) ? $data['email'] : '';
    // Jangan tampilkan hash password di form edit
    $password = '';
    $tanggal_registrasi = isset($data['tanggal_registrasi']) ? $data['tanggal_registrasi'] : '';
} else {
    $tanggal_registrasi = date('Y-m-d');
}
$tarif = mysqli_query($koneksi, "SELECT * FROM tarif ORDER BY daya ASC");

if (!file_exists('../assets/fpdf/fpdf.php')) {
  die('FPDF tidak ditemukan! Pastikan folder fpdf ada di assets.');
}
require '../assets/fpdf/fpdf.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? "Edit" : "Tambah" ?> Pelanggan</title>
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
        .btn-pln {
            background: #ffe600;
            color: #222;
            border: none;
            font-weight: bold;
        }
        .btn-pln:hover {
            background: #fff200;
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
        <h2 class="mb-0"><?= $id ? "Edit" : "Tambah" ?> Pelanggan</h2>
    </div>
    
    <div class="form-container">
        <form method="POST" action="../proses/pelanggan_proses.php">
            <input type="hidden" name="old_id" value="<?= $id ?>">
            
            <div class="mb-3">
                <label class="form-label">ID Pelanggan:</label>
                <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" value="<?= $id ?>" required readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nama:</label>
                <input type="text" name="nama" class="form-control" value="<?= $nama ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= $alamat ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" value="<?= $password ?>" required>
                <div class="form-text text-muted">
                    Catatan: Password akan di-hash pada proses simpan.
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tanggal Registrasi:</label>
                <input type="date" name="tanggal_registrasi" class="form-control" value="<?= $tanggal_registrasi ?>" required readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Daya (Watt):</label>
                <select name="daya" class="form-control" required>
                    <option value="">-- Pilih Daya --</option>
                    <?php mysqli_data_seek($tarif, 0); while($row = mysqli_fetch_assoc($tarif)): ?>
                        <option value="<?= $row['daya'] ?>" <?= $row['daya'] == $daya ? 'selected' : '' ?>><?= $row['daya'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" name="<?= isset($_GET['id']) ? 'update' : 'tambah' ?>" class="btn btn-primary">Simpan</button>
                <a href="pelanggan.php" class="link-kembali">&larr; Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
// Fungsi JS untuk generate ID pelanggan random 8 digit (prefix 12)
function randomUsername() {
    /*
     * Fungsi ini menghasilkan ID pelanggan unik dengan prefix '12' dan 6 digit acak.
     * Digunakan untuk generate ID pelanggan otomatis saat tambah pelanggan.
     * Return: string ID pelanggan unik
     */
    var prefix = '12';
    var rand = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
    return prefix + rand;
}
<?php if (!isset($_GET['id'])): ?>
document.getElementById('id_pelanggan').value = randomUsername();
<?php endif; ?>
</script>
</body>
</html>