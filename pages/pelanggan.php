<?php
/*
 * Script ini menampilkan data seluruh pelanggan PLN untuk admin.
 * Alur:
 * - Memastikan koneksi database aktif dan user adalah admin
 * - Mengambil data pelanggan dari database
 * - Menampilkan tabel data pelanggan dengan fitur tambah, edit, dan hapus
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/koneksi.php';
include '../config/akses_admin.php';
$result = mysqli_query($koneksi, "SELECT * FROM pelanggan");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>
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
        .table-pln {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .table-pln th {
            background: #0074c7;
            color: #fff;
        }
        .table-pln tbody tr:hover {
            background: #e3f2fd;
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
            <h2 class="mb-0">Data Pelanggan</h2>
        </div>
        <a href="tambah_pelanggan.php" class="btn btn-pln mb-3">Tambah Pelanggan</a>
        <div class="table-responsive">
            <table class="table table-bordered table-pln">
                <thead>
                    <tr>
                        <th>ID</th><th>Nama</th><th>Alamat</th><th>Daya</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id_pelanggan'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td><?= $row['daya'] ?></td>
                        <td>
                            <a href="tambah_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="../proses/hapus_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pelanggan ini BESERTA penggunaan dan tagihannya?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="../dashboard.php" class="link-kembali">&larr; Kembali ke Dashboard</a>
    </div>
</body>
</html> 