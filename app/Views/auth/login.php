<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Maldin17 App</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            /* Background gradient yang lembut sesuai brand biru di dashboardmu */
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .login-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg login-card" style="width: 400px;">
            <div class="card-body p-5">

                <div class="text-center">
                    <div class="login-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                    <p class="text-muted mb-4">Silakan masuk ke akun Anda</p>
                </div>

                <?php if (session()->getFlashdata('error') || session()->getFlashdata('salahpw')): ?>
                    <div class="alert alert-danger border-0 small d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <?= session()->getFlashdata('error') ?: session()->getFlashdata('salahpw') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/proses-login') ?>" method="post">

                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="userInput" placeholder="Username" required>
                        <label for="userInput" class="text-muted">Username</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                        <label for="passInput" class="text-muted">Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-login mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>

                </form>

                <div class="position-relative my-4">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">ATAU</span>
                </div>

                <div class="text-center">
                    <p class="small text-muted mb-2">Belum punya akun?</p>
                    <a href="<?= base_url('users/create') ?>" class="btn btn-outline-success btn-sm w-100 py-2 rounded-3">
                        <i class="bi bi-person-plus me-1"></i> Buat Akun Baru
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>