<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold"><i class="bi bi-arrow-return-left text-success me-2"></i> Daftar Pengembalian Alat</h4>
            <p class="text-muted">Daftar di bawah ini adalah alat yang statusnya masih <b>Dipinjam</b>.</p>
            <hr>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Peminjam</th>
                            <th>Nama Alat</th>
                            <th>Tgl Pinjam</th>
                            <th>Tenggat Kembali</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($peminjaman)) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-check2-circle fs-1 d-block mb-2 text-success"></i>
                                    Semua alat sudah dikembalikan. Tidak ada pinjaman aktif.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php $no = 1;
                            foreach ($peminjaman as $p) : ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td class="fw-bold"><?= $p['nama_peminjam'] ?></td>
                                    <td><?= $p['nama_alat'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                                    <td><span class="text-danger fw-medium"><?= date('d/m/Y', strtotime($p['tgl_kembali'])) ?></span></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('peminjaman/kembalikan/' . $p['id']) ?>"
                                            class="btn btn-success btn-sm px-3 shadow-sm"
                                            onclick="return confirm('Proses pengembalian alat ini?')">
                                            Proses Kembali
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