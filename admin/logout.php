<?php
require_once '../config/database.php';

// Hapus semua session admin
if (isset($_SESSION['admin_id'])) {
    unset($_SESSION['admin_id']);
}
if (isset($_SESSION['admin_nama'])) {
    unset($_SESSION['admin_nama']);
}
if (isset($_SESSION['admin_role'])) {
    unset($_SESSION['admin_role']);
}

// Hancurkan session jika tidak ada session pendaftar yang aktif
if (!isset($_SESSION['pendaftar_id'])) {
    session_destroy();
}

// Redirect ke halaman login admin
header('Location: login.php');
exit();
?>