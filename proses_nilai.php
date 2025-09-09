<?php
require_once 'config/database.php';

// Pastikan hanya bisa diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    $_SESSION['nilai_error'] = "Sesi Anda telah berakhir, silakan login kembali.";
    header('Location: login.php');
    exit();
}

// Validasi input
if (empty($_POST['pendaftaran_id']) || empty($_POST['nilai']) || !is_array($_POST['nilai'])) {
    $_SESSION['nilai_error'] = "Terjadi kesalahan, data tidak lengkap.";
    header('Location: form_nilai.php');
    exit();
}

$pendaftaran_id = intval($_POST['pendaftaran_id']);
$nilai_mapel = $_POST['nilai'];

// Pastikan pendaftaran ini milik user yang sedang login
$pendaftar_id_session = $_SESSION['pendaftar_id'];
$query_check = "SELECT pendaftar_id FROM pendaftaran WHERE id = ?";
$stmt_check = mysqli_prepare($koneksi, $query_check);
mysqli_stmt_bind_param($stmt_check, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$owner = mysqli_fetch_assoc($result_check);

if (!$owner || $owner['pendaftar_id'] != $pendaftar_id_session) {
    $_SESSION['nilai_error'] = "Anda tidak memiliki izin untuk mengubah data ini.";
    header('Location: dashboard.php');
    exit();
}

$error = false;

// Query untuk insert atau update nilai
$query = "INSERT INTO nilai_pendaftar (pendaftaran_id, mapel, nilai) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)";
$stmt = mysqli_prepare($koneksi, $query);

foreach ($nilai_mapel as $mapel => $nilai) {
    // Sanitasi dan validasi sederhana
    $mapel_sanitized = htmlspecialchars($mapel);
    $nilai_validated = filter_var($nilai, FILTER_VALIDATE_FLOAT);

    if ($nilai_validated === false || $nilai_validated < 0 || $nilai_validated > 100) {
        $_SESSION['nilai_error'] = "Nilai untuk " . $mapel_sanitized . " tidak valid. Harap masukkan angka antara 0 dan 100.";
        $error = true;
        break; // Hentikan jika ada satu error
    }

    mysqli_stmt_bind_param($stmt, "isd", $pendaftaran_id, $mapel_sanitized, $nilai_validated);
    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['nilai_error'] = "Terjadi kesalahan saat menyimpan data: " . mysqli_error($koneksi);
        $error = true;
        break;
    }
}

if (!$error) {
    $_SESSION['nilai_success'] = "Nilai berhasil disimpan.";
} 

header('Location: form_nilai.php');
exit();

?>