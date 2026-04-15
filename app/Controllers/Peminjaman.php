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

    /**
     * 1. Halaman Pilih Alat (Sisi User)
     */
    public function index()
    {
        $data = [
            'title' => 'Pilih Alat untuk Dipinjam',
            'alat'  => $this->alatModel->where('stok >', 0)->findAll()
        ];
        return view('peminjaman/pilih_alat', $data);
    }

    /**
     * 2. Proses Simpan Pinjaman Awal (Status: Pending)
     */
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

    /**
     * 3. Halaman Daftar Permintaan Pinjam (Sisi Admin)
     */
    public function permintaan()
    {
        $data = [
            'title'      => 'Daftar Permintaan Pinjam',
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, alat.nama_alat')
                ->join('alat', 'alat.id = peminjaman.id_alat')
                ->where('peminjaman.status', 'pending')
                ->findAll()
        ];

        return view('peminjaman/permintaan', $data);
    }

    /**
     * 4. Proses Konfirmasi Admin (Update Status: Dipinjam & Kurangi Stok)
     */
    public function konfirmasi($id)
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Database Transaction agar data aman

        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam || strtolower($dataPinjam['status']) !== 'pending') {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        $alat = $this->alatModel->find($dataPinjam['id_alat']);

        if ($alat['stok'] >= $dataPinjam['jumlah']) {
            // A. Update status peminjaman
            $this->peminjamanModel->update($id, [
                'status'     => 'dipinjam',
                'tgl_pinjam' => date('Y-m-d H:i:s')
            ]);

            // B. Kurangi stok alat
            $stokBaru = $alat['stok'] - $dataPinjam['jumlah'];
            $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Gagal memproses data.');
            }

            return redirect()->to('/peminjaman/permintaan')->with('success', 'Peminjaman disetujui, stok berkurang.');
        } else {
            return redirect()->back()->with('error', 'Stok alat tidak mencukupi!');
        }
    }

    /**
     * 5. Halaman Daftar Pengembalian (Status: Dipinjam)
     */
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

    /**
     * 6. Proses Pengembalian (Update Status: Selesai & Tambah Stok)
     */
    public function kembalikan($id)
    {
        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam || strtolower($dataPinjam['status']) !== 'dipinjam') {
            return redirect()->to('/peminjaman/pengembalian')->with('error', 'Data tidak ditemukan atau sudah dikembalikan.');
        }

        // A. Update status peminjaman
        $this->peminjamanModel->update($id, [
            'status'      => 'selesai',
            'tgl_kembali' => date('Y-m-d H:i:s')
        ]);

        // B. Tambahkan kembali stok alat
        $alat = $this->alatModel->find($dataPinjam['id_alat']);
        if ($alat) {
            $stokBaru = $alat['stok'] + $dataPinjam['jumlah'];
            $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);
        }

        return redirect()->to('/peminjaman/pengembalian')->with('success', 'Alat berhasil dikembalikan.');
    }

    /**
     * 7. Halaman Riwayat & Detail
     */
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
    public function tolak($id)
    {
        $dataPinjam = $this->peminjamanModel->find($id);

        if ($dataPinjam && $dataPinjam['status'] == 'pending') {
            // Opsi 1: Langsung hapus datanya
            $this->peminjamanModel->delete($id);

            return redirect()->to('/peminjaman/permintaan')->with('success', 'Permintaan peminjaman telah ditolak.');
        }

        return redirect()->back()->with('error', 'Gagal memproses penolakan.');
    }
}
