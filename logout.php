<?php
/*
 * Script ini digunakan untuk logout user dari aplikasi PLN dan menghapus session login.
 * Alur:
 * - Memastikan session aktif
 * - Menghapus session login
 * - Redirect ke halaman login
 */
session_start();
session_destroy();
header("Location: login.php");
exit;
?> 