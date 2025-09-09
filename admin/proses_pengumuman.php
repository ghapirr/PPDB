<?php
require_once '../config/database.php';

// Security check: Ensure superadmin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'superadmin') {
    header('Location: login.php');
    exit();
}

// Cek apakah skor sudah dihitung
$query_check_skor = "SELECT COUNT(id) as count FROM pendaftaran WHERE skor_akhir IS NULL";
$result_check_skor = mysqli_fetch_assoc(mysqli_query($koneksi, $query_check_skor));
if ($result_check_skor['count'] > 0) {
    $_SESSION['pengumuman_error'] = "Masih ada pendaftar yang belum memiliki skor. Silakan jalankan proses ranking terlebih dahulu.";
    header('Location: pengumuman_admin.php');
    exit();
}

// Proses pengumuman
$query_jalur = mysqli_query($koneksi, "SELECT id, kuota FROM jalur");

$query_update = "UPDATE pendaftaran SET status_pendaftaran = ? WHERE id = ?";
$stmt_update = mysqli_prepare($koneksi, $query_update);

// Set semua status ke "Tidak Lulus" terlebih dahulu sebagai default
mysqli_query($koneksi, "UPDATE pendaftaran SET status_pendaftaran = 'Tidak Lulus'");

// Loop per jalur untuk menentukan kelulusan
while ($jalur = mysqli_fetch_assoc($query_jalur)) {
    $jalur_id = $jalur['id'];
    $kuota = $jalur['kuota'];

    // Ambil pendaftar teratas sesuai kuota
    $query_lulus = "SELECT id FROM pendaftaran WHERE jalur_id = ? ORDER BY skor_akhir DESC LIMIT ?";
    $stmt_lulus = mysqli_prepare($koneksi, $query_lulus);
    mysqli_stmt_bind_param($stmt_lulus, "ii", $jalur_id, $kuota);
    mysqli_stmt_execute($stmt_lulus);
    $result_lulus = mysqli_stmt_get_result($stmt_lulus);

    $status_lulus = 'Lulus';
    while ($pendaftar_lulus = mysqli_fetch_assoc($result_lulus)) {
        // Update status menjadi Lulus
        mysqli_stmt_bind_param($stmt_update, "si", $status_lulus, $pendaftar_lulus['id']);
        mysqli_stmt_execute($stmt_update);
    }
}

// Log the activity
$admin_id = $_SESSION['admin_id'];
$aktivitas = "Admin (ID: $admin_id) telah mempublikasikan hasil seleksi akhir.";
$query_log = "INSERT INTO log_aktivitas (admin_id, aktivitas) VALUES (?, ?)";
$stmt_log = mysqli_prepare($koneksi, $query_log);
mysqli_stmt_bind_param($stmt_log, "is", $admin_id, $aktivitas);
mysqli_stmt_execute($stmt_log);

$_SESSION['pengumuman_success'] = "Hasil seleksi telah berhasil diumumkan dan status pendaftar telah diperbarui.";
header('Location: pengumuman_admin.php');
exit();
?>