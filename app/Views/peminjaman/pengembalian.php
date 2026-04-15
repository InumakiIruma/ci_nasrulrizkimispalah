<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold"><i class="bi bi-arrow-return-left text-success me-2"></i> Daftar Pengembalian Alat</h4>
            <p class="text-muted">Daftar di bawah ini adalah alat yang statusnya masih <b>Dipinjam</b>.</p>
            <hr>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">No</th>
                            <th>Nama Peminjam</th>
                            <th>Alat</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($peminjaman)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-box2-heart fs-1 d-block mb-3 opacity-50"></i>
                                    <span class="fw-medium">Semua alat sudah dikembalikan. Tidak ada pinjaman aktif.</span>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php $no = 1;
                            foreach ($peminjaman as $row) : ?>
                                <tr>
                                    <td class="ps-4"><?= $no++ ?></td>
                                    <td class="fw-bold text-dark"><?= $row['nama_peminjam'] ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal py-2 px-3">
                                            <?= $row['nama_alat'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-secondary px-3"><?= $row['jumlah'] ?> Unit</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="<?= base_url('peminjaman/kembalikan/' . $row['id']) ?>"
                                            class="btn btn-success btn-sm rounded-pill px-4 shadow-sm"
                                            onclick="return confirm('Apakah Anda yakin alat ini sudah dikembalikan?')">
                                            <i class="bi bi-check2-circle me-1"></i> Kembalikan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>