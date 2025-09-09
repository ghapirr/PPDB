<?php require_once 'includes/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="display-6 fw-bold">Jadwal Pelaksanaan PPDB</h1>
    <p class="lead">Berikut adalah jadwal lengkap pelaksanaan PPDB Online <?= htmlspecialchars($pengaturan['nama_sekolah']) ?>.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar3"></i> Jadwal PPDB Tahun Ajaran <?= htmlspecialchars($pengaturan['tahun_ajaran']) ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Kegiatan</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query_jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal ORDER BY tgl_mulai ASC");
                            $nomor = 1;
                            if(mysqli_num_rows($query_jadwal) > 0):
                                while($jadwal = mysqli_fetch_assoc($query_jadwal)):
                            ?>
                                <tr>
                                    <td><?= $nomor++ ?></td>
                                    <td><?= htmlspecialchars($jadwal['tahapan']) ?></td>
                                    <td><?= date('d F Y, H:i', strtotime($jadwal['tgl_mulai'])) ?> WIB</td>
                                    <td><?= date('d F Y, H:i', strtotime($jadwal['tgl_selesai'])) ?> WIB</td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center">Jadwal belum tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                Harap perhatikan jadwal dengan seksama.
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
