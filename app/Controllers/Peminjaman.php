<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\PeminjamanModel;

class Peminjaman extends BaseController
{
    protected $alatModel;
    protected $peminjamanModel;
    protected $db; // Tambahan properti DB

    public function __construct()
    {
        $this->db = \Config\Database::connect(); // Inisialisasi DB
        $this->alatModel = new AlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    /**
     * FUNGSI TAMBAHAN: Simpan ke tabel notifikasi
     */
    private function simpanNotif($id_user, $pesan)
    {
        $this->db->table('notifikasi')->insert([
            'id_user' => $id_user,
            'pesan'   => $pesan,
            'is_read' => 0
        ]);
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

        // Ambil ID User dari Session
        $id_user_peminjam = session()->get('id') ?? session()->get('id_user');

        $dataSimpan = [
            'id_alat'       => $id_alat,
            'id_user'       => $id_user_peminjam, // Pastikan kolom id_user ada di tabel peminjaman
            'nama_peminjam' => $nama,
            'tgl_pinjam'    => $this->request->getPost('tgl_pinjam'),
            'tgl_kembali'   => $this->request->getPost('tgl_kembali'),
            'jumlah'        => $jumlah,
            'status'        => 'pending'
        ];

        if ($this->peminjamanModel->save($dataSimpan)) {
            $namaAlat = $alat['nama_alat'];

            // NOTIF UNTUK USER (Peminjam)
            if ($id_user_peminjam) {
                $this->simpanNotif($id_user_peminjam, "Anda telah mengajukan peminjaman alat: $namaAlat. Silakan tunggu konfirmasi admin.");
            }

            // NOTIF UNTUK SEMUA ADMIN
            $adminList = $this->db->table('users')->where('role', 'admin')->get()->getResultArray();
            foreach ($adminList as $admin) {
                $this->simpanNotif($admin['id'], "Permintaan Baru: $nama ingin meminjam $namaAlat ($jumlah unit).");
            }

            return redirect()->to('/peminjaman')
                ->with('success', 'Peminjaman alat berhasil diajukan! Admin telah dinotifikasi.');
        }

        return redirect()->back()->with('error', 'Gagal mengajukan peminjaman.');
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
        $this->db->transStart();

        $dataPinjam = $this->peminjamanModel->find($id);

        if (!$dataPinjam || strtolower($dataPinjam['status']) !== 'pending') {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        $alat = $this->alatModel->find($dataPinjam['id_alat']);

        if ($alat['stok'] >= $dataPinjam['jumlah']) {
            $this->peminjamanModel->update($id, [
                'status'     => 'dipinjam',
                'tgl_pinjam' => date('Y-m-d H:i:s')
            ]);

            $stokBaru = $alat['stok'] - $dataPinjam['jumlah'];
            $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

            // NOTIF UNTUK USER: Bahwa pinjaman disetujui
            if (!empty($dataPinjam['id_user'])) {
                $this->simpanNotif($dataPinjam['id_user'], "Peminjaman alat " . $alat['nama_alat'] . " telah DISETUJUI. Silakan ambil barang.");
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Gagal memproses data.');
            }

            return redirect()->to('/peminjaman/permintaan')->with('success', 'Peminjaman disetujui.');
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
            return redirect()->to('/peminjaman/pengembalian')->with('error', 'Data tidak ditemukan.');
        }

        $this->peminjamanModel->update($id, [
            'status'      => 'selesai',
            'tgl_kembali' => date('Y-m-d H:i:s')
        ]);

        $alat = $this->alatModel->find($dataPinjam['id_alat']);
        if ($alat) {
            $stokBaru = $alat['stok'] + $dataPinjam['jumlah'];
            $this->alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

            // NOTIF UNTUK USER: Bahwa barang sudah kembali
            if (!empty($dataPinjam['id_user'])) {
                $this->simpanNotif($dataPinjam['id_user'], "Pengembalian alat " . $alat['nama_alat'] . " telah diterima. Terima kasih!");
            }
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
            $alat = $this->alatModel->find($dataPinjam['id_alat']);

            // NOTIF UNTUK USER: Bahwa pinjaman ditolak
            if (!empty($dataPinjam['id_user'])) {
                $this->simpanNotif($dataPinjam['id_user'], "Maaf, permintaan pinjam " . ($alat['nama_alat'] ?? 'alat') . " DITOLAK.");
            }

            $this->peminjamanModel->delete($id);
            return redirect()->to('/peminjaman/permintaan')->with('success', 'Permintaan telah ditolak.');
        }

        return redirect()->back()->with('error', 'Gagal memproses penolakan.');
    }
}
