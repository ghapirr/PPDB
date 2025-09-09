<?php 
require_once 'includes/header.php'; 

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

$pendaftar_id = $_SESSION['pendaftar_id'];

// Ambil data pendaftaran user
$query_pendaftaran = "SELECT p.*, j.nama_jalur FROM pendaftaran p JOIN jalur j ON p.jalur_id = j.id WHERE p.pendaftar_id = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query_pendaftaran);
mysqli_stmt_bind_param($stmt, "i", $pendaftar_id);
mysqli_stmt_execute($stmt);
$result_pendaftaran = mysqli_stmt_get_result($stmt);
$data_pendaftaran = mysqli_fetch_assoc($result_pendaftaran);

// Cek kelengkapan dokumen jika pendaftaran sudah ada
$semua_dokumen_lengkap = false;
$dokumen_kurang_labels = [];
if ($data_pendaftaran) {
    $pendaftaran_id = $data_pendaftaran['id'];
    $jalur_id = $data_pendaftaran['jalur_id'];

    // 1. Ambil dokumen yang diwajibkan untuk jalur ini
    $query_wajib = "SELECT jenis_dokumen FROM jalur_dokumen_wajib WHERE jalur_id = ?";
    $stmt_wajib = mysqli_prepare($koneksi, $query_wajib);
    mysqli_stmt_bind_param($stmt_wajib, "i", $jalur_id);
    mysqli_stmt_execute($stmt_wajib);
    $result_wajib = mysqli_stmt_get_result($stmt_wajib);
    $dokumen_wajib = [];
    while($row = mysqli_fetch_assoc($result_wajib)) {
        $dokumen_wajib[] = $row['jenis_dokumen'];
    }

    // 2. Ambil dokumen yang sudah diunggah pendaftar
    $query_uploaded = "SELECT jenis_dokumen FROM dokumen WHERE pendaftaran_id = ?";
    $stmt_uploaded = mysqli_prepare($koneksi, $query_uploaded);
    mysqli_stmt_bind_param($stmt_uploaded, "i", $pendaftaran_id);
    mysqli_stmt_execute($stmt_uploaded);
    $result_uploaded = mysqli_stmt_get_result($stmt_uploaded);
    $dokumen_uploaded = [];
    while($row = mysqli_fetch_assoc($result_uploaded)) {
        $dokumen_uploaded[] = $row['jenis_dokumen'];
    }

    // 3. Cek apakah semua dokumen wajib sudah diunggah
    $dokumen_kurang = array_diff($dokumen_wajib, $dokumen_uploaded);

    if (empty($dokumen_kurang)) {
        $semua_dokumen_lengkap = true;
    } else {
        // Siapkan label untuk dokumen yang kurang
        $all_docs_labels = [
            'foto' => 'Pas Foto (3x4)',
            'rapor' => 'Scan Rapor Terakhir',
            'sktm' => 'Scan SKTM/KIP',
            'prestasi' => 'Sertifikat Prestasi'
        ];
        foreach ($dokumen_kurang as $jenis) {
            if(isset($all_docs_labels[$jenis])) {
                $dokumen_kurang_labels[] = $all_docs_labels[$jenis];
            }
        }
    }
}

// Cek status pengisian nilai
$nilai_sudah_diisi = false;
if ($data_pendaftaran) {
    $query_nilai_check = "SELECT COUNT(id) as total FROM nilai_pendaftar WHERE pendaftaran_id = ?";
    $stmt_nilai_check = mysqli_prepare($koneksi, $query_nilai_check);
    mysqli_stmt_bind_param($stmt_nilai_check, "i", $pendaftaran_id);
    mysqli_stmt_execute($stmt_nilai_check);
    $result_nilai_check = mysqli_stmt_get_result($stmt_nilai_check);
    $nilai_count = mysqli_fetch_assoc($result_nilai_check)['total'];
    if ($nilai_count > 0) {
        $nilai_sudah_diisi = true;
    }
}

?>

<h1 class="display-6 fw-bold">Dashboard Pendaftar</h1>
<p class="lead">Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!</p>

<div class="row">
    <div class="col-md-8">
        <?php if ($data_pendaftaran): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Status Pendaftaran Anda</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>No. Pendaftaran:</strong><br> <?= htmlspecialchars($data_pendaftaran['no_pendaftaran']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jalur Pilihan:</strong><br> <?= htmlspecialchars($data_pendaftaran['nama_jalur']) ?></p>
                        </div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Langkah-Langkah Pendaftaran</h5>
                    <ul class="list-group">
                        <!-- Step 1: Formulir Utama -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <strong>Langkah 1:</strong> Pengisian Formulir Utama
                            </div>
                            <span class="badge bg-success rounded-pill">Selesai</span>
                        </li>

                        <!-- Step 2: Input Nilai -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($nilai_sudah_diisi): ?>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?php else: ?>
                                    <i class="bi bi-pencil-square text-warning me-2"></i>
                                <?php endif; ?>
                                <strong>Langkah 2:</strong> Pengisian Nilai Rapor
                            </div>
                            <a href="form_nilai.php" class="btn btn-sm btn-outline-primary">Isi/Ubah Nilai</a>
                        </li>

                        <!-- Step 3: Upload Dokumen -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($semua_dokumen_lengkap): ?>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <?php else: ?>
                                    <i class="bi bi-cloud-upload text-warning me-2"></i>
                                <?php endif; ?>
                                <strong>Langkah 3:</strong> Unggah Dokumen Wajib
                            </div>
                            <a href="upload_dokumen.php" class="btn btn-sm btn-outline-primary">Kelola Dokumen</a>
                        </li>

                        <!-- Step 4: Finalisasi -->
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if ($semua_dokumen_lengkap && $nilai_sudah_diisi): ?>
                                        <i class="bi bi-printer-fill text-primary me-2"></i>
                                    <?php else: ?>
                                        <i class="bi bi-lock-fill text-muted me-2"></i>
                                    <?php endif; ?>
                                    <strong>Langkah 4:</strong> Finalisasi & Cetak Bukti
                                </div>
                                <?php
                                $finalisasi_lengkap = $semua_dokumen_lengkap && $nilai_sudah_diisi;
                                $disabled_attr = $finalisasi_lengkap ? '' : 'disabled';
                                $tooltip_wrapper_start = !$finalisasi_lengkap ? '<span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Lengkapi pengisian nilai dan unggah dokumen untuk mencetak.">' : '';
                                $tooltip_wrapper_end = !$finalisasi_lengkap ? '</span>' : '';

                                echo $tooltip_wrapper_start;
                                ?>
                                <a href="cetak_bukti.php" target="_blank" class="btn btn-primary <?= $finalisasi_lengkap ? '' : 'disabled' ?>" <?= $disabled_attr ?>>
                                    <i class="bi bi-printer"></i> Cetak Bukti Pendaftaran
                                a>
                                <?php echo $tooltip_wrapper_end; ?>
                            </div>
                            <?php if (!$finalisasi_lengkap): ?>
                            <div class="form-text mt-2">
                                Tombol cetak akan aktif setelah Anda menyelesaikan Langkah 2 dan Langkah 3.
                            </div>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Anda Belum Menyelesaikan Pendaftaran</h5>
                </div>
                <div class="card-body text-center">
                    <p>Anda telah berhasil membuat akun. Langkah selanjutnya adalah mengisi formulir pendaftaran utama untuk mendapatkan nomor pendaftaran.</p>
                    <a href="form_pendaftaran.php" class="btn btn-success btn-lg">Mulai Isi Formulir Pendaftaran <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <?php
                $query_pendaftar = mysqli_query($koneksi, "SELECT * FROM pendaftar WHERE id = $pendaftar_id");
                $pendaftar = mysqli_fetch_assoc($query_pendaftar);
                ?>
                <p><strong>NIK:</strong><br> <?= htmlspecialchars($pendaftar['nik']) ?></p>
                <p><strong>Nama:</strong><br> <?= htmlspecialchars($pendaftar['nama_lengkap']) ?></p>
                <p><strong>Email:</strong><br> <?= htmlspecialchars($pendaftar['email']) ?></p>
                <a href="#" class="btn btn-outline-secondary btn-sm">Edit Akun</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
