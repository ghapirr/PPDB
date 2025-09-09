<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php'; 

// Get ID and validate
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Pendaftaran tidak valid.</div>";
    require_once 'includes/footer.php';
    exit();
}
$pendaftaran_id = intval($_GET['id']);

// Fetch all data for this registration
$query = "SELECT 
            p.*, 
            u.nik, u.nama_lengkap, u.email, u.jenis_kelamin, u.tempat_lahir, u.tanggal_lahir, u.alamat, u.no_hp, u.sekolah_asal,
            j.nama_jalur
          FROM pendaftaran p
          JOIN pendaftar u ON p.pendaftar_id = u.id
          JOIN jalur j ON p.jalur_id = j.id
          WHERE p.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Data pendaftaran tidak ditemukan.</div>";
    require_once 'includes/footer.php';
    exit();
}
$data = mysqli_fetch_assoc($result);

// Fetch documents
$query_docs = "SELECT * FROM dokumen WHERE pendaftaran_id = ?";
$stmt_docs = mysqli_prepare($koneksi, $query_docs);
mysqli_stmt_bind_param($stmt_docs, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt_docs);
$result_docs = mysqli_stmt_get_result($stmt_docs);

// Fetch grades
$query_nilai = "SELECT mapel, nilai FROM nilai_pendaftar WHERE pendaftaran_id = ? ORDER BY mapel";
$stmt_nilai = mysqli_prepare($koneksi, $query_nilai);
mysqli_stmt_bind_param($stmt_nilai, "i", $pendaftaran_id);
mysqli_stmt_execute($stmt_nilai);
$result_nilai = mysqli_stmt_get_result($stmt_nilai);

?>

<h1 class="mt-4">Detail Pendaftar</h1>
<p>Verifikasi data dan dokumen untuk pendaftar <strong><?= htmlspecialchars($data['nama_lengkap']) ?></strong>.</p>

<div class="card shadow-sm">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="data-tab" data-bs-toggle="tab" href="#data-pendaftar">Data Pendaftar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dokumen-tab" data-bs-toggle="tab" href="#dokumen">Dokumen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nilai-tab" data-bs-toggle="tab" href="#nilai-rapor">Nilai Rapor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="status-tab" data-bs-toggle="tab" href="#status">Status & Aksi</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- Data Pendaftar Tab -->
            <div class="tab-pane fade show active" id="data-pendaftar">
                <h4>Data Pendaftaran</h4>
                <table class="table table-sm table-bordered">
                    <tr><th>No. Pendaftaran</th><td><?= htmlspecialchars($data['no_pendaftaran']) ?></td></tr>
                    <tr><th>Jalur</th><td><?= htmlspecialchars($data['nama_jalur']) ?></td></tr>
                    <tr><th>Tanggal Daftar</th><td><?= date('d M Y H:i', strtotime($data['tgl_daftar'])) ?></td></tr>
                </table>
                <h4 class="mt-4">Data Pribadi</h4>
                <table class="table table-sm table-bordered">
                    <tr><th style="width: 30%;">Nama Lengkap</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                    <tr><th>NIK</th><td><?= htmlspecialchars($data['nik']) ?></td></tr>
                    <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?= ($data['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                    <tr><th>Tempat, Tanggal Lahir</th><td><?= htmlspecialchars($data['tempat_lahir']) ?>, <?= date('d F Y', strtotime($data['tanggal_lahir'])) ?></td></tr>
                    <tr><th>Sekolah Asal</th><td><?= htmlspecialchars($data['sekolah_asal']) ?></td></tr>
                    <tr><th>No. HP</th><td><?= htmlspecialchars($data['no_hp']) ?></td></tr>
                    <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($data['alamat'])) ?></td></tr>
                </table>
            </div>

            <!-- Dokumen Tab -->
            <div class="tab-pane fade" id="dokumen">
                <h4>Dokumen Persyaratan</h4>
                <p>Periksa kelengkapan dan validitas dokumen yang diunggah.</p>
                <table class="table table-bordered">
                    <thead><tr><th>Jenis Dokumen</th><th>File</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($doc = mysqli_fetch_assoc($result_docs)): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['jenis_dokumen']) ?></td>
                            <td><a href="../uploads/<?= htmlspecialchars($doc['nama_file']) ?>" target="_blank"><?= htmlspecialchars($doc['nama_file']) ?></a></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($doc['status_verifikasi']) ?></span></td>
                            <td>
                                <a href="proses_verifikasi_dokumen.php?doc_id=<?= $doc['id'] ?>&status=Valid&pendaftaran_id=<?= $pendaftaran_id ?>" class="btn btn-sm btn-success">Set Valid</a>
                                <a href="proses_verifikasi_dokumen.php?doc_id=<?= $doc['id'] ?>&status=Tidak Valid&pendaftaran_id=<?= $pendaftaran_id ?>" class="btn btn-sm btn-danger">Set Tidak Valid</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($result_docs) == 0): ?>
                        <tr><td colspan="4" class="text-center">Belum ada dokumen yang diunggah.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Nilai Rapor Tab -->
            <div class="tab-pane fade" id="nilai-rapor">
                <h4>Nilai Rapor</h4>
                <p>Rincian nilai rata-rata per mata pelajaran yang telah diinput oleh pendaftar.</p>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70%;">Mata Pelajaran</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_nilai) > 0):
                            mysqli_data_seek($result_nilai, 0); // Reset pointer
                            while($nilai = mysqli_fetch_assoc($result_nilai)):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $nilai['mapel']))) ?></td>
                                <td><strong><?= htmlspecialchars(number_format($nilai['nilai'], 2)) ?></strong></td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr><td colspan="2" class="text-center">Pendaftar belum menginput nilai.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Status & Aksi Tab -->
            <div class="tab-pane fade" id="status">
                <h4>Ubah Status Pendaftaran</h4>
                <form action="proses_update_status.php" method="POST">
                    <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran_id ?>">
                    <div class="mb-3">
                        <label for="status_pendaftaran" class="form-label">Status Baru</label>
                        <select name="status_pendaftaran" id="status_pendaftaran" class="form-select">
                            <?php
                            $status_options = ['Proses', 'Diverifikasi', 'Lulus', 'Tidak Lulus', 'Daftar Ulang'];
                            foreach ($status_options as $opt) {
                                $selected = ($data['status_pendaftaran'] == $opt) ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan_admin" class="form-label">Catatan Admin (Opsional)</label>
                        <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
