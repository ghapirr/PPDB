<?php require_once 'includes/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="display-6 fw-bold">Pengumuman Hasil Seleksi</h1>
    <p class="lead">Masukkan nomor pendaftaran Anda untuk melihat hasil seleksi PPDB.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="pengumuman.php">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-lg" name="no_pendaftaran" placeholder="Masukkan Nomor Pendaftaran Anda..." value="<?= htmlspecialchars($_GET['no_pendaftaran'] ?? '') ?>" required>
                        <button class="btn btn-success" type="submit"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if (isset($_GET['no_pendaftaran']) && !empty($_GET['no_pendaftaran'])) {
            $no_pendaftaran = mysqli_real_escape_string($koneksi, $_GET['no_pendaftaran']);

            $query = "SELECT 
                        pendaftaran.*, 
                        pendaftar.nama_lengkap, pendaftar.sekolah_asal,
                        jalur.nama_jalur
                      FROM pendaftaran
                      JOIN pendaftar ON pendaftaran.pendaftar_id = pendaftar.id
                      JOIN jalur ON pendaftaran.jalur_id = jalur.id
                      WHERE pendaftaran.no_pendaftaran = '$no_pendaftaran'";
            
            $result = mysqli_query($koneksi, $query);

            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $status = $data['status_pendaftaran'];
                $alert_class = '';
                $status_text = '';

                switch ($status) {
                    case 'Lulus':
                        $alert_class = 'alert-success';
                        $status_text = 'SELAMAT, ANDA DINYATAKAN LULUS';
                        break;
                    case 'Tidak Lulus':
                        $alert_class = 'alert-danger';
                        $status_text = 'MOHON MAAF, ANDA DINYATAKAN TIDAK LULUS';
                        break;
                    default:
                        $alert_class = 'alert-info';
                        $status_text = 'PENDAFTARAN ANDA MASIH DALAM PROSES';
                        break;
                }
        ?>
                <div id="hasil-pengumuman" class="card shadow-sm mt-4">
                    <div class="alert <?= $alert_class ?> mb-0" role="alert">
                        <h4 class="alert-heading text-center"><?= $status_text ?></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 30%;">Nomor Pendaftaran</th>
                                    <td><?= htmlspecialchars($data['no_pendaftaran']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Nama Lengkap</th>
                                    <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Asal Sekolah</th>
                                    <td><?= htmlspecialchars($data['sekolah_asal']) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Jalur Pendaftaran</th>
                                    <td><?= htmlspecialchars($data['nama_jalur']) ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <?php if ($status == 'Lulus'): ?>
                            <div class="mt-4">
                                <h5>Langkah Selanjutnya: Daftar Ulang</h5>
                                <p>Silakan lakukan daftar ulang pada tanggal yang telah ditentukan. Informasi lebih lanjut mengenai persyaratan daftar ulang akan diinformasikan melalui website ini atau dapat ditanyakan langsung di sekolah.</p>
                            </div>
                        <?php elseif ($status == 'Tidak Lulus'): ?>
                             <div class="mt-4">
                                <p>Jangan berkecil hati. Terima kasih telah berpartisipasi dalam PPDB <?= htmlspecialchars($pengaturan['nama_sekolah']) ?>.</p>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-4">
                            <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer-fill"></i> Cetak Hasil Pengumuman</button>
                        </div>
                    </div>
                </div>
        <?php
            } else {
        ?>
                <div class="alert alert-warning mt-4" role="alert">
                    <strong>Data tidak ditemukan.</strong> Pastikan nomor pendaftaran yang Anda masukkan sudah benar.
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
