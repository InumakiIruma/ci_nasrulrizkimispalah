<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\PeminjamanModel;

class Peminjaman extends BaseController
{
    protected $alatModel;
    protected $peminjamanModel;

    public function __construct()
    {
        // Memanggil model cukup sekali di constructor
        $this->alatModel = new AlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Peminjaman Alat',
            'alat'  => $this->alatModel->where('status', 'Tersedia')->findAll()
        ];
        return view('peminjaman/index', $data);
    }

    /**
     * Fungsi proses sudah disatukan. 
     * Menghapus duplikasi agar tidak menyebabkan error "Cannot redeclare".
     */
    public function proses()
    {
        // Ambil ID Alat dari form
        $id_alat = $this->request->getPost('id_alat');

        // 1. Simpan data ke tabel peminjaman
        $this->peminjamanModel->save([
            'id_alat'       => $id_alat,
            'nama_peminjam' => $this->request->getPost('nama_peminjam'),
            'tgl_pinjam'    => $this->request->getPost('tgl_pinjam'),
            'tgl_kembali'   => $this->request->getPost('tgl_kembali'),
            'status'        => 'Dipinjam'
        ]);

        // 2. Update status alat menjadi 'Dipinjam'
        // Menggunakan $this->alatModel yang sudah didefinisikan di atas (lebih rapi)
        $this->alatModel->update($id_alat, ['status' => 'Dipinjam']);

        // 3. Redirect kembali ke halaman utama (Dashboard) dengan pesan sukses
        return redirect()->to('/')->with('success', 'Peminjaman berhasil dicatat dan stok diperbarui!');
    }
}
