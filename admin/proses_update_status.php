<?php
require_once '../config/database.php';

// Security check: Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pendaftaran_id = $_POST['pendaftaran_id'] ?? null;
    $status_pendaftaran = $_POST['status_pendaftaran'] ?? null;
    $catatan_admin = $_POST['catatan_admin'] ?? ''; // Optional
    $admin_id = $_SESSION['admin_id'];

    // Validate
    if (empty($pendaftaran_id) || empty($status_pendaftaran)) {
        // Handle error - maybe redirect back with a message
        header('Location: pendaftar.php?error=invalid_data');
        exit();
    }

    // Update status in pendaftaran table
    // For now, we don't have a catatan_admin column, so we ignore it.
    $query_update = "UPDATE pendaftaran SET status_pendaftaran = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($koneksi, $query_update);
    mysqli_stmt_bind_param($stmt_update, "si", $status_pendaftaran, $pendaftaran_id);
    
    if (mysqli_stmt_execute($stmt_update)) {
        // Log the activity
        $aktivitas = "Admin (ID: $admin_id) mengubah status pendaftaran (ID: $pendaftaran_id) menjadi '$status_pendaftaran'";
        if (!empty($catatan_admin)) {
            $aktivitas .= " dengan catatan: " . $catatan_admin;
        }
        $query_log = "INSERT INTO log_aktivitas (admin_id, aktivitas) VALUES (?, ?)";
        $stmt_log = mysqli_prepare($koneksi, $query_log);
        mysqli_stmt_bind_param($stmt_log, "is", $admin_id, $aktivitas);
        mysqli_stmt_execute($stmt_log);

        // Redirect back to detail page with success message
        $_SESSION['update_success'] = "Status pendaftaran berhasil diperbarui.";
        header('Location: detail_pendaftar.php?id=' . $pendaftaran_id);
        exit();

    } else {
        // Handle failure
        $_SESSION['update_error'] = "Gagal memperbarui status.";
        header('Location: detail_pendaftar.php?id=' . $pendaftaran_id);
        exit();
    }

} else {
    // Not a POST request
    header('Location: pendaftar.php');
    exit();
}
?>