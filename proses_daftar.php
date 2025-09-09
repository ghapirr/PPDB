<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nik = $_POST['nik'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? null;
    $nama_lengkap = $_POST['nama_lengkap'] ?? null;
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
    $sekolah_asal = $_POST['sekolah_asal'] ?? null;
    $tempat_lahir = $_POST['tempat_lahir'] ?? null;
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
    $no_hp = $_POST['no_hp'] ?? null;
    $alamat = $_POST['alamat'] ?? null;

    // Validasi dasar
    if (empty($nik) || empty($email) || empty($password) || empty($nama_lengkap)) {
        $_SESSION['register_error'] = "Semua field yang bertanda bintang wajib diisi.";
        header('Location: daftar.php');
        exit();
    }

    if ($password !== $konfirmasi_password) {
        $_SESSION['register_error'] = "Password dan Konfirmasi Password tidak cocok.";
        header('Location: daftar.php');
        exit();
    }

    // Cek duplikasi NIK atau Email
    $query_check = "SELECT nik, email FROM pendaftar WHERE nik = ? OR email = ? LIMIT 1";
    $stmt_check = mysqli_prepare($koneksi, $query_check);
    mysqli_stmt_bind_param($stmt_check, "ss", $nik, $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        $existing_user = mysqli_fetch_assoc($result_check);
        if ($existing_user['nik'] == $nik) {
            $_SESSION['register_error'] = "NIK sudah terdaftar. Silakan gunakan NIK lain atau login.";
        } else {
            $_SESSION['register_error'] = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
        }
        header('Location: daftar.php');
        exit();
    }

    // In a real application, HASH THE PASSWORD!
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // For this demo, we'll store it as plain text to match the login process.
    $hashed_password = $password; // INSECURE, FOR DEMO ONLY

    // Insert data ke database
    $query_insert = "INSERT INTO pendaftar (nik, email, password, nama_lengkap, jenis_kelamin, sekolah_asal, tempat_lahir, tanggal_lahir, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssssssss", $nik, $email, $hashed_password, $nama_lengkap, $jenis_kelamin, $sekolah_asal, $tempat_lahir, $tanggal_lahir, $no_hp, $alamat);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        // Registrasi berhasil
        $_SESSION['register_success'] = "Pendaftaran akun berhasil! Silakan login untuk melanjutkan.";
        header('Location: login.php');
        exit();
    } else {
        // Gagal insert
        $_SESSION['register_error'] = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        header('Location: daftar.php');
        exit();
    }

} else {
    // Bukan POST request
    header('Location: daftar.php');
    exit();
}
?>