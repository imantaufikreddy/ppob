<?php
/*
 * Script ini adalah form update password & email pelanggan oleh admin PLN.
 * Alur:
 * - Memastikan koneksi database aktif
 * - Menyediakan form untuk memilih pelanggan
 * - Jika pelanggan dipilih, menampilkan form update email dan password
 * - Menyimpan perubahan ke database
 * - Terdapat tombol kembali ke dashboard
 */
include '../config/koneksi.php';
$msg = '';
$selected_id = isset($_POST['id_pelanggan']) ? $_POST['id_pelanggan'] : '';
$email = '';
$password = '';
if ($selected_id) {
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT email, password FROM pelanggan WHERE id_pelanggan='$selected_id'"));
    if ($data) {
        $email = $data['email'];
        $password = $data['password'];
    }
}
if (isset($_POST['update'])) {
    $id = $_POST['id_pelanggan'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    mysqli_query($koneksi, "UPDATE pelanggan SET password='$password', email='$email' WHERE id_pelanggan='$id'");
    $msg = '<div class="alert alert-success">Data akun pelanggan berhasil diupdate!</div>';
}
$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Akun Pelanggan</title>
    <?php include '../assets/bootstrap.html'; ?>
    <script>
    function submitForm() {
        document.getElementById('pilihForm').submit();
    }
    </script>
</head>
<body class="container mt-5">
    <h2>Update Password & Email Pelanggan</h2>
    <?= $msg ?>
    <form method="POST" id="pilihForm">
        Pilih Pelanggan:
        <select name="id_pelanggan" class="form-control mb-2" required onchange="submitForm()">
            <option value="">--Pilih--</option>
            <?php mysqli_data_seek($pelanggan, 0); while($p = mysqli_fetch_assoc($pelanggan)): ?>
                <option value="<?= $p['id_pelanggan'] ?>" <?= $selected_id == $p['id_pelanggan'] ? 'selected' : '' ?>><?= $p['nama'] ?> (<?= $p['id_pelanggan'] ?>)</option>
            <?php endwhile; ?>
        </select><br>
    </form>
    <?php if ($selected_id): ?>
    <form method="POST">
        <input type="hidden" name="id_pelanggan" value="<?= $selected_id ?>">
        Email: <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($email) ?>" required><br>
        Password: <input type="text" name="password" class="form-control mb-2" value="<?= htmlspecialchars($password) ?>" required><br>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
    <?php endif; ?>
    <a href="../dashboard.php">Kembali ke Dashboard</a>
</body>
</html> 