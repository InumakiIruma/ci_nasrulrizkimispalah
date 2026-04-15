<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- 1. Variabel Filter ---
$authFilter = ['filter' => 'auth'];

// --- 2. Auth (Login/Logout) ---
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// --- 3. Halaman Utama / Dashboard ---
$routes->get('/', 'Home::index', $authFilter);
$routes->get('/dashboard', 'Home::index', $authFilter);

// --- 4. Profile ---
$routes->get('/profile', 'Users::profile', $authFilter);
$routes->post('/profile/update', 'Users::updateProfile', $authFilter);

// --- 5. Manajemen Alat ---
$routes->group('alat', $authFilter, function ($routes) {
    $routes->get('/', 'Alat::index');
    $routes->post('simpan', 'Alat::simpan');
});

// --- 6. Kategori Alat ---
$routes->group('kategori', $authFilter, function ($routes) {
    $routes->get('/', 'Kategori::index');
    $routes->post('simpan', 'Kategori::simpan');
    $routes->get('hapus/(:num)', 'Kategori::hapus/$1');
});

// --- 7. Transaksi (Peminjaman & Pengembalian) ---
$routes->group('peminjaman', $authFilter, function ($routes) {
    // Alur Utama Peminjaman
    $routes->get('/', 'Peminjaman::index');
    $routes->post('proses', 'Peminjaman::proses');
    $routes->get('permintaan', 'Peminjaman::permintaan');
    $routes->get('konfirmasi/(:num)', 'Peminjaman::konfirmasi/$1');

    // --- Tambahan ( Aksi tolak peminjaman ) ------
    $routes->get('tolak/(:num)', 'Peminjaman::tolak/$1');

    // Alur Pengembalian
    $routes->get('pengembalian', 'Peminjaman::pengembalian');
    $routes->get('kembalikan/(:num)', 'Peminjaman::kembalikan/$1');

    // Informasi & Riwayat
    $routes->get('history', 'Peminjaman::history');
    $routes->get('detail/(:num)', 'Peminjaman::detail/$1');
    $routes->get('log', 'Peminjaman::log');
});

// Route Alias (Menjaga agar /pengembalian tanpa prefix tetap jalan)
$routes->get('/pengembalian', 'Peminjaman::pengembalian', $authFilter);

// --- 8. Laporan ---
$routes->group('laporan', $authFilter, function ($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->get('filter', 'Laporan::index');
});

// --- 9. Manajemen User ---
$routes->group('users', $authFilter, function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('store', 'Users::store');
    $routes->get('hapus/(:num)', 'Users::hapus/$1');
});
