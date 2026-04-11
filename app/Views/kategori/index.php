<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark"><i class="bi bi-tags text-primary me-2"></i> Manajemen Kategori Alat</h4>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th width="30%">Nama Kategori</th>
                            <th width="35%">Keterangan</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kategori)) : ?>
                            <?php $no = 1;
                            foreach ($kategori as $k) : ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td><span class="badge rounded-pill bg-light text-dark border"><?= $k['kode_kategori']; ?></span></td>
                                    <td class="fw-medium text-dark"><?= $k['nama_kategori']; ?></td>
                                    <td class="text-muted small"><?= $k['keterangan'] ?: '-'; ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('kategori/hapus/' . $k['id']) ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                            class="btn btn-outline-danger btn-sm rounded-circle">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Data kategori belum tersedia. Silakan tambah kategori baru.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('kategori/simpan') ?>" method="post">
                <?= csrf_field() ?> <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control"
                            placeholder="Contoh: ELK, KMR" required maxlength="5"
                            style="text-transform: uppercase;">
                        <div class="form-text">Maksimal 5 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control"
                            placeholder="Contoh: Elektronik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                            placeholder="Tambahkan deskripsi singkat..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>