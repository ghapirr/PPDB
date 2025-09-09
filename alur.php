<?php require_once 'includes/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="display-6 fw-bold">Alur Pendaftaran PPDB Online</h1>
    <p class="lead">Ikuti langkah-langkah berikut untuk mendaftar di <?= htmlspecialchars($pengaturan['nama_sekolah']) ?>.</p>
</div>

<div class="row g-4 justify-content-center">
    <!-- Step 1 -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-success"><i class="bi bi-person-plus"></i></div>
                <h5 class="card-title mt-3">1. Registrasi Akun</h5>
                <p class="card-text">Calon siswa membuat akun menggunakan NIK dan email.</p>
            </div>
        </div>
    </div>
    <!-- Step 2 -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-success"><i class="bi bi-box-arrow-in-right"></i></div>
                <h5 class="card-title mt-3">2. Login Sistem</h5>
                <p class="card-text">Login ke sistem dengan akun yang telah dibuat.</p>
            </div>
        </div>
    </div>
    <!-- Step 3 -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-success"><i class="bi bi-pencil-square"></i></div>
                <h5 class="card-title mt-3">3. Isi Formulir</h5>
                <p class="card-text">Mengisi formulir pendaftaran secara online dengan data yang benar.</p>
            </div>
        </div>
    </div>
    <!-- Step 4 -->
    <div class="col-md-3 col-sm-6">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-success"><i class="bi bi-cloud-upload"></i></div>
                <h5 class="card-title mt-3">4. Upload Dokumen</h5>
                <p class="card-text">Mengunggah dokumen-dokumen persyaratan sesuai jalur yang dipilih.</p>
            </div>
        </div>
    </div>
    <!-- Step 5 -->
    <div class="col-md-3 col-sm-6 mt-4">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-primary"><i class="bi bi-person-check"></i></div>
                <h5 class="card-title mt-3">5. Verifikasi Data</h5>
                <p class="card-text">Panitia akan memverifikasi data dan dokumen yang telah diunggah.</p>
            </div>
        </div>
    </div>
    <!-- Step 6 -->
    <div class="col-md-3 col-sm-6 mt-4">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-primary"><i class="bi bi-bar-chart-steps"></i></div>
                <h5 class="card-title mt-3">6. Seleksi & Ranking</h5>
                <p class="card-text">Sistem akan melakukan perangkingan otomatis berdasarkan skor.</p>
            </div>
        </div>
    </div>
    <!-- Step 7 -->
    <div class="col-md-3 col-sm-6 mt-4">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-primary"><i class="bi bi-megaphone"></i></div>
                <h5 class="card-title mt-3">7. Pengumuman</h5>
                <p class="card-text">Hasil seleksi diumumkan secara online melalui halaman Pengumuman.</p>
            </div>
        </div>
    </div>
    <!-- Step 8 -->
    <div class="col-md-3 col-sm-6 mt-4">
        <div class="card text-center h-100 shadow-sm">
            <div class="card-body">
                <div class="fs-1 text-primary"><i class="bi bi-patch-check-fill"></i></div>
                <h5 class="card-title mt-3">8. Daftar Ulang</h5>
                <p class="card-text">Siswa yang dinyatakan lulus melakukan daftar ulang di sekolah.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
