<?= self::extend('layout/admin_layout'); ?>
<?= self::section('content'); ?>
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Menu Prediksi Pendaftar BPJS</h1>
    <p class="mb-4">
        Pilih rentang waktu untuk melakukan prediksi jumlah pendaftar BPJS menggunakan ARIMA.
    </p>

    <!-- Form Prediksi Rentang Waktu -->
    <div class="mb-3">
        <div class="card border-primary shadow-sm">
            <div class="card-body p-3">
                <form action="" method="get" class="form-inline row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="tanggal_mulai" class="col-form-label">Tanggal Mulai</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required
                            value="<?= isset($_GET['tanggal_mulai']) ? esc($_GET['tanggal_mulai']) : '' ?>">
                    </div>
                    <div class="col-auto">
                        <label for="tanggal_akhir" class="col-form-label">Tanggal Akhir</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required
                            value="<?= isset($_GET['tanggal_akhir']) ? esc($_GET['tanggal_akhir']) : '' ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-chart-line"></i> Prediksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Prediksi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($summary['total']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rata-rata</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($summary['avg']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Min</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($summary['min']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Max</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($summary['max']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hasil Prediksi Jumlah Pendaftar BPJS</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Bulan</th>
                            <th>Jumlah Pendaftar (Prediksi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prediksiData)): ?>
                            <?php foreach ($prediksiData as $row): ?>
                                <tr>
                                    <td><?= esc($row['no']) ?></td>
                                    <td><?= esc($row['tanggal']) ?></td>
                                    <td><?= esc($row['bulan']) ?></td>
                                    <td><?= esc($row['jumlah']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data prediksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik Tren Prediksi -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Tren Prediksi</h6>
        </div>
        <div class="card-body">
            <canvas id="chartPrediksi" height="100"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('chartPrediksi').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Jumlah Pendaftar (Prediksi)',
                data: <?= json_encode($jumlahPrediksi) ?>,
                borderColor: 'rgba(78, 115, 223, 1)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: false }
            }
        }
    });
</script>
<?= self::endSection(); ?>
