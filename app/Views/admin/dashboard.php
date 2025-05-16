<?= self::extend('layout/admin_layout'); ?>
<?= self::section('content'); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Selamat Datang, Admin!</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tentang Aplikasi Prediksi Pendaftar BPJS</h6>
                </div>
                <div class="card-body">
                    <p>
                        Aplikasi ini digunakan untuk memprediksi jumlah pendaftar BPJS setiap bulan menggunakan metode <b>ARIMA</b> (AutoRegressive Integrated Moving Average).
                    </p>
                    <ol>
                        <li>
                            <b><i class="fas fa-database text-warning"></i> Dataset:</b> Kelola dan import data riwayat pendaftar BPJS.
                        </li>
                        <li>
                            <b><i class="fas fa-percentage text-success"></i> Peformance ARIMA:</b> Lihat performa model ARIMA pada data yang diupload.
                        </li>
                        <li>
                            <b><i class="fas fa-chart-line text-info"></i> Prediksi & Hasil:</b> Lihat hasil prediksi dalam bentuk grafik dan tabel.
                        </li>
                        <li>
                            <b><i class="fas fa-cogs text-secondary"></i> Pengaturan Akun:</b> Kelola akun admin.
                        </li>
                        <li>
                            <b><i class="fas fa-sign-out-alt text-danger"></i> Logout:</b> Keluar dari aplikasi.
                        </li>
                    </ol>
                    <p>
                        Silakan gunakan menu di sebelah kiri untuk mengelola data, melakukan analisis, melihat hasil prediksi, dan mengatur akun Anda.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Prediksi berbasis data historis.</li>
                        <li>Visualisasi data dalam bentuk grafik.</li>
                        <li>Import data prediksi dari format Excel.</li>
                    </ul>
                    <a href="https://en.wikipedia.org/wiki/Autoregressive_integrated_moving_average" target="_blank">Pelajari tentang ARIMA &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= self::endSection(); ?>
