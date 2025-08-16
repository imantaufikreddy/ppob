<?php
/*
 * Script ini adalah form tambah/edit penggunaan listrik untuk admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Jika edit, mengambil data penggunaan berdasarkan id
 * - Mengambil data pelanggan untuk pilihan dropdown
 * - Menampilkan form tambah/edit penggunaan (tanggal, meter awal, meter akhir)
 * - Form akan mengirim data ke penggunaan_proses.php
 * - Validasi tanggal tidak boleh sebelum tanggal registrasi pelanggan
 */
include '../config/koneksi.php';

$id = $id_pelanggan =  $meter_awal = $meter_akhir = '';
$tanggal = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM penggunaan WHERE id_penggunaan='$id'"));
    $id_pelanggan = $edit['id_pelanggan'];
    $meter_awal = $edit['meter_awal'];
    $meter_akhir = $edit['meter_akhir'];
    $tanggal = isset($edit['tanggal']) ? $edit['tanggal'] : '';
}
$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $id ? "Edit" : "Tambah" ?> Penggunaan Listrik</title>
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
        <h2 class="mb-0"><?= $id ? "Edit" : "Tambah" ?> Penggunaan Listrik</h2>
    </div>
    
    <div class="form-container">
        <form method="POST" action="../proses/penggunaan_proses.php">
            <input type="hidden" name="id" value="<?= $id ?>">
            
            <div class="mb-3">
                <label class="form-label">Pelanggan:</label>
                <select name="id_pelanggan" class="form-control" required>
                    <option value="">--Pilih Pelanggan--</option>
                    <?php mysqli_data_seek($pelanggan, 0); while ($p = mysqli_fetch_assoc($pelanggan)): ?>
                    <option value="<?= $p['id_pelanggan'] ?>" <?= $p['id_pelanggan'] == $id_pelanggan ? 'selected' : '' ?>>
                        <?= $p['nama'] ?> (<?= $p['id_pelanggan'] ?>)
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $tanggal ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Meter Awal:</label>
                <input type="number" name="meter_awal" class="form-control" value="<?= $meter_awal ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Meter Akhir:</label>
                <input type="number" name="meter_akhir" class="form-control" value="<?= $meter_akhir ?>" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" name="<?= $id ? 'update' : 'tambah' ?>" class="btn btn-primary">Simpan</button>
                <a href="penggunaan.php" class="link-kembali">&larr; Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
// Validasi tanggal tidak boleh sebelum tanggal registrasi pelanggan
const pelangganSelect = document.querySelector('select[name="id_pelanggan"]');
const tanggalInput = document.getElementById('tanggal');
let pelangganData = {};
<?php mysqli_data_seek($pelanggan, 0); while($p = mysqli_fetch_assoc($pelanggan)): ?>
pelangganData['<?= $p['id_pelanggan'] ?>'] = '<?= $p['tanggal_registrasi'] ?>';
<?php endwhile; ?>
pelangganSelect.addEventListener('change', function() {
    const tglReg = pelangganData[this.value];
    if (tglReg) {
        tanggalInput.min = tglReg;
    } else {
        tanggalInput.min = '';
    }
});
</script>
</body>
</html> 