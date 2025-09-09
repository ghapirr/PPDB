<?php
require_once '../config/database.php';

// Security check: Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get all registration IDs
$query_pendaftaran = "SELECT id FROM pendaftaran";
$result_pendaftaran = mysqli_query($koneksi, $query_pendaftaran);

if (!$result_pendaftaran) {
    $_SESSION['ranking_error'] = "Gagal mengambil data pendaftaran: " . mysqli_error($koneksi);
    header('Location: ranking.php');
    exit();
}

// Prepare statements for fetching grades and updating score
$query_nilai = "SELECT AVG(nilai) as rata_rata FROM nilai_pendaftar WHERE pendaftaran_id = ?";
$stmt_nilai = mysqli_prepare($koneksi, $query_nilai);

$query_update = "UPDATE pendaftaran SET skor_akhir = ? WHERE id = ?";
$stmt_update = mysqli_prepare($koneksi, $query_update);

$total_updated = 0;

// Loop through each registration
while ($pendaftar = mysqli_fetch_assoc($result_pendaftaran)) {
    $pendaftaran_id = $pendaftar['id'];
    $skor_akhir = 0; // Default score is 0

    // Fetch and calculate average score
    mysqli_stmt_bind_param($stmt_nilai, "i", $pendaftaran_id);
    mysqli_stmt_execute($stmt_nilai);
    $result_nilai = mysqli_stmt_get_result($stmt_nilai);
    
    if ($row_nilai = mysqli_fetch_assoc($result_nilai)) {
        // If there are grades, use the average. If not, it remains 0.
        if ($row_nilai['rata_rata'] !== null) {
            $skor_akhir = $row_nilai['rata_rata'];
        }
    }

    // Update the final score in the pendaftaran table
    mysqli_stmt_bind_param($stmt_update, "di", $skor_akhir, $pendaftaran_id);
    mysqli_stmt_execute($stmt_update);
    if (mysqli_stmt_affected_rows($stmt_update) > 0) {
        $total_updated++;
    }
}

// Log the activity
$admin_id = $_SESSION['admin_id'];
$aktivitas = "Admin (ID: $admin_id) melakukan perhitungan skor akhir berdasarkan rata-rata nilai rapor. " . $total_updated . " data diperbarui.";
$query_log = "INSERT INTO log_aktivitas (admin_id, aktivitas) VALUES (?, ?)";
$stmt_log = mysqli_prepare($koneksi, $query_log);
mysqli_stmt_bind_param($stmt_log, "is", $admin_id, $aktivitas);
mysqli_stmt_execute($stmt_log);

$_SESSION['ranking_success'] = "Perhitungan skor akhir telah selesai. Peringkat diperbarui berdasarkan rata-rata nilai rapor.";
header('Location: ranking.php');
exit();
?>