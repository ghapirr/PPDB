<?php require_once 'includes/header.php'; ?>

<?php
// Redirect to dashboard if already logged in
if (isset($_SESSION['pendaftar_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0 text-center"><i class="bi bi-box-arrow-in-right"></i> Login Pendaftar</h4>
            </div>
            <div class="card-body p-4">
                <form action="proses_login.php" method="POST">
                    <?php 
                    if(isset($_SESSION['login_error'])):
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['login_error']; ?>
                    </div>
                    <?php 
                        unset($_SESSION['login_error']);
                    endif;
                    ?>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" class="form-control" id="nik" name="nik" required maxlength="16">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Login</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-0">Belum punya akun? <a href="daftar.php">Buat Akun Sekarang</a></p>
                    <p><a href="#">Lupa Password?</a></p> <!-- Placeholder for future feature -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
