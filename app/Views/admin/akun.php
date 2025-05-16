<?= self::extend('layout/admin_layout'); ?>
<?= self::section('content'); ?>
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Pengaturan Akun</h1>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
            </div>
        <?php endif; ?>
    <div class="row">
        <!-- Profile Info -->
        <div class="col-md-6 mb-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user"></i> Informasi Profil
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8"><?= esc(session('nama') ?? 'Nama Pengguna'); ?></dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><?= esc(session('email') ?? 'email@email.com'); ?></dd>

                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8"><?= esc(session('username') ?? 'username'); ?></dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Reset Password -->
        <div class="col-md-6 mb-4">
            <div class="card shadow border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-key"></i> Reset Password
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/akun/reset_password'); ?>" method="post">
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password_baru" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password_konfirmasi" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sync-alt"></i> Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?= self::endSection(); ?>
