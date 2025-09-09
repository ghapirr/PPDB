<?php
require_once 'config/database.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pendaftar_id = $_SESSION['pendaftar_id'];
    $jalur_id = $_POST['jalur_id'] ?? null;

    // Validasi
    if (empty($jalur_id)) {
        $_SESSION['form_error'] = "Anda harus memilih jalur pendaftaran.";
        header('Location: form_pendaftaran.php');
        exit();
    }

    // Cek apakah sudah pernah mendaftar
    $query_check = "SELECT id FROM pendaftaran WHERE pendaftar_id = ? LIMIT 1";
    $stmt_check = mysqli_prepare($koneksi, $query_check);
    mysqli_stmt_bind_param($stmt_check, "i", $pendaftar_id);
    mysqli_stmt_execute($stmt_check);
    if (mysqli_stmt_get_result($stmt_check)->num_rows > 0) {
        header('Location: dashboard.php');
        exit();
    }

    // Generate Nomor Pendaftaran Unik
    // Format: PPDB + TAHUN + ID Pendaftar (di-padding)
    $tahun = date('Y');
    $no_pendaftaran = 'PPDB' . $tahun . str_pad($pendaftar_id, 4, '0', STR_PAD_LEFT);

    // Insert ke tabel pendaftaran
    $query_insert = "INSERT INTO pendaftaran (no_pendaftaran, pendaftar_id, jalur_id, status_pendaftaran) VALUES (?, ?, ?, 'Proses')";
    $stmt_insert = mysqli_prepare($koneksi, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "sii", $no_pendaftaran, $pendaftar_id, $jalur_id);

    if (mysqli_stmt_execute($stmt_insert)) {
        // Berhasil, redirect ke dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Gagal
        $_SESSION['form_error'] = "Terjadi kesalahan pada sistem. Gagal membuat nomor pendaftaran.";
        header('Location: form_pendaftaran.php');
        exit();
    }

} else {
    // Bukan POST request
    header('Location: form_pendaftaran.php');
    exit();
}
?>