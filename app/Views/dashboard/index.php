<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-3">
            <small>Menunggu Konfirmasi</small>
            <h2 class="fw-bold"><?= $total_pending ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white p-3">
            <small>Sedang Dipinjam</small>
            <h2 class="fw-bold"><?= $total_pinjam ?></h2>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Transaksi Terakhir</h5>
        <a href="<?= base_url('peminjaman/history') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Lihat Semua Transaksi <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Peminjam</th>
                        <th>Alat</th>
                        <th>Status</th>
                        <th class="pe-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi_terakhir as $tr) : ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= $tr['nama_peminjam'] ?></td>
                            <td><?= $tr['nama_alat'] ?></td>
                            <td>
                                <?php if ($tr['status'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                <?php elseif ($tr['status'] == 'dipinjam') : ?>
                                    <span class="badge bg-primary rounded-pill">Dipinjam</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary rounded-pill">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 small text-muted"><?= date('d/m/Y', strtotime($tr['tgl_pinjam'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>