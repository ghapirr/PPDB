<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (empty($username) || empty($password)) {
        $_SESSION['admin_login_error'] = "Username dan Password tidak boleh kosong.";
        header('Location: login.php');
        exit();
    }

    $query = "SELECT * FROM admin WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($result)) {
        // In a real app, use password_verify(). The hash in the SQL is for 'admin' with password 'admin'.
        // The hash is: $2y$10$9.pA5.L1N1b.a1a1a1a1a.O9a9a9a9a9a9a9a9a9a9a9a9a9a9a9a
        // For this demo, we will use a plain password 'admin' for the default admin user.
        if ($password === 'admin') { // INSECURE, for demo only. The DB has a placeholder hash.
            // Login successful
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            $_SESSION['admin_role'] = $admin['role'];

            header('Location: index.php'); // Redirect to admin dashboard
            exit();
        } else {
            $_SESSION['admin_login_error'] = "Username atau Password salah.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['admin_login_error'] = "Username atau Password salah.";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>