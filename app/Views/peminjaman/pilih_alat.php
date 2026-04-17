<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4 pb-5">
    <div class="row mb-4 align-items-end">
        <div class="col-lg-5">
            <h3 class="fw-bold text-dark"><i class="bi bi-cart-plus me-2 text-primary"></i>Pilih Alat untuk Dipinjam</h3>
            <p class="text-muted mb-0">Katalog alat tersedia. Silakan pilih item yang ingin Anda pinjam.</p>
        </div>
        <div class="col-lg-7 mt-3 mt-lg-0">
            <div class="row g-2">
                <div class="col-md-5">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-filter text-primary"></i>
                        </span>
                        <select id="categoryFilter" class="form-select border-0 py-2" style="box-shadow: none; cursor: pointer;">
                            <option value="">Semua Kategori</option>
                            <?php
                            // Mengambil daftar kategori unik dari array alat
                            $categories = array_unique(array_column($alat, 'kategori'));
                            sort($categories);
                            foreach ($categories as $cat) :
                            ?>
                                <option value="<?= strtolower($cat); ?>"><?= $cat; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-0 py-2" placeholder="Cari nama alat...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row" id="alatContainer">
        <?php foreach ($alat as $a): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4 item-alat">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover">
                    <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 180px;">
                        <i class="bi bi-tools text-muted opacity-25" style="font-size: 4rem;"></i>

                        <?php if ($a['stok'] <= 0) : ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center" style="z-index: 1;">
                                <span class="badge bg-danger px-3 py-2 rounded-pill shadow">Stok Habis</span>
                            </div>
                        <?php endif; ?>

                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-white text-dark shadow-sm opacity-75">#ALT-<?= str_pad($a['id'], 3, '0', STR_PAD_LEFT); ?></span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill small fw-bold kategori-alat">
                                <?= $a['kategori']; ?>
                            </span>
                            <small class="<?= ($a['stok'] > 0) ? 'text-muted' : 'text-danger fw-bold' ?>">
                                <i class="bi bi-box-seam me-1"></i> Stok: <?= $a['stok']; ?>
                            </small>
                        </div>
                        <h5 class="card-title fw-bold mb-1 text-dark text-truncate nama-alat"><?= $a['nama_alat']; ?></h5>
                        <p class="text-muted small mb-0">Klik tombol di bawah untuk memproses peminjaman.</p>
                    </div>

                    <div class="card-footer bg-white border-0 px-4 pb-4">
                        <button class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm transition-base"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPinjam"
                            onclick="setAlat('<?= $a['id']; ?>', '<?= $a['nama_alat']; ?>', '<?= $a['stok']; ?>')"
                            <?= ($a['stok'] <= 0) ? 'disabled' : '' ?>>
                            <i class="bi bi-plus-circle me-2"></i> Pinjam Alat
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="noResults" class="text-center py-5 d-none">
        <i class="bi bi-search-heart fs-1 text-muted"></i>
        <p class="mt-2 text-muted">Maaf, alat yang Anda cari tidak ditemukan.</p>
    </div>
</div>

<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Formulir Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('/peminjaman/proses') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_alat" id="id_alat">

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Alat yang Dipilih</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-box text-primary"></i></span>
                            <input type="text" id="nama_alat_display" class="form-control bg-light border-0 fw-bold" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Nama Peminjam</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama_peminjam" class="form-control border-start-0 ps-0"
                                value="<?= session()->get('nama') ?>" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6 mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">Estimasi Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Jumlah Item</label>
                        <div class="input-group" style="max-width: 170px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-hash"></i></span>
                            <input type="number" name="jumlah" id="jumlah_input" class="form-control border-start-0" value="1" min="1" required>
                        </div>
                        <small class="text-muted mt-1 d-block" id="stok_info"></small>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                        Konfirmasi Pinjam <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const items = document.querySelectorAll('.item-alat');
    const noResults = document.getElementById('noResults');

    // FUNGSI FILTER GABUNGAN (SEARCH & CATEGORY)
    function filterAlat() {
        const keyword = searchInput.value.toLowerCase();
        const selectedCat = categoryFilter.value.toLowerCase();
        let hasVisibleItems = false;

        items.forEach(item => {
            const nama = item.querySelector('.nama-alat').textContent.toLowerCase();
            const kategori = item.querySelector('.kategori-alat').textContent.toLowerCase().trim();

            const matchSearch = nama.includes(keyword);
            const matchCategory = selectedCat === "" || kategori === selectedCat;

            if (matchSearch && matchCategory) {
                item.style.display = 'block';
                hasVisibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (hasVisibleItems) {
            noResults.classList.add('d-none');
        } else {
            noResults.classList.remove('d-none');
        }
    }

    // Listener untuk Input & Dropdown
    searchInput.addEventListener('input', filterAlat);
    categoryFilter.addEventListener('change', filterAlat);

    // FUNGSI MODAL
    function setAlat(id, nama, stok) {
        document.getElementById('id_alat').value = id;
        document.getElementById('nama_alat_display').value = nama;
        const inputJumlah = document.getElementById('jumlah_input');
        inputJumlah.max = stok;
        inputJumlah.value = 1;
        document.getElementById('stok_info').innerText = "* Maksimal pinjam: " + stok + " item";
    }
</script>

<style>
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
    }

    .bg-soft-primary {
        background-color: #eef2ff;
        color: #4361ee;
    }

    .shadow-primary {
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
    }

    .transition-base {
        transition: all 0.2s ease;
    }

    .btn:active {
        transform: scale(0.96);
    }

    /* Perbaikan tampilan select agar tidak kaku */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%234361ee' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }
</style>

<?= $this->endSection() ?>