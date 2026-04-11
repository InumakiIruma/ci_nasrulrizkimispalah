<?php

namespace App\Controllers;

// Tambahkan ini di atas agar Controller kenal dengan Modelnya
use App\Models\AlatModel;
use App\Models\PeminjamanModel;

class Home extends BaseController
{
    public function index(): string
    {
        // 1. Panggil Modelnya dulu
        $alatModel = new AlatModel();
        $pinjamModel = new PeminjamanModel();

        // 2. Siapkan data statis & dinamis agar Dashboard tidak kosong
        $data = [
            'title'             => 'Dashboard Pengelola',
            'totalAlat'         => $alatModel->countAll(),
            'totalTersedia'     => $alatModel->where('status', 'Tersedia')->countAllResults(),
            'totalDipinjam'     => $alatModel->where('status', 'Dipinjam')->countAllResults(),
            'totalTerlambat'    => $pinjamModel->where('tgl_kembali <', date('Y-m-d'))
                ->where('status', 'Dipinjam')
                ->countAllResults(),
            'permintaanTerbaru' => $pinjamModel->getPeminjamanLimit(5)
        ];

        // 3. Kirim variabel $data ke View (dashboard yang ada di folder layouts)
        return view('layouts/dashboard', $data);
    }
}
