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
                            <th class="d-print-none text-center">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)) : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi pada periode ini.</td>
                            </tr>
                            <?php else : $no = 1;
                            foreach ($laporan as $row) :
                                // Format pesan WhatsApp
                                $wa_text = "Laporan Peminjaman Alat:%0A- Nama: " . $row['nama_peminjam'] . "%0A- Alat: " . $row['nama_alat'] . "%0A- Status: " . $row['status'];
                            ?>
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
                                    <td class="d-print-none text-center">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden" style="border: 1px solid #dee2e6;">
                                            <a href="https://api.whatsapp.com/send?text=<?= $wa_text ?>" target="_blank" class="btn btn-white btn-sm border-0 px-2" title="Share ke WhatsApp">
                                                <i class="bi bi-whatsapp text-success"></i>
                                            </a>
                                            <button onclick="copyShareLink('<?= $row['nama_peminjam'] ?>', '<?= $row['nama_alat'] ?>')" class="btn btn-white btn-sm border-0 px-2 border-start" title="Salin untuk Instagram">
                                                <i class="bi bi-instagram text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function copyShareLink(peminjam, alat) {
        const textToCopy = `Laporan Peminjaman: ${peminjam} meminjam ${alat}. Cek detailnya di aplikasi!`;
        const tempInput = document.createElement("input");
        tempInput.value = textToCopy;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

        // Notifikasi sederhana (Bisa diganti Swal jika sudah ada SweetAlert2 di layout)
        alert('Teks laporan berhasil disalin! Silakan paste di Instagram.');
    }
</script>

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

    /* Style tambahan untuk tombol share agar senada */
    .btn-white {
        background: #fff;
        transition: background 0.2s;
    }

    .btn-white:hover {
        background: #f8f9fa;
    }
</style>

<?= $this->endSection() ?>