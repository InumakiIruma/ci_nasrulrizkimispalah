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

    public function index()
    {
        $data = [
            'title' => 'Daftar User',
            'users' => $this->users->findAll()
        ];
        return view('users/index', $data);
    }

    public function create()
    {
        // PENGATURAN: Proteksi admin dihapus agar publik bisa daftar
        $data = [
            'title' => 'Buat Akun Baru'
        ];
        return view('users/create', $data);
    }

    public function store()
    {
        // PENGATURAN: Proteksi admin dihapus agar form pendaftaran bisa disimpan
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

        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png';

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function hapus($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/users')->with('error', 'Hanya Admin yang bisa menghapus user!');
        }

        $user = $this->users->find($id);
        if ($user) {
            if ($user['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $user['foto']);
            }
            $this->users->delete($id);
        }
        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }

    public function profile()
    {
        $id = session()->get('id') ?? session()->get('id_user');
        if (!$id) {
            return redirect()->to('/login')->with('error', 'Sesi berakhir, silakan login kembali.');
        }
        $data = [
            'title' => 'Edit Profil',
            'user'  => $this->users->find($id)
        ];
        return view('users/profile', $data);
    }

    public function updateProfile()
    {
        $id = session()->get('id') ?? session()->get('id_user');
        $userLama = $this->users->find($id);
        if (!$userLama) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

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

        $pass = $this->request->getPost('password');
        if ($pass) {
            $dataUpdate['password'] = password_hash((string)$pass, PASSWORD_DEFAULT);
        }

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/users', $namaFoto);

            if ($userLama['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $userLama['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $userLama['foto']);
            }
            $dataUpdate['foto'] = $namaFoto;
            session()->set('foto', $namaFoto);
        }

        $this->users->save($dataUpdate);
        session()->set('nama', $dataUpdate['nama']);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
