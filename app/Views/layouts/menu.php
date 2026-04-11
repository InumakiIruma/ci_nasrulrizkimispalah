<ul class="nav flex-column mt-3">
    <li class="nav-item mb-3">
        <a class="nav-link text-primary" href="#">
            <i class="bi bi-yelp fs-4"></i> <span class="fw-bold fs-5">Maldin17</span>App
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= (uri_string() == '' || uri_string() == '/') ? 'active bg-light' : '' ?>" href="<?= base_url('/') ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
    </li>

    <hr class="mx-3 my-2">
    <small class="text-muted mx-3 uppercase">Manajemen Alat</small>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/alat') ?>">
            <i class="bi bi-tools"></i> <span>Daftar Alat</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/kategori') ?>">
            <i class="bi bi-tags"></i> <span>Kategori Alat</span>
        </a>
    </li>

    <hr class="mx-3 my-2">
    <small class="text-muted mx-3">Transaksi</small>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/peminjaman') ?>">
            <i class="bi bi-cart-check"></i> <span>Peminjaman</span>
            <span class="badge rounded-pill bg-danger float-end">3</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/pengembalian') ?>">
            <i class="bi bi-arrow-return-left"></i> <span>Pengembalian</span>
        </a>
    </li>

    <hr class="mx-3 my-2">
    <small class="text-muted mx-3">Laporan & User</small>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/laporan') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan Bulanan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/users') ?>">
            <i class="bi bi-people"></i> <span>Manajemen User</span>
        </a>
    </li>

    <hr class="mx-3 my-2">

    <li class="nav-item">
        <a class="nav-link text-danger" href="<?= base_url('/logout') ?>">
            <i class="bi bi-box-arrow-right"></i> <span>Log Out</span>
        </a>
    </li>
</ul>