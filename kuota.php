<?php require_once 'includes/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="display-6 fw-bold">Kuota Penerimaan</h1>
    <p class="lead">Informasi kuota penerimaan siswa baru di <?= htmlspecialchars($pengaturan['nama_sekolah']) ?>.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Visualisasi Kuota</h5>
            </div>
            <div class="card-body">
                <canvas id="kuotaChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Rincian Kuota per Jalur</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Jalur</th>
                                <th>Kuota</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_jalur = mysqli_query($koneksi, "SELECT * FROM jalur");
                            $chart_labels = [];
                            $chart_data = [];
                            $total_kuota = 0;
                            while($jalur = mysqli_fetch_assoc($query_jalur)) {
                                $chart_labels[] = $jalur['nama_jalur'];
                                $chart_data[] = $jalur['kuota'];
                                $total_kuota += $jalur['kuota'];
                                echo "<tr>";
                                echo "<td>".htmlspecialchars($jalur['nama_jalur'])."</td>";
                                echo "<td>".htmlspecialchars($jalur['kuota'])." Siswa</td>";
                                echo "<td>".htmlspecialchars($jalur['deskripsi'])."</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td>Total</td>
                                <td><?= $total_kuota ?> Siswa</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kuotaChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Kuota',
                data: <?= json_encode($chart_data) ?>,
                backgroundColor: [
                    'rgba(25, 135, 84, 0.8)', // Success
                    'rgba(255, 193, 7, 0.8)',  // Warning
                    'rgba(13, 110, 253, 0.8)', // Primary
                    'rgba(220, 53, 69, 0.8)'  // Danger
                ],
                borderColor: [
                    'rgba(25, 135, 84, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(13, 110, 253, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += context.parsed + ' siswa';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
