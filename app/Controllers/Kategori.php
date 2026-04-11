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
        // Validasi sederhana
        if (!$this->validate([
            'nama_kategori' => 'required',
            'kode_kategori' => 'required|is_unique[kategori.kode_kategori]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Nama/Kode kategori sudah ada atau kosong.');
        }

        $this->kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'kode_kategori' => strtoupper($this->request->getPost('kode_kategori')),
            'keterangan'    => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }
    public function hapus($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to('/kategori');
    }
}
