<?php

namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Kategori',
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('kategori/index', $data);
    }

    public function simpan()
    {
        $this->kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'kode_kategori' => strtoupper($this->request->getPost('kode_kategori')),
            'keterangan'    => $this->request->getPost('keterangan'),
        ]);
        return redirect()->to('/kategori');
    }

    public function hapus($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to('/kategori');
    }
}
