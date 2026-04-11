<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'id';

    // FIX: Menambahkan 'jumlah' ke dalam daftar agar bisa dibaca di View
    protected $allowedFields = ['id_alat', 'nama_peminjam', 'tgl_pinjam', 'tgl_kembali', 'status', 'jumlah'];

    public function getPeminjamanLimit($limit)
    {
        return $this->db->table('peminjaman')
            ->select('peminjaman.*, alat.nama_alat')
            ->join('alat', 'alat.id = peminjaman.id_alat', 'left')
            ->orderBy('peminjaman.id', 'DESC')
            ->get($limit)
            ->getResultArray();
    }

    public function konfirmasi($id)
    {
        $alatModel = new \App\Models\AlatModel();
        $dataPinjam = $this->find($id);

        if (!$dataPinjam) {
            return false;
        }

        $alat = $alatModel->find($dataPinjam['id_alat']);

        if (!$alat || $alat['stok'] < ($dataPinjam['jumlah'] ?? 0)) {
            return false;
        }

        $this->update($id, [
            'status' => 'dipinjam',
            'tgl_pinjam' => date('Y-m-d H:i:s')
        ]);

        $stokBaru = $alat['stok'] - $dataPinjam['jumlah'];
        $alatModel->update($dataPinjam['id_alat'], ['stok' => $stokBaru]);

        return true;
    }
    public function getLaporan()
    {
        return $this->select('peminjaman.*, alat.nama_alat, alat.kategori')
            ->join('alat', 'alat.id = peminjaman.id_alat')
            ->orderBy('peminjaman.tgl_pinjam', 'DESC')
            ->findAll();
    }
}
