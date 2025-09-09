<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php'; 

// Hanya superadmin yang bisa mengakses
if ($_SESSION['admin_role'] !== 'superadmin') {
    echo "<div class='alert alert-danger'>Anda tidak memiliki hak akses untuk halaman ini.</div>";
    require_once 'includes/footer.php';
    exit();
}

// Ambil semua pengaturan
$query_pengaturan = mysqli_query($koneksi, "SELECT * FROM pengaturan");
$pengaturan = [];
while($row = mysqli_fetch_assoc($query_pengaturan)) {
    $pengaturan[$row['setting_name']] = $row['setting_value'];
}

?>

<h1 class="mt-4">Pengaturan Aplikasi</h1>
<p>Kelola pengaturan umum, jadwal, dan kuota PPDB.</p>

<?php 
if(isset($_SESSION['setting_update_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['setting_update_success'] . '</div>';
    unset($_SESSION['setting_update_success']);
}
?>

<form action="proses_update_pengaturan.php" method="POST">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            Pengaturan Umum & Kontak
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                    <input type="text" class="form-control" id="nama_sekolah" name="pengaturan[nama_sekolah]" value="<?= htmlspecialchars($pengaturan['nama_sekolah']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun_ajaran" name="pengaturan[tahun_ajaran]" value="<?= htmlspecialchars($pengaturan['tahun_ajaran']) ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                    <textarea class="form-control" id="alamat_sekolah" name="pengaturan[alamat_sekolah]"><?= htmlspecialchars($pengaturan['alamat_sekolah']) ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email_panitia" class="form-label">Email Panitia</label>
                    <input type="email" class="form-control" id="email_panitia" name="pengaturan[email_panitia]" value="<?= htmlspecialchars($pengaturan['email_panitia']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="wa_panitia" class="form-label">No. WhatsApp Panitia</label>
                    <input type="text" class="form-control" id="wa_panitia" name="pengaturan[wa_panitia]" value="<?= htmlspecialchars($pengaturan['wa_panitia']) ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal & Kuota will be added here later -->

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            Pengaturan Jadwal PPDB
        </div>
        <div class="card-body">
            <?php
            $query_jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal ORDER BY id");
            while($jadwal = mysqli_fetch_assoc($query_jadwal)):
            ?>
            <div class="row mb-2">
                <input type="hidden" name="jadwal[<?= $jadwal['id'] ?>][id]" value="<?= $jadwal['id'] ?>">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="jadwal[<?= $jadwal['id'] ?>][tahapan]" value="<?= htmlspecialchars($jadwal['tahapan']) ?>">
                </div>
                <div class="col-md-4">
                    <input type="datetime-local" class="form-control" name="jadwal[<?= $jadwal['id'] ?>][tgl_mulai]" value="<?= date('Y-m-d\TH:i', strtotime($jadwal['tgl_mulai'])) ?>">
                </div>
                <div class="col-md-4">
                    <input type="datetime-local" class="form-control" name="jadwal[<?= $jadwal['id'] ?>][tgl_selesai]" value="<?= date('Y-m-d\TH:i', strtotime($jadwal['tgl_selesai'])) ?>">
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            Pengaturan Kuota Jalur
        </div>
        <div class="card-body">
            <?php
            $query_jalur = mysqli_query($koneksi, "SELECT * FROM jalur ORDER BY id");
            while($jalur = mysqli_fetch_assoc($query_jalur)):
            ?>
            <div class="row mb-2">
                <input type="hidden" name="jalur[<?= $jalur['id'] ?>][id]" value="<?= $jalur['id'] ?>">
                <div class="col-md-6">
                    <label class="form-label">Nama Jalur</label>
                    <input type="text" class="form-control" name="jalur[<?= $jalur['id'] ?>][nama_jalur]" value="<?= htmlspecialchars($jalur['nama_jalur']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kuota</label>
                    <input type="number" class="form-control" name="jalur[<?= $jalur['id'] ?>][kuota]" value="<?= htmlspecialchars($jalur['kuota']) ?>">
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Semua Perubahan</button>
</form>

<?php require_once 'includes/footer.php'; ?>
