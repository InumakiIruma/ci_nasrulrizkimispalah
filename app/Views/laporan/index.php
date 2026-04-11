<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="bi bi-file-earmark-bar-graph text-primary me-2"></i> Laporan Bulanan</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-print-none">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    <div class="card shadow-sm border-0 mb-4 d-print-none">
        <div class="card-body">
            <form action="<?= base_url('/laporan') ?>" method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php
                        $nama_bulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        foreach ($nama_bulan as $key => $val) : ?>
                            <option value="<?= $key ?>" <?= ($bulan == $key) ? 'selected' : '' ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>" min="2020" max="2099">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="text-center mb-4 d-none d-print-block">
                <h5>LAPORAN PEMINJAMAN ALAT</h5>
                <p>Periode: <?= $nama_bulan[$bulan] . ' ' . $tahun ?></p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tgl Pinjam</th>
                            <th>Nama Peminjam</th>
                            <th>Alat</th>
                            <th>Status</th>
                            <th>Tgl Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data transaksi pada periode ini.</td>
                            </tr>
                            <?php else : $no = 1;
                            foreach ($laporan as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                                    <td><?= $row['nama_peminjam'] ?></td>
                                    <td><?= $row['nama_alat'] ?></td>
                                    <td>
                                        <span class="badge <?= ($row['status'] == 'Kembali') ? 'bg-success' : 'bg-warning' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= ($row['tgl_kembali']) ? date('d/m/Y', strtotime($row['tgl_kembali'])) : '-' ?></td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background: white;
        }

        .sidebar,
        .navbar,
        .btn,
        .d-print-none {
            display: none !important;
        }

        .container {
            max-width: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .card {
            border: none !important;
            shadow: none !important;
        }
    }
</style>

<?= $this->endSection() ?>