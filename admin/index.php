<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>

<?php
// Fetching statistics
$total_pendaftar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM pendaftar"))['total'];
$total_pendaftaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id) as total FROM pendaftaran"))['total'];

$status_counts = [];
$query_status = mysqli_query($koneksi, "SELECT status_pendaftaran, COUNT(id) as count FROM pendaftaran GROUP BY status_pendaftaran");
while($row = mysqli_fetch_assoc($query_status)) {
    $status_counts[$row['status_pendaftaran']] = $row['count'];
}

$jalur_counts = [];
$query_jalur = mysqli_query($koneksi, "SELECT j.nama_jalur, COUNT(p.id) as count FROM pendaftaran p JOIN jalur j ON p.jalur_id = j.id GROUP BY j.nama_jalur");
$chart_labels = [];
$chart_data = [];
while($row = mysqli_fetch_assoc($query_jalur)) {
    $jalur_counts[$row['nama_jalur']] = $row['count'];
    $chart_labels[] = $row['nama_jalur'];
    $chart_data[] = $row['count'];
}

?>

<h1 class="mt-4">Dashboard</h1>
<p>Selamat datang di panel admin PPDB Online, <?= htmlspecialchars($_SESSION['admin_nama']); ?>.</p>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-people-fill fs-3"></i></div>
                    <div class="text-end">
                        <div class="fs-4"><?= $total_pendaftar ?></div>
                        <div>Total Akun Pendaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-card-list fs-3"></i></div>
                    <div class="text-end">
                        <div class="fs-4"><?= $total_pendaftaran ?></div>
                        <div>Total Formulir Masuk</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-person-video3 fs-3"></i></div>
                    <div class="text-end">
                        <div class="fs-4"><?= $status_counts['Proses'] ?? 0 ?></div>
                        <div>Belum Diverifikasi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-check-circle-fill fs-3"></i></div>
                    <div class="text-end">
                        <div class="fs-4"><?= $status_counts['Lulus'] ?? 0 ?></div>
                        <div>Pendaftar Lulus</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill me-1"></i>
                Grafik Pendaftar per Jalur
            </div>
            <div class="card-body"><canvas id="jalurChart" width="100%" height="30"></canvas></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('jalurChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
