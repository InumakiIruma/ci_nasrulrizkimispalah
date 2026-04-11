<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    // 1. Tentukan nama tabel di database
    protected $table      = 'kategori';

    // 2. Tentukan primary key tabel tersebut
    protected $primaryKey = 'id';

    // 3. DAFTARKAN KOLOM (Ini yang kamu tanyakan)
    // Hanya kolom di bawah ini yang diizinkan untuk perintah Insert dan Update
    protected $allowedFields = [
        'nama_kategori',
        'kode_kategori',
        'keterangan'
    ];

    // 4. (Opsional) Tambahkan fitur Timestamp otomatis
    // Jika di tabel database kamu ada kolom created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
