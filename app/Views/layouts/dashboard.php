<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-dark">Dashboard Pengelola</h3>
            <p class="text-muted">Selamat datang di <b>PinjamDulu</b>App! Berikut adalah ringkasan sistem hari ini.</p>
            <hr>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-white-50">Total Alat</h6>
                    <h2 class="fw-bold"><?= $totalAlat ?></h2>
                    <small><i class="bi bi-box-seam me-1"></i> Unit terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-white-50">Tersedia</h6>
                    <h2 class="fw-bold"><?= $totalTersedia ?></h2>
                    <small><i class="bi bi-check-lg me-1"></i> Siap dipinjam</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-warning text-dark h-100">
                <div class="card-body">
                    <h6 class="text-black-50">Sedang Dipinjam</h6>
                    <h2 class="fw-bold"><?= $totalDipinjam ?></h2>
                    <small><i class="bi bi-clock-history me-1"></i> Perlu dipantau</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-danger text-white h-100">
                <div class="card-body">
                    <h6 class="text-white-50">Terlambat</h6>
                    <h2 class="fw-bold text-white"><?= $totalTerlambat ?></h2>
                    <small><i class="bi bi-exclamation-triangle me-1"></i> Melebihi tenggat</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold text-primary"><i class="bi bi-arrow-left-right me-2"></i>Aktivitas Peminjaman Terbaru</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Peminjam</th>
                                    <th>Alat</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($permintaanTerbaru)) : ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi saat ini.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($permintaanTerbaru as $p) : ?>
                                        <tr>
                                            <td class="ps-3 fw-medium"><?= $p['nama_peminjam'] ?></td>
                                            <td><span class="text-truncate d-inline-block" style="max-width: 150px;"><?= $p['nama_alat'] ?></span></td>
                                            <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                                            <td>
                                                <?php if ($p['status'] == 'pending') : ?>
                                                    <span class="badge rounded-pill bg-info px-3 text-white">Menunggu Approval</span>
                                                <?php elseif ($p['status'] == 'dipinjam') : ?>
                                                    <span class="badge rounded-pill bg-warning text-dark px-3">Sedang Dipakai</span>
                                                <?php else : ?>
                                                    <span class="badge rounded-pill bg-success px-3">Selesai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('/peminjaman/detail/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-center border-0 py-3">
                    <a href="<?= base_url('/peminjaman/history') ?>" class="text-decoration-none small fw-bold text-primary text-uppercase">
                        Lihat Semua Riwayat Transaksi <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary shadow-sm border-0 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahAlat">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Alat Baru
                        </button>
                        <a href="<?= base_url('/peminjaman') ?>" class="btn btn-outline-dark py-2">
                            <i class="bi bi-cart-plus me-2"></i> Buat Pinjaman Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 text-white bg-dark">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-info"></i>Kondisi Inventaris</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle text-success me-2 fs-5"></i>
                            <span>Sistem Stok Otomatis Aktif</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-shield-check text-info me-2 fs-5"></i>
                            <span>Validasi Stok Kosong Aktif</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahAlat" tabindex="-1" aria-labelledby="modalTambahAlatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTambahAlatLabel">Tambah Inventaris Alat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('/alat/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Kamera Sony A6400" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Kamera">Kamera</option>
                            <option value="Pertukangan">Pertukangan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Awal</label>
                            <select name="status" class="form-select">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Perbaikan">Perbaikan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Alat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>