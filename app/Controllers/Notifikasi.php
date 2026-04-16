<?php

namespace App\Controllers;

class Notifikasi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_user = session()->get('id') ?? session()->get('id_user');

        $data = [
            'title' => 'Notifikasi Saya',
            'notif' => $this->db->table('notifikasi')
                ->where('id_user', $id_user)
                ->orderBy('created_at', 'DESC')
                ->get()->getResultArray()
        ];

        return view('notifikasi/index', $data);
    }

    public function read($id)
    {
        $this->db->table('notifikasi')->where('id', $id)->update(['is_read' => 1]);
        return redirect()->back();
    }

    public function hapus($id)
    {
        $this->db->table('notifikasi')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Notifikasi dihapus.');
    }
}
