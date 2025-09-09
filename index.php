<?php require_once 'includes/header.php'; ?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Penerimaan Peserta Didik Baru (PPDB) Online</h1>
        <h2 class="fs-4"><?= $pengaturan['nama_sekolah'] ?? '' ?> Tahun Ajaran <?= $pengaturan['tahun_ajaran'] ?? '' ?></h2>
        <p class="col-md-8 fs-5 mt-3">Selamat datang di sistem pendaftaran online. Silakan ikuti alur pendaftaran dan lengkapi data Anda dengan benar.</p>
        <a href="daftar.php" class="btn btn-success btn-lg">Daftar Sekarang <i class="bi bi-arrow-right-circle-fill"></i></a>
        <a href="alur.php" class="btn btn-outline-secondary btn-lg">Lihat Alur Pendaftaran</a>
    </div>
</div>

<div class="row align-items-md-stretch">
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 text-white bg-success rounded-3 shadow">
            <h2><i class="bi bi-calendar-check"></i> Jadwal Penting</h2>
            <p>Perhatikan tanggal-tanggal penting berikut agar tidak terlewat.</p>
            <ul class="list-unstyled">
                <?php 
                $query_jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal ORDER BY tgl_mulai ASC");
                while($jadwal = mysqli_fetch_assoc($query_jadwal)): 
                ?>
                    <li>
                        <strong><?= htmlspecialchars($jadwal['tahapan']) ?>:</strong> 
                        <?= date('d M Y', strtotime($jadwal['tgl_mulai'])) ?> - <?= date('d M Y', strtotime($jadwal['tgl_selesai'])) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 bg-light border rounded-3 shadow-sm">
            <h2><i class="bi bi-bullhorn"></i> Pengumuman</h2>
            <p>Hasil seleksi akan diumumkan sesuai jadwal. Calon siswa dapat melihat status kelulusan melalui halaman pengumuman.</p>
            <p>Untuk melihat hasil, siapkan nomor pendaftaran Anda.</p>
            <a href="pengumuman.php" class="btn btn-primary">Lihat Halaman Pengumuman <i class="bi bi-search"></i></a>
        </div>
    </div>
</div>

<div class="mt-4 p-5 bg-light rounded-3 shadow-sm">
    <div class="row">
        <div class="col-md-4 text-center">
            <i class="bi bi-person-plus-fill fs-1 text-success"></i>
            <h4 class="mt-2">1. Buat Akun</h4>
            <p>Daftarkan diri Anda dengan NIK dan email untuk membuat akun.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="bi bi-card-list fs-1 text-success"></i>
            <h4 class="mt-2">2. Isi Formulir</h4>
            <p>Login dan lengkapi formulir pendaftaran dengan data yang valid.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="bi bi-cloud-upload-fill fs-1 text-success"></i>
            <h4 class="mt-2">3. Upload Dokumen</h4>
            <p>Unggah dokumen yang diperlukan sesuai dengan jalur yang dipilih.</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
