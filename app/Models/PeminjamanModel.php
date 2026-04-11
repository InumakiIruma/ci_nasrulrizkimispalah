<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_alat', 'nama_peminjam', 'tgl_pinjam', 'tgl_kembali', 'status'];

    public function getPeminjamanLimit($limit)
    {
        return $this->db->table('peminjaman')
            ->select('peminjaman.*, alat.nama_alat')
            ->join('alat', 'alat.id = peminjaman.id_alat', 'left') // 'left' agar tidak error jika alat dihapus
            ->orderBy('peminjaman.id', 'DESC')
            ->get($limit)
            ->getResultArray();
    }
}
