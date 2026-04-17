<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Inventaris Alat</h4>
        <a href="<?= base_url('alat/tambah') ?>" class="btn btn-primary btn-sm">+ Tambah Alat</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($alat as $item): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $item['nama_alat']; ?></td>
                            <td><?= $item['kategori']; ?></td>
                            <td><?= $item['stok']; ?></td>
                            <td>
                                <span class="badge bg-<?= ($item['status'] == 'Tersedia') ? 'success' : 'warning' ?>">
                                    <?= $item['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('alat/edit/' . $item['id']); ?>" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-pencil-square"></i> Edit
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