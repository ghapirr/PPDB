<?php
require_once '../config/database.php';

// Security check: Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Validation
$doc_id = $_GET['doc_id'] ?? null;
$status = $_GET['status'] ?? null;
$pendaftaran_id = $_GET['pendaftaran_id'] ?? null;
$admin_id = $_SESSION['admin_id'];

$allowed_statuses = ['Valid', 'Tidak Valid', 'Belum Dicek'];

if (empty($doc_id) || !is_numeric($doc_id) || empty($status) || !in_array($status, $allowed_statuses) || empty($pendaftaran_id)) {
    // Redirect with error if data is invalid
    header('Location: pendaftar.php?error=invalid_request');
    exit();
}

// Update the document status
$query_update = "UPDATE dokumen SET status_verifikasi = ? WHERE id = ?";
$stmt_update = mysqli_prepare($koneksi, $query_update);
mysqli_stmt_bind_param($stmt_update, "si", $status, $doc_id);

if (mysqli_stmt_execute($stmt_update)) {
    // Log the activity
    $aktivitas = "Admin (ID: $admin_id) mengubah status dokumen (ID: $doc_id) menjadi '$status' untuk pendaftaran (ID: $pendaftaran_id).";
    $query_log = "INSERT INTO log_aktivitas (admin_id, aktivitas) VALUES (?, ?)";
    $stmt_log = mysqli_prepare($koneksi, $query_log);
    mysqli_stmt_bind_param($stmt_log, "is", $admin_id, $aktivitas);
    mysqli_stmt_execute($stmt_log);

    $_SESSION['update_success'] = "Status dokumen berhasil diperbarui.";
} else {
    $_SESSION['update_error'] = "Gagal memperbarui status dokumen.";
}

// Redirect back to the detail page
header('Location: detail_pendaftar.php?id=' . $pendaftaran_id);
exit();
?>