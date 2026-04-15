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
    $routes->get('/', 'Peminjaman::index'); // URL: /peminjaman
    $routes->post('proses', 'Peminjaman::proses'); // URL: /peminjaman/proses
    $routes->get('log', 'Peminjaman::log');

    // --- FIX DI SINI ---
    // Route ini sekarang bisa diakses di /peminjaman/detail/1 dan /peminjaman/konfirmasi/1
    $routes->get('detail/(:num)', 'Peminjaman::detail/$1');
    $routes->get('konfirmasi/(:num)', 'Peminjaman::konfirmasi/$1');
    $routes->get('kembalikan/(:num)', 'Peminjaman::kembalikan/$1');
});

// Route Pengembalian
$routes->get('/pengembalian', 'Peminjaman::pengembalian', $authFilter);

// --- 8. Laporan Bulanan ---
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
$routes->get('peminjaman/history', 'Peminjaman::history');


$routes->get('peminjaman/pengembalian', 'Peminjaman::pengembalian');
$routes->get('peminjaman/kembalikan/(:num)', 'Peminjaman::kembalikan/$1');
$routes->get('peminjaman/history', 'Peminjaman::history');
