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
        $this->alatModel = new AlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pilih Alat untuk Dipinjam',
            'alat'  => $this->alatModel->where('stok >', 0)->findAll()
        ];
        return view('peminjaman/pilih_alat', $data);
    }

    public function proses()
    {
        $id_alat = $this->request->getPost('id_alat');
        $jumlah  = (int) ($this->request->getPost('jumlah') ?: 1);
        $nama    = $this->request->getPost('nama_peminjam');

        $alat = $this->alatModel->find($id_alat);

        if (!$alat || $alat['stok'] <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok alat ini sudah habis.');
        }

        if ($alat['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Jumlah pinjam melebihi stok yang tersedia.');
        }

        $this->peminjamanModel->save([
            'id_alat'       => $id_alat,
            'nama_peminjam' => $nama,
            'tgl_pinjam'    => $this->request->getPost('tgl_pinjam'),
            'tgl_kembali'   => $this->request->getPost('tgl_kembali'),
            'jumlah'        => $jumlah,
            'status'        => 'pending'
        ]);

        return redirect()->to('/peminjaman')->with('success', 'Permintaan pinjam berhasil dikirim.');
    }

    public function konfirmasi($id)
    {
        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if (strtolower($dataPinjam['status']) !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses sebelumnya.');
        }

        $alat = $this->alatModel->find($dataPinjam['id_alat']);

        if (!$alat || $alat['stok'] < $dataPinjam['jumlah']) {
            return redirect()->back()->with('error', 'Gagal konfirmasi! Stok alat tidak mencukupi.');
        }

        $this->peminjamanModel->update($id, [
            'status'     => 'dipinjam',
            'tgl_pinjam' => date('Y-m-d H:i:s')
        ]);

        $stokBaru = $alat['stok'] - $dataPinjam['jumlah'];
        $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

        return redirect()->to('/peminjaman/history')->with('success', 'Peminjaman dikonfirmasi!');
    }

    public function detail($id)
    {
        $data['peminjaman'] = $this->peminjamanModel->select('peminjaman.*, alat.nama_alat, alat.kategori')
            ->join('alat', 'alat.id = peminjaman.id_alat')
            ->where('peminjaman.id', $id)
            ->first();

        if (!$data['peminjaman']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['title'] = "Detail Peminjaman";
        return view('peminjaman/detail', $data);
    }

    public function pengembalian()
    {
        $data = [
            'title'      => 'Pengembalian Alat',
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, alat.nama_alat')
                ->join('alat', 'alat.id = peminjaman.id_alat')
                ->where('peminjaman.status', 'dipinjam')
                ->findAll()
        ];

        return view('peminjaman/pengembalian', $data);
    }

    public function kembalikan($id)
    {
        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam) {
            return redirect()->to('/peminjaman/pengembalian')->with('error', 'Data tidak ditemukan.');
        }

        if (strtolower($dataPinjam['status']) !== 'dipinjam') {
            return redirect()->to('/peminjaman/pengembalian')->with('error', 'Alat ini sudah dikembalikan.');
        }

        $this->peminjamanModel->update($id, [
            'status' => 'selesai',
            'tgl_kembali' => date('Y-m-d H:i:s')
        ]);

        $alat = $this->alatModel->find($dataPinjam['id_alat']);
        if ($alat) {
            $stokBaru = $alat['stok'] + $dataPinjam['jumlah'];
            $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);
        }

        return redirect()->to('/peminjaman/pengembalian')->with('success', 'Alat berhasil dikembalikan.');
    }

    public function history()
    {
        $data = [
            'title'      => 'Riwayat Transaksi',
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, alat.nama_alat, alat.kategori')
                ->join('alat', 'alat.id = peminjaman.id_alat')
                ->orderBy('peminjaman.id', 'DESC')
                ->findAll()
        ];

        return view('peminjaman/history', $data);
    }
}
