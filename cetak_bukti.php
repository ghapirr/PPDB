<?php 
require_once 'config/database.php'; 

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

$pendaftar_id = $_SESSION['pendaftar_id'];

// Ambil semua data pendaftaran
$query = "SELECT 
            p.*, 
            u.nik, u.nama_lengkap, u.email, u.jenis_kelamin, u.tempat_lahir, u.tanggal_lahir, u.sekolah_asal,
            j.nama_jalur
          FROM pendaftaran p
          JOIN pendaftar u ON p.pendaftar_id = u.id
          JOIN jalur j ON p.jalur_id = j.id
          WHERE p.pendaftar_id = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $pendaftar_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Jika belum mendaftar, redirect ke dashboard
    $_SESSION['form_error'] = "Anda harus menyelesaikan pendaftaran terlebih dahulu.";
    header('Location: dashboard.php');
    exit();
}
$data = mysqli_fetch_assoc($result);

// Ambil foto pendaftar jika ada
$query_foto = "SELECT nama_file FROM dokumen WHERE pendaftaran_id = ? AND jenis_dokumen = 'foto' LIMIT 1";
$stmt_foto = mysqli_prepare($koneksi, $query_foto);
mysqli_stmt_bind_param($stmt_foto, "i", $data['id']);
mysqli_stmt_execute($stmt_foto);
$result_foto = mysqli_stmt_get_result($stmt_foto);
$foto = mysqli_fetch_assoc($result_foto);

$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data['no_pendaftaran']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Bukti Pendaftaran - <?= htmlspecialchars($data['no_pendaftaran']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .card {
                border: 1px solid #dee2e6 !important;
            }
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header text-center">
                        <h3>BUKTI PENDAFTARAN PPDB ONLINE</h3>
                        <h5><?= htmlspecialchars($pengaturan['nama_sekolah']) ?></h5>
                        <h6>Tahun Ajaran <?= htmlspecialchars($pengaturan['tahun_ajaran']) ?></h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-sm table-borderless">
                                    <tr><td style="width: 40%;"><strong>No. Pendaftaran</strong></td><td>: <?= htmlspecialchars($data['no_pendaftaran']) ?></td></tr>
                                    <tr><td><strong>Nama Lengkap</strong></td><td>: <?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                    <tr><td><strong>NIK</strong></td><td>: <?= htmlspecialchars($data['nik']) ?></td></tr>
                                    <tr><td><strong>Sekolah Asal</strong></td><td>: <?= htmlspecialchars($data['sekolah_asal']) ?></td></tr>
                                    <tr><td><strong>Jalur Pilihan</strong></td><td>: <?= htmlspecialchars($data['nama_jalur']) ?></td></tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                <?php if ($foto): ?>
                                    <img src="uploads/<?= htmlspecialchars($foto['nama_file']) ?>" class="img-thumbnail" alt="Pas Foto">
                                <?php else: ?>
                                    <div class="border bg-light d-flex justify-content-center align-items-center" style="width: 150px; height: 200px;">Foto Belum Diunggah</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-4">
                            <div class="col-7">
                                <p><strong>Perhatian:</strong></p>
                                <ol>
                                    <li>Simpan bukti pendaftaran ini dengan baik.</li>
                                    <li>Semua data yang diisikan adalah benar dan dapat dipertanggungjawabkan.</li>
                                    <li>Pantau terus jadwal dan pengumuman di website.</li>
                                </ol>
                            </div>
                            <div class="col-5 text-center">
                                <img src="<?= $qr_code_url ?>" alt="QR Code Pendaftaran">
                                <p class="mt-2 mb-0">Banjarnegara, <?= date('d F Y') ?></p>
                                <p>Pendaftar,</p>
                                <br><br><br>
                                <p>( <?= htmlspecialchars($data['nama_lengkap']) ?> )</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3 no-print">
                    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
