<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Users extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new UsersModel();
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        // 1. Validasi form (Perbaikan pada 'valid_email')
        $rules = [
            'nama'     => 'required',
            'email'    => 'required|valid_email', // <-- SUDAH DIPERBAIKI
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'role'     => 'required',
        ];

        if (!$this->validate($rules)) {
            // Menggunakan getFieldErrors agar lebih rapi atau tampilkan semua
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        // ============== 2. Upload Foto ==============
        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png'; // Beri default jika tidak upload

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        // ============== 3. Simpan Data ==============
        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ]);

        return redirect()->to('/login')->with('success', 'User berhasil ditambahkan!');
    }
}
