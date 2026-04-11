<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Variabel Filter
$authFilter = ['filter' => 'auth'];

// Variabel Role
$admin     = ['filter' => 'role:admin'];
$petugas     = ['filter' => 'role:petugas'];
$anggota     = ['filter' => 'role:anggota'];
$allRole   = ['filter' => 'role:admin, petugas, anggota'];

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// Halaman utama
$routes->get('/', 'Home::index', $authFilter);
$routes->get('/dashboard', 'Home::index', $authFilter);

// Daftar alat
$routes->get('/alat', 'Alat::index');

// Kategori
$routes->get('/kategori', 'Kategori::index');
$routes->post('/kategori/simpan', 'Kategori::simpan');
$routes->get('/kategori/hapus/(:num)', 'Kategori::hapus/$1');

// Route untuk halaman utama peminjaman (Tampilan Card)
$routes->get('/peminjaman', 'Peminjaman::index');

// Route untuk memproses form dari modal
$routes->post('/peminjaman/proses', 'Peminjaman::proses');

// Route untuk melihat daftar yang sedang dipinjam (Log)
$routes->get('/peminjaman/log', 'Peminjaman::log');

// Jika ingin Dashboard muncul saat buka domain utama
$routes->get('/', 'Admin::index');

// Atau jika ingin diakses via url /admin
$routes->get('/admin', 'Admin::index');

// Simpan
$routes->post('/alat/simpan', 'Alat::simpan');

// Tambah user
$routes->get('/users/create', 'Users::create'); // form tambah user
$routes->post('/users/store', 'Users::store'); // aksi simpan user
