<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

<?php
// Filtering logic
$where_clauses = [];
$filter_jalur = $_GET['jalur'] ?? '';
$filter_status = $_GET['status'] ?? '';

if (!empty($filter_jalur)) {
    $where_clauses[] = "p.jalur_id = " . intval($filter_jalur);
}
if (!empty($filter_status)) {
    $where_clauses[] = "p.status_pendaftaran = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

$query_pendaftar = "SELECT 
                        p.id as pendaftaran_id, p.no_pendaftaran, p.status_pendaftaran, p.skor_akhir,
                        u.nama_lengkap, u.sekolah_asal,
                        j.nama_jalur
                    FROM pendaftaran p
                    JOIN pendaftar u ON p.pendaftar_id = u.id
                    JOIN jalur j ON p.jalur_id = j.id
                    $where_sql
                    ORDER BY p.tgl_daftar DESC";

$result_pendaftar = mysqli_query($koneksi, $query_pendaftar);

?>

<h1 class="mt-4">Daftar Pendaftar</h1>
<p>Kelola semua data pendaftar yang masuk ke sistem.</p>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <i class="bi bi-filter"></i> Filter Data
    </div>
    <div class="card-body">
        <form method="GET" action="pendaftar.php">
            <div class="row">
                <div class="col-md-5">
                    <label for="jalur" class="form-label">Jalur Pendaftaran</label>
                    <select name="jalur" id="jalur" class="form-select">
                        <option value="">Semua Jalur</option>
                        <?php
                        $query_jalur_filter = mysqli_query($koneksi, "SELECT * FROM jalur");
                        while($jalur = mysqli_fetch_assoc($query_jalur_filter)) {
                            $selected = ($filter_jalur == $jalur['id']) ? 'selected' : '';
                            echo "<option value='{$jalur['id']}' $selected>".htmlspecialchars($jalur['nama_jalur'])."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="status" class="form-label">Status Pendaftaran</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <?php
                        $status_options = ['Proses', 'Diverifikasi', 'Lulus', 'Tidak Lulus', 'Daftar Ulang'];
                        foreach ($status_options as $opt) {
                            $selected = ($filter_status == $opt) ? 'selected' : '';
                            echo "<option value='$opt' $selected>$opt</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="bi bi-table"></i> Data Pendaftar
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th>Jalur</th>
                        <th>Skor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_pendaftar) > 0): ?>
                        <?php while($data = mysqli_fetch_assoc($result_pendaftar)): ?>
                            <tr>
                                <td><?= htmlspecialchars($data['no_pendaftaran']) ?></td>
                                <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($data['sekolah_asal']) ?></td>
                                <td><?= htmlspecialchars($data['nama_jalur']) ?></td>
                                <td><?= htmlspecialchars($data['skor_akhir'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($data['status_pendaftaran']) ?></span></td>
                                <td>
                                    <a href="detail_pendaftar.php?id=<?= $data['pendaftaran_id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Detail</a>
                                    <!-- Add more actions here -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
