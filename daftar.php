<?php require_once 'includes/header.php'; ?>

<?php
// Redirect to dashboard if already logged in
if (isset($_SESSION['pendaftar_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0 text-center"><i class="bi bi-person-plus-fill"></i> Formulir Pendaftaran Akun</h4>
            </div>
            <div class="card-body p-4">
                <form action="proses_daftar.php" method="POST">
                    <?php 
                    if(isset($_SESSION['register_error'])):
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['register_error']; ?>
                    </div>
                    <?php 
                        unset($_SESSION['register_error']);
                    endif;
                    ?>

                    <h5>Data Akun</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required minlength="16" maxlength="16">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                        </div>
                    </div>

                    <h5 class="mt-4">Data Pribadi</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap (sesuai ijazah)</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled selected>Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sekolah_asal" class="form-label">Sekolah Asal</label>
                            <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No. HP / WhatsApp</label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
