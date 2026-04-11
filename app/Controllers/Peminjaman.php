<?php

namespace App\Controllers;

// Memastikan model dipanggil dengan benar
use App\Models\AlatModel;
use App\Models\PeminjamanModel;

class Peminjaman extends BaseController
{
    protected $alatModel;
    protected $peminjamanModel;

    public function __construct()
    {
        // Inisialisasi model cukup sekali di sini
        $this->alatModel = new AlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pilih Alat untuk Dipinjam',
            // Mengambil alat yang stoknya tersedia
            'alat'  => $this->alatModel->where('stok >', 0)->findAll()
        ];
        return view('peminjaman/pilih_alat', $data);
    }

    public function proses()
    {
        $id_alat = $this->request->getPost('id_alat');
        $jumlah  = $this->request->getPost('jumlah') ?: 1;

        $alat = $this->alatModel->find($id_alat);

        if (!$alat || $alat['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Maaf, stok alat tidak mencukupi.');
        }

        // Simpan data peminjaman baru
        $this->peminjamanModel->save([
            'id_alat'       => $id_alat,
            'nama_peminjam' => $this->request->getPost('nama_peminjam'),
            'tgl_pinjam'    => $this->request->getPost('tgl_pinjam'),
            'tgl_kembali'   => $this->request->getPost('tgl_kembali'),
            'jumlah'        => $jumlah,
            'status'        => 'pending'
        ]);

        return redirect()->to('/peminjaman')->with('success', 'Permintaan berhasil dikirim!');
    }

    public function konfirmasi($id)
    {
        // Cari data peminjaman berdasarkan ID
        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Validasi agar tidak dikonfirmasi dua kali
        if (strtolower($dataPinjam['status']) !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $alat = $this->alatModel->find($dataPinjam['id_alat']);

        // Cek apakah stok masih cukup saat admin klik konfirmasi
        if (!$alat || $alat['stok'] < $dataPinjam['jumlah']) {
            return redirect()->back()->with('error', 'Gagal konfirmasi! Stok alat tidak mencukupi.');
        }

        // 1. Update status peminjaman
        $this->peminjamanModel->update($id, [
            'status'     => 'dipinjam',
            'tgl_pinjam' => date('Y-m-d H:i:s')
        ]);

        // 2. Kurangi stok alat
        $stokBaru = $alat['stok'] - $dataPinjam['jumlah'];
        $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil dikonfirmasi!');
    }

    public function detail($id)
    {
        // Pastikan join ke tabel alat untuk mendapatkan nama_alat dan kategori
        $data['peminjaman'] = $this->peminjamanModel->select('peminjaman.*, alat.nama_alat, alat.kategori')
            ->join('alat', 'alat.id = peminjaman.id_alat')
            ->where('peminjaman.id', $id)
            ->first();

        if (!$data['peminjaman']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data tidak ditemukan.");
        }

        $data['title'] = "Detail Peminjaman";
        return view('peminjaman/detail', $data);
    }

    public function pengembalian()
    {
        $data = [
            'title'      => 'Pengembalian Alat',
            // Hanya tampilkan yang statusnya 'dipinjam'
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, alat.nama_alat')
                ->join('alat', 'alat.id = peminjaman.id_alat')
                ->where('peminjaman.status', 'dipinjam')
                ->findAll()
        ];

        return view('peminjaman/pengembalian', $data);
    }
}
