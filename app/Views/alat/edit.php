<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Data Alat</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('alat/update/' . $alat['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" value="<?= $alat['nama_alat'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?= $alat['stok'] ?>" required>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('/alat') ?>" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>