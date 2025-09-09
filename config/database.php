<?php
$host = 'localhost';
$user = 'root'; // Default XAMPP user
$pass = '';     // Default XAMPP password
$db   = 'ppdb_online';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fetch settings from database
$pengaturan = [];
$query_pengaturan = mysqli_query($koneksi, "SELECT * FROM pengaturan");
while($row = mysqli_fetch_assoc($query_pengaturan)) {
    $pengaturan[$row['setting_name']] = $row['setting_value'];
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>