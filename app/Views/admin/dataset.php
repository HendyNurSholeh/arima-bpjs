<?= self::extend('layout/admin_layout'); ?>
<?= self::section('content'); ?>
<div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Import Dataset ARIMA BPJS</h1>
                    <p class="mb-4">
                        Silakan impor data BPJS untuk proses prediksi menggunakan ARIMA. Pastikan format file sesuai template yang disediakan.
                    </p>

                    
                    <div class="mb-3">
                        <div class="card border-primary shadow-sm">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-2 mb-md-0 d-flex gap-2">
                                        <a href="<?= base_url('admin/dataset/template'); ?>" class="btn btn-outline-secondary btn-sm mr-2">
                                            <i class="fas fa-download"></i> Download Template
                                        </a>
                                        <button class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#infoModal">
                                            <i class="fas fa-info-circle"></i> Info Format
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <form action="<?= base_url('admin/dataset/import'); ?>" method="post" enctype="multipart/form-data" class="form-inline d-flex justify-content-end">
                                        <div class="d-flex align-items-center">
                                                <div class="custom-file mr-2">
                                                    <input type="file" name="file" accept=".xlsx, application/vnd.ms-excel" required class="custom-file-input" id="datasetFileInput">
                                                    <label class="custom-file-label" for="datasetFileInput">Pilih file...</label>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm ml-2">
                                                    <i class="fas fa-upload"></i> Import Data
                                                </button>
                                        </div>    
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        // Untuk menampilkan nama file pada custom file input
                        document.addEventListener('DOMContentLoaded', function() {
                            var input = document.getElementById('datasetFileInput');
                            if(input){
                                input.addEventListener('change', function(e){
                                    var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                                    var label = e.target.nextElementSibling;
                                    if(label) label.innerText = fileName;
                                });
                            }
                        });
                    </script>
                    <!-- Data Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Data BPJS</h6>
                            <a href="<?= base_url('admin/dataset/clear'); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus semua data?')">
                                <i class="fas fa-trash"></i> Hapus Semua
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Import & Action Buttons -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun</th>
                                            <th>Bulan</th>
                                            <th>Jumlah Peserta</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi dari controller -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Info Modal -->
                    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">Format Data Import</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>File harus berformat <b>Excel (.xlsx)</b> dengan kolom berikut:</p>
                            <ul>
                                <li><b>No</b> (nomor urut)</li>
                                <li><b>Tanggal</b> (format: <i>dd/mm/yyyy</i>, contoh: 03/01/2022)</li>
                                <li><b>Jumlah Pendaftar</b> (angka)</li>
                                <li><b>Bulan</b> (misal: Januari, Februari, dst)</li>
                            </ul>
                            <p>Contoh baris data:</p>
                            <pre>No	Tanggal 	Jumlah Pendaftar    Bulan</pre>
                            <pre>1	03/01/2022	12345	            Januari</pre>
                          </div>
                        </div>
                      </div>
                    </div>

                </div>
<?= self::endSection(); ?>