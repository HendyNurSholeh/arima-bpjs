<?= self::extend('layout/admin_layout'); ?>
<?= self::section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Performance Model ARIMA</h1>
    <p class="mb-4">
        Berikut adalah evaluasi performa model ARIMA berdasarkan data aktual dan hasil prediksi.<br>
        <strong>Proporsi Data:</strong> Training 80% &mdash; Testing 20%
    </p>

    <!-- Ringkasan Akurasi -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">MAE</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(120, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">MSE</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(18000, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">RMSE</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(134, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">MAPE (%)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">1,2%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Aktual vs Prediksi -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Aktual vs Prediksi</h6>
        </div>
        <div class="card-body">
            <canvas id="chartPerformance" height="100"></canvas>
        </div>
    </div>

    <!-- Tabel Detail -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Evaluasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height:400px;">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Aktual</th>
                            <th>Prediksi</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>01/07/2024</td>
                            <td><?= number_format(12600, 0, ',', '.'); ?></td>
                            <td><?= number_format(12500, 0, ',', '.'); ?></td>
                            <td><?= number_format(100, 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>02/07/2024</td>
                            <td><?= number_format(12700, 0, ',', '.'); ?></td>
                            <td><?= number_format(12600, 0, ',', '.'); ?></td>
                            <td><?= number_format(100, 0, ',', '.'); ?></td>
                        </tr>
                        <!-- dst -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('chartPerformance').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['01/07/2024', '02/07/2024', '03/07/2024', '04/07/2024', '05/07/2024'],
            datasets: [
                {
                    label: 'Aktual',
                    data: [12600, 12700, 12750, 12800, 12900],
                    borderColor: 'rgba(54, 185, 204, 1)',
                    fill: false,
                    tension: 0.4
                },
                {
                    label: 'Prediksi',
                    data: [12500, 12600, 12700, 12800, 12900],
                    borderColor: 'rgba(78, 115, 223, 1)',
                    fill: false,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: false }
            }
        }
    });
</script>
<?= self::endSection(); ?>
