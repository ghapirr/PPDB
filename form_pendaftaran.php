<?php 
require_once 'includes/header.php'; 

// Cek jika pengguna sudah login
if (!isset($_SESSION['pendaftar_id'])) {
    header('Location: login.php');
    exit();
}

$pendaftar_id = $_SESSION['pendaftar_id'];

// Cek apakah sudah pernah mendaftar sebelumnya, jika ya, redirect ke dashboard
$query_check = "SELECT id FROM pendaftaran WHERE pendaftar_id = ? LIMIT 1";
$stmt_check = mysqli_prepare($koneksi, $query_check);
mysqli_stmt_bind_param($stmt_check, "i", $pendaftar_id);
mysqli_stmt_execute($stmt_check);
if (mysqli_stmt_get_result($stmt_check)->num_rows > 0) {
    header('Location: dashboard.php');
    exit();
}

?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0 text-center"><i class="bi bi-card-list"></i> Formulir Pendaftaran PPDB</h4>
            </div>
            <div class="card-body p-4">
                <form action="proses_form_pendaftaran.php" method="POST">
                    <?php 
                    if(isset($_SESSION['form_error'])):
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['form_error']; ?>
                    </div>
                    <?php 
                        unset($_SESSION['form_error']);
                    endif;
                    ?>

                    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong>. Silakan lengkapi data pendaftaran Anda di bawah ini.</p>

                    <div class="mb-3">
                        <label for="jalur_id" class="form-label">Pilih Jalur Pendaftaran</label>
                        <select class="form-select" id="jalur_id" name="jalur_id" required>
                            <option value="" disabled selected>-- Pilih Jalur --</option>
                            <?php
                            $query_jalur = mysqli_query($koneksi, "SELECT * FROM jalur ORDER BY nama_jalur");
                            while($jalur = mysqli_fetch_assoc($query_jalur)) {
                                echo "<option value='{$jalur['id']}'>".htmlspecialchars($jalur['nama_jalur'])." (Kuota: {$jalur['kuota']})</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Pilih jalur yang paling sesuai dengan kriteria Anda.</div>
                    </div>

                    <!-- Placeholder for future fields based on jalur -->
                    <div id="dynamic-fields"></div>

                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" value="" id="konfirmasi_data" required>
                        <label class="form-check-label" for="konfirmasi_data">
                            Saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan.
                        </label>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Dapatkan Nomor Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
