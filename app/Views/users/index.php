<?= $this->extend('layouts/main') ?> <?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen User</h4>
            <p class="text-muted small">Kelola data pengguna sistem di sini.</p>
        </div>

        <?php if (session()->get('role') == 'admin') : ?>
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User Baru
            </button>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold">User</th>
                            <th class="py-3 text-uppercase small fw-bold">Username & Email</th>
                            <th class="py-3 text-uppercase small fw-bold text-center">Role</th>
                            <?php if (session()->get('role') == 'admin') : ?>
                                <th class="py-3 text-uppercase small fw-bold text-center pe-4">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u) : ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= base_url('uploads/users/' . ($u['foto'] ?: 'default.png')) ?>"
                                            class="rounded-circle border me-3" width="45" height="45" style="object-fit: cover;">
                                        <div class="fw-bold text-dark"><?= $u['nama'] ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-dark fw-semibold"><?= $u['username'] ?></div>
                                    <div class="small text-muted"><?= $u['email'] ?></div>
                                </td>
                                <td class="text-center">
                                    <?php $color = ($u['role'] == 'admin') ? 'bg-primary' : 'bg-success'; ?>
                                    <span class="badge <?= $color ?> rounded-pill px-3 shadow-sm" style="font-size: 11px;">
                                        <?= strtoupper($u['role']) ?>
                                    </span>
                                </td>
                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td class="text-center pe-4">
                                        <a href="<?= base_url('users/hapus/' . $u['id']) ?>"
                                            class="btn btn-light btn-sm text-danger rounded-3"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (session()->get('role') == 'admin') : ?>
    <div class="modal fade" id="modalTambahUser" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Form User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('users/store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control rounded-3" placeholder="Masukkan nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="email@contoh.com" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Password</label>
                                <input type="password" name="password" class="form-control rounded-3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Role</label>
                            <select name="role" class="form-select rounded-3" required>
                                <option value="user">User / Staff</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Foto Profil</label>
                            <input type="file" name="foto" class="form-control rounded-3" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>