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
            'alat'  => $model->findAll()
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
            'foto'      => 'default.jpg'
        ]);

        return redirect()->to('/alat')->with('success', 'Alat baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $model = new AlatModel();
        $data = [
            'title' => 'Edit Alat - Maldin17App',
            'alat'  => $model->find($id)
        ];

        if (!$data['alat']) {
            return redirect()->to('/alat')->with('error', 'Data alat tidak ditemukan.');
        }

        return view('alat/edit', $data);
    }

    public function update($id)
    {
        $model = new AlatModel();

        // Mengambil semua field dari form edit yang sudah kita buat
        $data = [
            'nama_alat' => $this->request->getPost('nama_alat'),
            'kategori'  => $this->request->getPost('kategori'),
            'stok'      => $this->request->getPost('stok'),
            'status'    => $this->request->getPost('status'),
        ];

        $model->update($id, $data);

        return redirect()->to('/alat')->with('success', 'Data alat berhasil diperbarui.');
    }
}
