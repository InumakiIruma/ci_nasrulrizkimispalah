<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;

class Laporan extends BaseController
{
    protected $peminjamanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        // 1. Ambil input filter dari URL (jika ada)
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        // 2. Mulai query dengan JOIN agar nama_alat bisa tampil
        $query = $this->peminjamanModel->select('peminjaman.*, alat.nama_alat')
            ->join('alat', 'alat.id = peminjaman.id_alat');

        // 3. Logika Filter: Jika user memilih bulan dan tahun
        if ($bulan && $tahun) {
            $query->where('MONTH(peminjaman.tgl_pinjam)', $bulan)
                ->where('YEAR(peminjaman.tgl_pinjam)', $tahun);
        }

        $data = [
            'title'   => 'Laporan Peminjaman',
            'bulan'   => $bulan ?? date('m'), // Untuk menjaga nilai di form filter
            'tahun'   => $tahun ?? date('Y'),
            'laporan' => $query->findAll()    // Eksekusi query
        ];

        return view('laporan/index', $data);
    }
}
