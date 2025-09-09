<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php'; 

// Hanya superadmin yang bisa mengakses
if ($_SESSION['admin_role'] !== 'superadmin') {
    echo "<div class='alert alert-danger'>Anda tidak memiliki hak akses untuk halaman ini.</div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<h1 class="mt-4">Manajemen Pengumuman</h1>
<p>Gunakan halaman ini untuk mempublikasikan hasil seleksi akhir kepada semua pendaftar.</p>

<?php 
if(isset($_SESSION['pengumuman_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['pengumuman_success'] . '</div>';
    unset($_SESSION['pengumuman_success']);
}
if(isset($_SESSION['pengumuman_error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['pengumuman_error'] . '</div>';
    unset($_SESSION['pengumuman_error']);
}
?>

<div class="card shadow-sm text-center">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0">Publikasikan Hasil Seleksi</h4>
    </div>
    <div class="card-body">
        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
        <h5 class="card-title mt-3">PERHATIAN!</h5>
        <p class="card-text">
            Tindakan ini akan mengubah status kelulusan semua pendaftar berdasarkan peringkat terakhir di halaman Ranking & Seleksi.
            <br>
            Pastikan Anda sudah melakukan proses perangkingan dengan benar. <strong>Tindakan ini tidak dapat diurungkan.</strong>
        </p>
        <a href="proses_pengumuman.php" class="btn btn-danger btn-lg" onclick="return confirm('Apakah Anda benar-benar yakin ingin mengumumkan hasil seleksi sekarang? Tindakan ini tidak dapat dibatalkan.')">
            <i class="bi bi-megaphone-fill"></i> Umumkan Hasil Seleksi Sekarang
        </a>
    </div>
    <div class="card-footer text-muted">
        Hasil akan dapat dilihat oleh pendaftar di halaman Pengumuman setelah tombol ini ditekan.
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
