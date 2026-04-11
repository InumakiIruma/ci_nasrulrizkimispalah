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

    // 1. Tampilan Utama (Daftar User) - Hanya ada SATU fungsi index
    public function index()
    {
        // Proteksi dihapus agar semua role bisa melihat daftar user
        $data = [
            'title' => 'Daftar User',
            'users' => $this->users->findAll()
        ];

        return view('users/index', $data);
    }

    // 2. Fungsi Simpan
    public function store()
    {
        // Tetap gunakan proteksi di level simpan data
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/users')->with('error', 'Hanya Admin yang bisa menambah user!');
        }

        // Validasi form
        $rules = [
            'nama'     => 'required',
            'email'    => 'required|valid_email',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'role'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        // Upload Foto
        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png';

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        // Simpan Data
        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ]);

        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan!');
    }

    // 3. Fungsi Hapus
    public function hapus($id)
    {
        // Proteksi agar user biasa tidak bisa menghapus lewat URL
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/users')->with('error', 'Hanya Admin yang bisa menghapus user!');
        }

        $user = $this->users->find($id);

        if ($user) {
            // Hapus file foto jika bukan default.png
            if ($user['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $user['foto']);
            }
            $this->users->delete($id);
        }

        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }
    public function profile()
    {
        $id = session()->get('id_user'); // Pastikan id_user ada di session saat login
        $data = [
            'title' => 'Edit Profil',
            'user'  => $this->users->find($id)
        ];
        return view('users/profile', $data);
    }

    public function updateProfile()
    {
        $id = session()->get('id_user');
        $userLama = $this->users->find($id);

        // Validasi dasar
        $rules = [
            'nama'  => 'required',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $dataUpdate = [
            'id'    => $id,
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];

        // Cek jika ganti password
        $pass = $this->request->getPost('password');
        if ($pass) {
            $dataUpdate['password'] = password_hash((string)$pass, PASSWORD_DEFAULT);
        }

        // Upload Foto jika ada
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/users', $namaFoto);

            // Hapus foto lama jika bukan default
            if ($userLama['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $userLama['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $userLama['foto']);
            }
            $dataUpdate['foto'] = $namaFoto;

            // Update session foto agar langsung berubah di menu
            session()->set('foto', $namaFoto);
        }

        $this->users->save($dataUpdate);

        // Update session nama
        session()->set('nama', $dataUpdate['nama']);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
