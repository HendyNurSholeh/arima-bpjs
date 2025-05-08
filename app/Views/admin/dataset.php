<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Naive Bayes | Hipertensi </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="/assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="/assets/images/logo-circle.png" />
</head>

<body class="with-welcome-text">
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo fw-bold" style="font-size: 0.9em;" href="index.html">
                        <!-- <img src="/assets/images/logo.svg" alt="logo" /> -->
                        Hipertensi | <sup>Naive Bayes</sup>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="index.html">
                        <sup> Naive Bayes</sup>
                        <!-- <img src="/assets/images/logo-mini.svg" alt="logo" /> -->
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold">Admin</span></h1>
                        <h3 class="welcome-sub-text">Selamat datang di aplikasi Naive Bayes Hipertensi.</h3>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="/assets/images/faces/face8.jpg" alt="Profile image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="/assets/images/faces/face8.jpg"
                                    alt="Profile image">
                                <p class="mb-1 mt-3 fw-semibold">Admin</p>
                            </div>
                            <a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">
                            <i class="mdi mdi-grid-large menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Naive Bayes</li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dataset">
                            <i class="menu-icon mdi mdi-database"></i>
                            <span class="menu-title">Dataset</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/peforma">
                            <i class="menu-icon mdi mdi-chart-line"></i>
                            <span class="menu-title">Peforma</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/prediksi">
                            <i class="menu-icon mdi mdi-chart-areaspline"></i>
                            <span class="menu-title">Prediksi</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Logout</li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">
                            <i class="menu-icon mdi mdi-logout"></i>
                            <span class="menu-title">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="home-tab">
                                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                        aria-labelledby="overview">
                                        <div class="row">
                                            <div class="col-lg-12 d-flex flex-column">
                                                <div class="row flex-grow">
                                                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                                        <div class="card card-rounded">
                                                            <div class="card-body">
                                                                <h4 class="card-title">Dataset</h4>
                                                                <form action="/admin/dataset/import" method="post"
                                                                    enctype="multipart/form-data">
                                                                    <div class="form-group">
                                                                        <label for="file">Import Data Excel (format:
                                                                            .xlsx, .xls)</label>
                                                                        <input type="file" name="file"
                                                                            class="form-control" id="file"
                                                                            accept=".xlsx, .xls" required>
                                                                        <button type="submit"
                                                                            class="btn btn-outline-primary text-primary d-flex mt-1 justify-content-center btn-icon-text"
                                                                            id="submit-button">
                                                                            <i class="ti-file btn-icon-prepend"></i>
                                                                            Submit
                                                                        </button>
                                                                        <style>
                                                                        #submit-button:hover {
                                                                            color: white !important;
                                                                        }
                                                                        </style>
                                                                    </div>
                                                                </form>
                                                                <!-- <div class="mt-3">
                                                                    <span class="badge bg-success">Data berhasil
                                                                        diimport</span>
                                                                    <span class="badge bg-danger">Gagal mengimport
                                                                        data</span>
                                                                </div> -->
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Nama</th>
                                                                                <th>Umur</th>
                                                                                <th>Tekanan <sup>darah</sup> </th>
                                                                                <th>Gula <sup>darah</sup></th>
                                                                                <th>Asam <sup>urat</sup></th>
                                                                                <th>Kolestrol</th>
                                                                                <th>Berat</th>
                                                                                <th>Tinggi</th>
                                                                                <th>Lingkar <sup>perut</sup></th>
                                                                                <th>Hipertensi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="9" class="text-center"
                                                                                    style="background-color: #d1ecf1;">
                                                                                    -- Atribut Pendukung --</td>
                                                                                <td class="text-center"
                                                                                    style="background-color: #ffeeba;">
                                                                                    -- Label Target --</td>
                                                                            </tr>
                                                                            <?php foreach ($data as $row): ?>
                                                                            <tr>
                                                                                <td><?= $row['nama'] ?></td>
                                                                                <td><?= $row['umur'] ?></td>
                                                                                <td><?= $row['tekanan_darah'] ?></td>
                                                                                <td><?= $row['gula_darah'] ?></td>
                                                                                <td><?= $row['asam_urat'] ?></td>
                                                                                <td><?= $row['kolesterol'] ?></td>
                                                                                <td><?= $row['berat_badan'] ?></td>
                                                                                <td><?= $row['tinggi_badan'] ?></td>
                                                                                <td><?= $row['lingkar_perut'] ?></td>
                                                                                <td><?= $row['status'] ?></td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All
                            rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/assets/js/off-canvas.js"></script>
    <script src="/assets/js/template.js"></script>
    <script src="/assets/js/settings.js"></script>
    <script src="/assets/js/hoverable-collapse.js"></script>
    <script src="/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="/assets/js/dashboard.js"></script>
    <!-- <script src="/assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>