<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> PinjamDuluApp </title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: "SF Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            overflow-x: auto;
        }

        .sidebar {
            width: 280px;
            /* Lebar sidebar disesuaikan agar teks tidak terpotong */
            background-color: #ffffff;
            position: relative;
            border-right: 1px solid #f1f5f9;
        }

        .content {
            flex-grow: 1;
            padding: 15px;
            background-color: #f8f9fa;
        }

        /* Custom styling agar SweetAlert2 lebih matching dengan tema */
        .swal2-popup {
            border-radius: 20px !important;
            font-size: 0.9rem !important;
        }
    </style>
</head>

<body>
    <div id="sidebar" class="sidebar">
        <?php include(APPPATH . 'Views/layouts/menu.php'); ?>
    </div>

    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mencari semua element dengan class logout-btn
            const logoutButtons = document.querySelectorAll('.logout-btn');

            logoutButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Stop link agar tidak langsung pindah halaman
                    const url = this.getAttribute('href');

                    Swal.fire({
                        title: 'Yakin ingin keluar?',
                        text: "Sesi Anda akan diakhiri sekarang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4361ee',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Keluar!',
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
</body>

</html>