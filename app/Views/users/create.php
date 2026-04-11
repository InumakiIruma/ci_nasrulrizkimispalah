<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maldin17App | Tambah User</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-register {
            max-width: 500px;
            margin: 50px auto;
            border: none;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card card-register shadow-lg">
            <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                <h4 class="mb-0 fw-bold">Form Tambah User</h4>
                <small>Silakan lengkapi data di bawah ini</small>
            </div>
            <div class="card-body p-4">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('users/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="<?= old('nama') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= old('email') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username" value="<?= old('username') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="****" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Role Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="petugas" <?= old('role') == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="anggota" <?= old('role') == 'anggota' ? 'selected' : '' ?>>Anggota</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Foto Profil</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Format: JPG, PNG (Maks. 2MB). Kosongkan jika tidak ada.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                            <i class="bi bi-person-plus-fill me-2"></i> Simpan User
                        </button>
                        <a href="<?= base_url('login') ?>" class="btn btn-link text-decoration-none text-muted small">
                            Sudah punya akun? Login di sini
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>