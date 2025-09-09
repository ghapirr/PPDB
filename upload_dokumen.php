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
    // Jika belum ada di tabel pendaftaran, redirect ke dashboard untuk mengisi form utama dulu
    header('Location: dashboard.php');
    exit();
}
$data_pendaftaran = mysqli_fetch_assoc($result_pendaftaran);
$pendaftaran_id = $data_pendaftaran['id'];

// Ambil dokumen yang sudah diupload
$query_docs = "SELECT * FROM dokumen WHERE pendaftaran_id = ?";
$stmt_docs = mysqli_prepare($koneksi, $query_docs);
mysqli_stmt_bind_param($stmt_docs, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt_docs);
$result_docs = mysqli_stmt_get_result($stmt_docs);
$uploaded_docs = [];
while($doc = mysqli_fetch_assoc($result_docs)) {
    $uploaded_docs[$doc['jenis_dokumen']] = $doc;
}

// Ambil jalur_id dari data pendaftaran
$jalur_id = $data_pendaftaran['jalur_id'];

// Ambil daftar dokumen wajib dari database berdasarkan jalur_id
$query_wajib = "SELECT jenis_dokumen FROM jalur_dokumen_wajib WHERE jalur_id = ?";
$stmt_wajib = mysqli_prepare($koneksi, $query_wajib);
mysqli_stmt_bind_param($stmt_wajib, "i", $jalur_id);
mysqli_stmt_execute($stmt_wajib);
$result_wajib = mysqli_stmt_get_result($stmt_wajib);

// Daftar semua kemungkinan dokumen dan labelnya
$all_docs_labels = [
    'foto' => 'Pas Foto (3x4)',
    'rapor' => 'Scan Rapor Terakhir',
    'sktm' => 'Scan SKTM/KIP',
    'prestasi' => 'Sertifikat Prestasi'
];

// Buat array dokumen yang dibutuhkan untuk jalur ini
$required_docs = [];
while ($row = mysqli_fetch_assoc($result_wajib)) {
    $jenis = $row['jenis_dokumen'];
    if (isset($all_docs_labels[$jenis])) {
        $required_docs[$jenis] = $all_docs_labels[$jenis];
    }
}

?>

<h1 class="display-6 fw-bold">Upload Dokumen Persyaratan</h1>
<p class="lead">Silakan unggah dokumen yang diperlukan sesuai dengan jalur pendaftaran Anda.</p>

<?php 
if(isset($_SESSION['upload_error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['upload_error'] . '</div>';
    unset($_SESSION['upload_error']);
}
if(isset($_SESSION['upload_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['upload_success'] . '</div>';
    unset($_SESSION['upload_success']);
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Jenis Dokumen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($required_docs as $type => $label): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($label) ?><br>
                        <small class="text-muted">Tipe file: JPG, PNG, PDF. Maks: 2MB</small>
                    </td>
                    <td>
                        <?php if (isset($uploaded_docs[$type])): 
                            $doc = $uploaded_docs[$type];
                            $status_class = 'bg-secondary';
                            if ($doc['status_verifikasi'] == 'Valid') $status_class = 'bg-success';
                            if ($doc['status_verifikasi'] == 'Tidak Valid') $status_class = 'bg-danger';
                        ?>
                            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($doc['status_verifikasi']) ?></span>
                            <a href="uploads/<?= htmlspecialchars($doc['nama_file']) ?>" target="_blank">(Lihat File)</a>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Diunggah</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="proses_upload.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran_id ?>">
                            <input type="hidden" name="jenis_dokumen" value="<?= $type ?>">
                            <div class="input-group">
                                <input type="file" class="form-control" name="file_dokumen" required>
                                <button class="btn btn-success" type="submit">Upload</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
