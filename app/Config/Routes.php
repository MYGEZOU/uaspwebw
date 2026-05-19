<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------
// DEFAULT ROUTE
// --------------------------------------------------------------
$routes->get('/', 'Auth::login');

// --------------------------------------------------------------
// AUTENTIKASI
// --------------------------------------------------------------
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');

// Lupa & Reset Password
$routes->get('lupa-password', 'Auth::forgotPassword');
$routes->post('lupa-password/proses', 'Auth::prosesForgotPassword');
$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password/update', 'Auth::prosesResetPassword');

// --------------------------------------------------------------
// DASHBOARD
// --------------------------------------------------------------
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'role']);

// --------------------------------------------------------------
// AKSES ADMIN
// --------------------------------------------------------------
$routes->group('', ['filter' => 'role:Admin'], function ($routes) {
    // Akun
    $routes->get('akun', 'Akun::index');
    $routes->get('akun/tambah', 'Akun::tambah');
    $routes->post('akun/simpan', 'Akun::simpan');
    $routes->get('akun/edit/(:num)', 'Akun::edit/$1');
    $routes->post('akun/update/(:num)', 'Akun::update/$1');
    $routes->get('akun/hapus/(:num)', 'Akun::hapus/$1');

    // Turnamen Khusus Admin
    $routes->post('turnamen/simpan', 'Turnamen::simpan');
    $routes->get('turnamen/edit/(:num)', 'Turnamen::edit/$1');
    $routes->post('turnamen/update/(:num)', 'Turnamen::update/$1');
    $routes->get('turnamen/hapus/(:num)', 'Turnamen::hapus/$1');

    // Pendaftaran
    $routes->get('daftar/ubah-status/(:num)', 'Daftar::ubahStatus/$1');
    
    // Anggota (master)
    $routes->get('anggota', 'Anggota::index');
    $routes->get('anggota/tambah', 'Anggota::tambah');
    $routes->post('anggota/simpan', 'Anggota::simpan');
    $routes->get('anggota/edit/(:num)', 'Anggota::edit/$1');
    $routes->post('anggota/update/(:num)', 'Anggota::update/$1');
    $routes->get('anggota/hapus/(:num)', 'Anggota::hapus/$1');
});

// --------------------------------------------------------------
// AKSES ADMIN (Lanjutan)
// --------------------------------------------------------------
$routes->group('', ['filter' => 'role:Admin'], function ($routes) {
    // Turnamen
    $routes->get('turnamen', 'Turnamen::index');
    $routes->get('turnamen/tambah', 'Turnamen::tambah');
    
    // Tim
    $routes->get('tim', 'Tim::index');
    $routes->get('tim/detail/(:num)', 'Tim::detail/$1');
    
    // Pendaftaran
    $routes->get('daftar', 'Daftar::index');

    // Jadwal
    $routes->get('jadwal', 'Jadwal::index');
    $routes->get('jadwal/tambah', 'Jadwal::tambah');
    $routes->post('jadwal/simpan', 'Jadwal::simpan');
    $routes->get('jadwal/edit/(:num)', 'Jadwal::edit/$1');
    $routes->post('jadwal/update/(:num)', 'Jadwal::update/$1');
    $routes->get('jadwal/hapus/(:num)', 'Jadwal::hapus/$1');

    // Skor
    $routes->get('skor', 'Skor::index');
    $routes->get('skor/input/(:num)', 'Skor::input/$1');
    $routes->post('skor/simpan', 'Skor::simpan');
});

// --------------------------------------------------------------
// AKSES PESERTA
// --------------------------------------------------------------
$routes->group('', ['filter' => 'role:Peserta'], function ($routes) {
    // Turnamen & Pendaftaran
    $routes->get('turnamen/peserta', 'Turnamen::peserta');
    $routes->get('daftar/ikut/(:num)', 'Daftar::ikut/$1');
    $routes->post('daftar/proses-ikut', 'Daftar::prosesIkut');

    // Tim
    $routes->get('tim/profil', 'Tim::profil');
    $routes->get('tim/tambah', 'Tim::tambah');
    $routes->post('tim/simpan', 'Tim::simpan');
    $routes->get('tim/edit/(:num)', 'Tim::edit/$1');
    $routes->post('tim/update/(:num)', 'Tim::update/$1');
    $routes->get('tim/hapus/(:num)', 'Tim::hapus/$1');

    // Anggota tim sendiri
    $routes->get('anggota/tambah-peserta', 'Anggota::tambahPeserta');
    $routes->post('anggota/simpan-peserta', 'Anggota::simpanPeserta');
    $routes->get('anggota/edit-peserta/(:num)', 'Anggota::editPeserta/$1');
    $routes->post('anggota/update-peserta/(:num)', 'Anggota::updatePeserta/$1');
    $routes->get('anggota/hapus-peserta/(:num)', 'Anggota::hapusPeserta/$1');

    // Jadwal
    $routes->get('jadwal/peserta', 'Jadwal::peserta');
});

// --------------------------------------------------------------
// MASTER GAME
// --------------------------------------------------------------
$routes->group('game', ['filter' => 'role:Admin,AdminGame'], function($routes) {
    $routes->get('/', 'GameController::index');
    $routes->get('tambah', 'GameController::create');
    $routes->post('simpan', 'GameController::store');
    $routes->get('edit/(:num)', 'GameController::edit/$1');
    $routes->post('update/(:num)', 'GameController::update/$1');
    $routes->get('hapus/(:num)', 'GameController::delete/$1');
});