<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark">Notifikasi</h4>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                <?php if (empty($notif)) : ?>
                    <div class="p-5 text-center">
                        <i class="bi bi-bell-slash fs-1 text-muted"></i>
                        <p class="mt-2 text-muted">Belum ada notifikasi untuk Anda.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($notif as $n) : ?>
                        <div class="list-group-item p-3 <?= $n['is_read'] == 0 ? 'bg-light' : '' ?>" style="border-left: 4px solid <?= $n['is_read'] == 0 ? '#4361ee' : 'transparent' ?>;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="me-3">
                                    <p class="mb-1 <?= $n['is_read'] == 0 ? 'fw-bold' : '' ?> text-dark">
                                        <?= $n['pesan'] ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i> <?= date('d M Y, H:i', strtotime($n['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <?php if ($n['is_read'] == 0) : ?>
                                        <a href="<?= base_url('notifikasi/read/' . $n['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill" title="Tandai dibaca">
                                            <i class="bi bi-check2-all"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('notifikasi/hapus/' . $n['id']) ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus notifikasi ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>