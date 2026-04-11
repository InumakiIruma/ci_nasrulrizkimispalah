<?php

namespace App\Controllers;

use App\Models\AlatModel;

class Alat extends BaseController
{
    public function index()
    {
        $model = new AlatModel();
        $data = [
            'title' => 'Daftar Alat - Maldin17App',
            'alat'  => $model->findAll() // Mengambil semua data alat
        ];

        return view('alat/index', $data);
    }
    public function simpan()
    {
        $model = new AlatModel();

        $model->save([
            'nama_alat' => $this->request->getPost('nama_alat'),
            'kategori'  => $this->request->getPost('kategori'),
            'stok'      => $this->request->getPost('stok'),
            'status'    => $this->request->getPost('status'),
            'foto'      => 'default.jpg' // Placeholder foto
        ]);

        return redirect()->to('/')->with('success', 'Alat baru berhasil ditambahkan ke sistem!');
    }
}
