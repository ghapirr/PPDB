<?php
require_once '../config/database.php';

// Security check: Ensure superadmin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'superadmin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['admin_id'];
    $log_details = [];

    // Update general settings
    if (isset($_POST['pengaturan']) && is_array($_POST['pengaturan'])) {
        $pengaturan_data = $_POST['pengaturan'];
        $query_update = "UPDATE pengaturan SET setting_value = ? WHERE setting_name = ?";
        $stmt_update = mysqli_prepare($koneksi, $query_update);

        foreach ($pengaturan_data as $name => $value) {
            mysqli_stmt_bind_param($stmt_update, "ss", $value, $name);
            mysqli_stmt_execute($stmt_update);
            $log_details[] = "$name = '$value'";
        }
    }

    // Update Jadwal
    if (isset($_POST['jadwal']) && is_array($_POST['jadwal'])) {
        $jadwal_data = $_POST['jadwal'];
        $query_jadwal = "UPDATE jadwal SET tahapan = ?, tgl_mulai = ?, tgl_selesai = ? WHERE id = ?";
        $stmt_jadwal = mysqli_prepare($koneksi, $query_jadwal);
        foreach ($jadwal_data as $id => $data) {
            mysqli_stmt_bind_param($stmt_jadwal, "sssi", $data['tahapan'], $data['tgl_mulai'], $data['tgl_selesai'], $id);
            mysqli_stmt_execute($stmt_jadwal);
        }
        $log_details[] = "Jadwal diperbarui";
    }

    // Update Kuota Jalur
    if (isset($_POST['jalur']) && is_array($_POST['jalur'])) {
        $jalur_data = $_POST['jalur'];
        $query_jalur = "UPDATE jalur SET nama_jalur = ?, kuota = ? WHERE id = ?";
        $stmt_jalur = mysqli_prepare($koneksi, $query_jalur);
        foreach ($jalur_data as $id => $data) {
            mysqli_stmt_bind_param($stmt_jalur, "sii", $data['nama_jalur'], $data['kuota'], $id);
            mysqli_stmt_execute($stmt_jalur);
        }
        $log_details[] = "Kuota diperbarui";
    }

    // Log the activity
    if (!empty($log_details)) {
        $aktivitas = "Admin (ID: $admin_id) memperbarui pengaturan: " . implode(", ", $log_details);
        $query_log = "INSERT INTO log_aktivitas (admin_id, aktivitas) VALUES (?, ?)";
        $stmt_log = mysqli_prepare($koneksi, $query_log);
        mysqli_stmt_bind_param($stmt_log, "is", $admin_id, $aktivitas);
        mysqli_stmt_execute($stmt_log);
    }

    $_SESSION['setting_update_success'] = "Pengaturan berhasil diperbarui.";
    header('Location: pengaturan.php');
    exit();

} else {
    header('Location: pengaturan.php');
    exit();
}
?>