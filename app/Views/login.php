<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BPJS PBI | Time Series Forecasting</title>
    <link rel="stylesheet" href="/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/assets/images/bpjs-icon.png" />
</head>

<body>
    <div class="container-scroller"></div>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 pt-3 px-sm-5">
                        <div class="brand-logo text-center mb-2 d-flex align-items-center justify-content-center">
                            <img src="/assets/images/bpjs-icon.png" alt="logo" style="width: 80px;">
                        </div>
                        <h2 class="text-center fw-bold mt-3 pt-0 mb-4">Login ke Sistem Peramalan BPJS PBI</h2>
                        <h6 class="fw-light text-center mb-3">Silahkan login untuk mengakses sistem peramalan pendaftar
                            BPJS PBI menggunakan metode ARIMA.</h6>
                        <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger text-center fw-bold text-small">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                        <?php endif; ?>
                        <form class="pt-2" method="POST" action="/login">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" placeholder="Masukkan Username"
                                    name="username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg"
                                    placeholder="Masukkan Password" name="password" required>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="/assets/js/template.js"></script>
</body>

</html>