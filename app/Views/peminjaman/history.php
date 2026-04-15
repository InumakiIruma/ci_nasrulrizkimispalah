<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Semua Transaksi</h4>
            <p class="text-muted small mb-0">Daftar lengkap riwayat peminjaman alat.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tgl Pinjam</th>
                        <th class="pe-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($peminjaman as $row) : ?>
                        <tr>
                            <td class="ps-4"><?= $no++ ?></td>
                            <td class="fw-bold"><?= $row['nama_peminjam'] ?></td>
                            <td>
                                <div><?= $row['nama_alat'] ?></div>
                                <small class="text-muted"><?= $row['kategori'] ?></small>
                            </td>
                            <td><?= $row['jumlah'] ?> Unit</td>
                            <td>
                                <?php if ($row['status'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark rounded-pill">Menunggu</span>
                                <?php elseif ($row['status'] == 'dipinjam') : ?>
                                    <span class="badge bg-primary rounded-pill">Dipinjam</span>
                                <?php else : ?>
                                    <span class="badge bg-success rounded-pill">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></td>
                            <td class="pe-4 text-center">
                                <a href="<?= base_url('peminjaman/detail/' . $row['id']) ?>" class="btn btn-light btn-sm rounded-pill border shadow-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>