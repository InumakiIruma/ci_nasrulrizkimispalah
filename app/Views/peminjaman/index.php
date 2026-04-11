<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-cart-plus me-2"></i>Pilih Alat untuk Dipinjam</h4>
            <p class="text-muted">Klik "Pinjam Alat" pada item yang ingin diproses.</p>
        </div>
    </div>

    <div class="row">
        <?php foreach ($alat as $a): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-soft-primary text-primary border border-primary-subtle">
                                <?= $a['kategori']; ?>
                            </span>
                            <small class="text-muted">Stok: <?= $a['stok']; ?></small>
                        </div>
                        <h5 class="card-title fw-bold mb-1"><?= $a['nama_alat']; ?></h5>
                        <p class="card-text text-muted small">ID: #ALT-00<?= $a['id']; ?></p>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3">
                        <button class="btn btn-primary w-100 rounded-pill fw-bold"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPinjam"
                            onclick="setAlat('<?= $a['id']; ?>', '<?= $a['nama_alat']; ?>')">
                            Pinjam Alat
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="modalPinjam" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Formulir Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/peminjaman/proses" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_alat" id="id_alat">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alat yang Dipilih</label>
                        <input type="text" id="nama_alat_display" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Estimasi Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-modal="dismiss">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Konfirmasi Pinjam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setAlat(id, nama) {
        document.getElementById('id_alat').value = id;
        document.getElementById('nama_alat_display').value = nama;
    }
</script>

<style>
    .card-hover {
        transition: transform 0.2s, shadow 0.2s;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-soft-primary {
        background-color: #e7f1ff;
    }
</style>

<?= $this->endSection() ?>