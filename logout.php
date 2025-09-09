<?php
require_once 'config/database.php';

// Hapus semua session pendaftar
if (isset($_SESSION['pendaftar_id'])) {
    unset($_SESSION['pendaftar_id']);
}
if (isset($_SESSION['nama_lengkap'])) {
    unset($_SESSION['nama_lengkap']);
}

// Hancurkan session jika tidak ada session admin yang aktif
if (!isset($_SESSION['admin_id'])) {
    session_destroy();
}

// Redirect ke halaman utama
header('Location: index.php');
exit();
?>