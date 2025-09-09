<?php 
require_once 'includes/header.php'; 

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

$pendaftar_id = $_SESSION['pendaftar_id'];

// Ambil data pendaftaran (termasuk ID pendaftaran)
$query_pendaftaran = "SELECT * FROM pendaftaran WHERE pendaftar_id = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query_pendaftaran);
mysqli_stmt_bind_param($stmt, "i", $pendaftar_id);
mysqli_stmt_execute($stmt);
$result_pendaftaran = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result_pendaftaran) == 0) {
    // Jika belum mendaftar, redirect ke dashboard
    $_SESSION['flash_error'] = "Anda harus menyelesaikan pendaftaran utama terlebih dahulu.";
    header('Location: dashboard.php');
    exit();
}
$data_pendaftaran = mysqli_fetch_assoc($result_pendaftaran);
$pendaftaran_id = $data_pendaftaran['id'];

// Daftar mata pelajaran
$mapel_list = [
    'matematika' => 'Matematika',
    'bahasa_indonesia' => 'Bahasa Indonesia',
    'bahasa_inggris' => 'Bahasa Inggris',
    'ipa' => 'Ilmu Pengetahuan Alam (IPA)',
    'ips' => 'Ilmu Pengetahuan Sosial (IPS)'
];

// Ambil nilai yang sudah ada
$query_nilai = "SELECT mapel, nilai FROM nilai_pendaftar WHERE pendaftaran_id = ?";
$stmt_nilai = mysqli_prepare($koneksi, $query_nilai);
mysqli_stmt_bind_param($stmt_nilai, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt_nilai);
$result_nilai = mysqli_stmt_get_result($stmt_nilai);
$existing_nilai = [];
while($row = mysqli_fetch_assoc($result_nilai)) {
    $existing_nilai[$row['mapel']] = $row['nilai'];
}

?>

<h1 class="display-6 fw-bold">Formulir Input Nilai Rapor</h1>
<p class="lead">Silakan masukkan nilai rapor Anda untuk 5 semester terakhir (rata-rata per mata pelajaran).</p>

<?php 
if(isset($_SESSION['nilai_error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['nilai_error'] . '</div>';
    unset($_SESSION['nilai_error']);
}
if(isset($_SESSION['nilai_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['nilai_success'] . '</div>';
    unset($_SESSION['nilai_success']);
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="proses_nilai.php" method="post">
            <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran_id ?>">
            
            <?php foreach ($mapel_list as $mapel_key => $mapel_label): ?>
            <div class="mb-3 row">
                <label for="<?= $mapel_key ?>" class="col-sm-4 col-form-label"><?= htmlspecialchars($mapel_label) ?></label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="<?= $mapel_key ?>" name="nilai[<?= $mapel_key ?>]" 
                           min="0" max="100" step="0.01" 
                           value="<?= htmlspecialchars($existing_nilai[$mapel_key] ?? '') ?>" 
                           required>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard</a>
                <button type="submit" class="btn btn-primary">Simpan Nilai <i class="bi bi-save"></i></button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
