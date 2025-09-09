<?php 
require_once 'includes/header.php'; 
require_once 'includes/sidebar.php'; 

$filter_jalur = $_GET['jalur'] ?? '';

$where_clause = '';
$kuota = 0;
$nama_jalur_terpilih = 'Semua Jalur';

if (!empty($filter_jalur)) {
    $where_clause = "WHERE p.jalur_id = " . intval($filter_jalur);
    $jalur_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_jalur, kuota FROM jalur WHERE id = " . intval($filter_jalur)));
    $kuota = $jalur_info['kuota'];
    $nama_jalur_terpilih = $jalur_info['nama_jalur'];
}

$query = "SELECT 
            p.id, p.no_pendaftaran, p.skor_akhir,
            u.nama_lengkap,
            j.nama_jalur
          FROM pendaftaran p
          JOIN pendaftar u ON p.pendaftar_id = u.id
          JOIN jalur j ON p.jalur_id = j.id
          $where_clause
          ORDER BY p.skor_akhir DESC";

$result = mysqli_query($koneksi, $query);

?>

<h1 class="mt-4">Ranking & Seleksi</h1>
<p>Halaman ini menampilkan peringkat pendaftar berdasarkan skor akhir. Gunakan tombol untuk menghitung skor (demo).</p>

<?php 
if(isset($_SESSION['ranking_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['ranking_success'] . '</div>';
    unset($_SESSION['ranking_success']);
}
?>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        Aksi & Filter
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <a href="proses_hitung_skor.php" class="btn btn-primary mb-3" onclick="return confirm('Ini akan menimpa semua skor akhir dengan nilai acak. Lanjutkan?')">Hitung Skor & Buat Peringkat (Demo)</a>
            </div>
            <div class="col-md-8">
                <form method="GET" action="ranking.php">
                    <div class="input-group">
                        <select name="jalur" class="form-select">
                            <option value="">Tampilkan Semua Jalur</option>
                            <?php
                            $query_jalur_filter = mysqli_query($koneksi, "SELECT * FROM jalur");
                            while($jalur = mysqli_fetch_assoc($query_jalur_filter)) {
                                $selected = ($filter_jalur == $jalur['id']) ? 'selected' : '';
                                echo "<option value='{$jalur['id']}' $selected>".htmlspecialchars($jalur['nama_jalur'])."</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-info">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-bar-chart-steps"></i> Peringkat Pendaftar - <?= htmlspecialchars($nama_jalur_terpilih) ?>
        <?php if($kuota > 0) echo "(Kuota: $kuota)"; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Jalur</th>
                        <th>Skor Akhir</th>
                        <th>Status Kelulusan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    if (mysqli_num_rows($result) > 0):
                        while($data = mysqli_fetch_assoc($result)):
                            $is_lulus = (!empty($filter_jalur) && $rank <= $kuota);
                            $row_class = $is_lulus ? 'table-success' : '';
                    ?>
                        <tr class="<?= $row_class ?>">
                            <td><?= $rank++ ?></td>
                            <td><?= htmlspecialchars($data['no_pendaftaran']) ?></td>
                            <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($data['nama_jalur']) ?></td>
                            <td><strong><?= number_format($data['skor_akhir'], 2) ?></strong></td>
                            <td>
                                <?php if($is_lulus): ?>
                                    <span class="badge bg-success">Lulus</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Tidak Lulus</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr><td colspan="6" class="text-center">Data tidak tersedia. Coba hitung skor terlebih dahulu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
