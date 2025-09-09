<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online <?= $pengaturan['nama_sekolah'] ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --bs-primary-rgb: 34, 139, 34; /* Forest Green - Warna khas madrasah */
            --bs-secondary-rgb: 108, 117, 125;
        }
        .navbar-brand img {
            max-height: 40px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <!-- Optional: Logo Kemenag -->
            <!-- <img src="assets/img/logo_kemenag.png" alt="Logo Kemenag" class="me-2"> -->
            <strong>PPDB Online <?= $pengaturan['tahun_ajaran'] ?? '' ?></strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="alur.php">Alur Pendaftaran</a></li>
                <li class="nav-item"><a class="nav-link" href="jadwal.php">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="kuota.php">Kuota</a></li>
                <li class="nav-item"><a class="nav-link" href="pengumuman.php">Pengumuman</a></li>
                <li class="nav-item"><a class="nav-link" href="bantuan.php">Bantuan</a></li>
            </ul>
            <ul class="navbar-nav ms-lg-3">
                <?php if (isset($_SESSION['pendaftar_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> Akun Saya
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a href="login.php" class="btn btn-outline-success">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="daftar.php" class="btn btn-success">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5">
