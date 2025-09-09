<?php
require_once 'config/database.php';

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pendaftaran_id = $_POST['pendaftaran_id'] ?? null;
    $jenis_dokumen = $_POST['jenis_dokumen'] ?? null;

    // Validasi input dasar
    if (empty($pendaftaran_id) || empty($jenis_dokumen) || !isset($_FILES['file_dokumen'])) {
        $_SESSION['upload_error'] = "Permintaan tidak valid.";
        header('Location: upload_dokumen.php');
        exit();
    }

    // Cek file upload error
    if ($_FILES['file_dokumen']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['upload_error'] = "Gagal mengunggah file. Error code: " . $_FILES['file_dokumen']['error'];
        header('Location: upload_dokumen.php');
        exit();
    }

    $file = $_FILES['file_dokumen'];

    // Validasi ukuran file (maks 2MB)
    $max_size = 2 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        $_SESSION['upload_error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
        header('Location: upload_dokumen.php');
        exit();
    }

    // Validasi tipe file
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $file['tmp_name']);
    finfo_close($file_info);

    if (!in_array($mime_type, $allowed_types)) {
        $_SESSION['upload_error'] = "Tipe file tidak diizinkan. Hanya JPG, PNG, dan PDF.";
        header('Location: upload_dokumen.php');
        exit();
    }

    // Buat nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = $pendaftaran_id . '_' . $jenis_dokumen . '_' . time() . '.' . $extension;
    $upload_path = 'uploads/' . $new_filename;

    // Pindahkan file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Simpan ke database
        $query_check = "SELECT id, path_file FROM dokumen WHERE pendaftaran_id = ? AND jenis_dokumen = ?";
        $stmt_check = mysqli_prepare($koneksi, $query_check);
        mysqli_stmt_bind_param($stmt_check, "is", $pendaftaran_id, $jenis_dokumen);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if ($existing_doc = mysqli_fetch_assoc($result_check)) {
            // Update record jika sudah ada
            $old_file_path = $existing_doc['path_file'];
            $query_update = "UPDATE dokumen SET nama_file = ?, path_file = ?, status_verifikasi = 'Belum Dicek', catatan_verifikator = NULL WHERE id = ?";
            $stmt_update = mysqli_prepare($koneksi, $query_update);
            mysqli_stmt_bind_param($stmt_update, "ssi", $new_filename, $upload_path, $existing_doc['id']);
            mysqli_stmt_execute($stmt_update);
            // Hapus file lama
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        } else {
            // Insert record baru
            $query_insert = "INSERT INTO dokumen (pendaftaran_id, jenis_dokumen, nama_file, path_file) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "isss", $pendaftaran_id, $jenis_dokumen, $new_filename, $upload_path);
            mysqli_stmt_execute($stmt_insert);
        }

        $_SESSION['upload_success'] = "File berhasil diunggah.";
    } else {
        $_SESSION['upload_error'] = "Gagal memindahkan file yang diunggah.";
    }

    header('Location: upload_dokumen.php');
    exit();

} else {
    header('Location: upload_dokumen.php');
    exit();
}
?>