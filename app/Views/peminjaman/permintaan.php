<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-clock-history text-warning me-2"></i> Menunggu Konfirmasi</h4>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Peminjam</th>
                        <th>Alat</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($peminjaman)) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Tidak ada permintaan baru.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($peminjaman as $row) : ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= $row['nama_peminjam'] ?></div>
                                    <small class="text-muted"><?= $row['tgl_pinjam'] ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $row['nama_alat'] ?></span></td>
                                <td class="text-center"><?= $row['jumlah'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('peminjaman/konfirmasi/' . $row['id']) ?>"
                                        class="btn btn-primary btn-sm rounded-pill px-3 me-1 btn-konfirmasi">
                                        <i class="bi bi-check-lg"></i> Setujui
                                    </a>

                                    <a href="<?= base_url('peminjaman/tolak/' . $row['id']) ?>"
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-tolak">
                                        <i class="bi bi-x-lg"></i> Tolak
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk tombol Setujui
        const konfirmasiButtons = document.querySelectorAll('.btn-konfirmasi');
        konfirmasiButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                Swal.fire({
                    title: 'Setujui Peminjaman?',
                    text: "Stok alat akan otomatis berkurang setelah disetujui.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee', // Warna biru tema kamu
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });

        // Logika untuk tombol Tolak
        const tolakButtons = document.querySelectorAll('.btn-tolak');
        tolakButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                Swal.fire({
                    title: 'Tolak Permintaan?',
                    text: "Data permintaan ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Warna merah danger
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>