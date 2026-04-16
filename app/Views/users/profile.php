<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Pengaturan Profil</h5>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success border-0 shadow-sm mb-3">
                            <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/profile/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="text-center mb-4">
                            <?php
                            $fotoSekarang = (isset($user['foto']) && $user['foto'] != '') ? $user['foto'] : session()->get('foto');
                            if (!$fotoSekarang) $fotoSekarang = 'default.png';
                            ?>
                            <img src="<?= base_url('uploads/users/' . $fotoSekarang) ?>"
                                class="rounded-circle img-thumbnail shadow-sm mb-3"
                                width="120" height="120" style="object-fit: cover; border: 3px solid #fff;">

                            <div>
                                <label for="foto" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-camera me-1"></i> Ganti Foto
                                </label>
                                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                                <p class="text-muted small mt-2">Format: JPG, PNG (Max 2MB)</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control rounded-3"
                                    value="<?= esc($user['nama'] ?? session()->get('nama')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control rounded-3"
                                    value="<?= esc($user['email'] ?? session()->get('email')) ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control rounded-3"
                                    placeholder="Kosongkan jika tidak ingin mengganti password">
                                <div class="form-text small text-muted">Minimal 4 karakter jika ingin mengganti.</div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="<?= base_url('/') ?>" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm rounded-pill">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('foto').onchange = function(evt) {
        const [file] = this.files
        if (file) {
            document.querySelector('.img-thumbnail').src = URL.createObjectURL(file)
        }
    }
</script>

<?= $this->endSection() ?>