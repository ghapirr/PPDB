<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    if (empty($nik) || empty($password)) {
        $_SESSION['login_error'] = "NIK dan Password tidak boleh kosong.";
        header('Location: login.php');
        exit();
    }

    $query = "SELECT id, nama_lengkap, password FROM pendaftar WHERE nik = ? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // For now, we use plain text comparison. 
        // In a real application, you should use password_hash() and password_verify().
        // Example: if (password_verify($password, $user['password'])) {
        if ($password === $user['password']) { // THIS IS INSECURE, FOR DEMO ONLY
            // Login successful
            $_SESSION['pendaftar_id'] = $user['id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            // Incorrect password
            $_SESSION['login_error'] = "NIK atau Password salah.";
            header('Location: login.php');
            exit();
        }
    } else {
        // NIK not found
        $_SESSION['login_error'] = "NIK atau Password salah.";
        header('Location: login.php');
        exit();
    }

} else {
    // Not a POST request
    header('Location: login.php');
    exit();
}
?>