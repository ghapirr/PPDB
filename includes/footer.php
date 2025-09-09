</main>

<footer class="bg-light text-center text-lg-start mt-auto">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase"><?= $pengaturan['nama_sekolah'] ?? '' ?></h5>
                <p>
                    <?= $pengaturan['alamat_sekolah'] ?? '' ?>
                </p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Kontak</h5>
                <ul class="list-unstyled mb-0">
                    <li>
                        <i class="bi bi-envelope-fill"></i> <a href="mailto:<?= $pengaturan['email_panitia'] ?? '' ?>" class="text-dark"><?= $pengaturan['email_panitia'] ?? '' ?></a>
                    </li>
                    <li>
                        <i class="bi bi-whatsapp"></i> <a href="https://wa.me/<?= $pengaturan['wa_panitia'] ?? '' ?>" target="_blank" class="text-dark"><?= $pengaturan['wa_panitia'] ?? '' ?></a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Tautan</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-dark">Beranda</a></li>
                    <li><a href="alur.php" class="text-dark">Alur Pendaftaran</a></li>
                    <li><a href="jadwal.php" class="text-dark">Jadwal</a></li>
                    <li><a href="pengumuman.php" class="text-dark">Pengumuman</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        © <?= date('Y') ?> Panitia PPDB Online <?= $pengaturan['nama_sekolah'] ?? '' ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
