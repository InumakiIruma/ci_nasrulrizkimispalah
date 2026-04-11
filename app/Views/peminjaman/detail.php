<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <a href="<?= base_url('/peminjaman') ?>" class="btn btn-light btn-sm rounded-pill mb-3 border">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Detail Transaksi #<?= $peminjaman['id'] ?>
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Nama Alat</label>
                            <p class="text-dark fw-bold fs-5 mb-1"><?= $peminjaman['nama_alat'] ?? 'Tidak Diketahui' ?></p>
                            <span class="badge bg-soft-primary text-primary px-3"><?= $peminjaman['kategori'] ?? 'Umum' ?></span>
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Status Saat Ini</label>
                            <?php if ($peminjaman['status'] == 'pending') : ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Menunggu Konfirmasi</span>
                            <?php elseif ($peminjaman['status'] == 'dipinjam') : ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">Sedang Dipinjam</span>
                            <?php else : ?>
                                <span class="badge bg-secondary rounded-pill px-3 py-2">Selesai</span>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <hr class="opacity-25">
                        </div>

                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Peminjam</label>
                            <p class="text-dark mb-0 fw-semibold"><?= $peminjaman['nama_peminjam'] ?></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Jumlah Item</label>
                            <p class="text-dark mb-0 fw-semibold"><?= $peminjaman['jumlah'] ?? 0 ?> Unit</p>
                        </div>
                    </div>
                </div>

                <?php if ($peminjaman['status'] == 'pending' && session()->get('role') == 'admin') : ?>
                    <div class="card-footer bg-light border-0 p-4">
                        <div class="d-grid">
                            <a href="<?= base_url('peminjaman/konfirmasi/' . $peminjaman['id']) ?>"
                                class="btn btn-primary btn-lg rounded-4 fw-bold shadow-primary"
                                onclick="return confirm('Konfirmasi peminjaman ini?')">
                                Konfirmasi Peminjaman
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary {
        background-color: #f0f4ff;
    }

    .shadow-primary {
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
</style>

<?= $this->endSection() ?>