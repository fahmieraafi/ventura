<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- VARIABEL FILTER ---
// Menyimpan pengaturan keamanan ke dalam variabel agar kode rute di bawah lebih rapi dan mudah dibaca
$authFilter    = ['filter' => 'auth'];              // Harus login
$adminFilter   = ['filter' => 'role:admin'];         // Harus login sebagai Admin
$userFilter    = ['filter' => 'role:user'];          // Harus login sebagai User biasa
$allRoleFilter = ['filter' => 'role:admin,user'];    // Harus login (bebas Admin atau User)

// --- PUBLIC ROUTES ---
// Halaman pendaftaran yang bisa diakses siapa saja tanpa perlu login
$routes->get('/users/create', 'Users::create');      // Menampilkan form daftar
$routes->post('/users/store', 'Users::store');       // Memproses data pendaftaran

// --- AUTHENTICATION ---
// Rute untuk proses masuk dan keluar sistem
$routes->get('login', 'Auth::login');                // Menampilkan halaman login
$routes->post('proses-login', 'Auth::prosesLogin');  // Memproses verifikasi username & password
$routes->get('logout', 'Auth::logout');              // Menghapus session dan keluar

// --- DASHBOARD ---
// Halaman utama setelah user berhasil masuk
$routes->get('/', 'Home::index', $authFilter);       // Domain utama (/) diarahkan ke dashboard
$routes->get('/dashboard', 'Home::index', $authFilter); // URL /dashboard

// --- MANAGEMENT USERS ---
// Pengaturan akun pengguna
$routes->get('/users', 'Users::index', $allRoleFilter);           // Lihat daftar user (Admin/User)
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRoleFilter); // Form edit berdasarkan ID angka
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRoleFilter); // Proses simpan perubahan
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $adminFilter); // Hanya admin yang bisa hapus user

// --- DATA BARANG ---
$routes->get('barang', 'Barang::index');             // Katalog barang (Public)

// --- FITUR BARU: DETAIL BARANG & TRACKER VIEWS ---
$routes->get('barang/(:num)', 'Barang::detail/$1');  // Melihat detail satu barang berdasarkan ID

// Rute manajemen stok barang (Hanya untuk Admin)
$routes->get('barang/create', 'Barang::create', $adminFilter);    // Form tambah barang
$routes->post('barang/store', 'Barang::store', $adminFilter);      // Proses simpan barang baru
$routes->get('barang/edit/(:num)', 'Barang::edit/$1', $adminFilter); // Form edit barang
$routes->post('barang/update/(:num)', 'Barang::update/$1', $adminFilter); // Proses update barang
$routes->get('barang/delete/(:num)', 'Barang::delete/$1', $adminFilter); // Hapus barang
$routes->post('barang/hapusFotoSatuan', 'Barang::hapusFotoSatuan', $adminFilter); // Hapus salah satu foto saja

// --- FITUR WISHLIST (SIMPAN BARANG) ---
$routes->get('wishlist', 'Wishlist::index', $allRoleFilter);      // Lihat daftar wishlist saya
$routes->get('wishlist/tambah/(:num)', 'Wishlist::tambah/$1', $allRoleFilter); // Simpan/Batal suka
$routes->get('wishlist/hapus/(:num)', 'Wishlist::hapus/$1', $allRoleFilter); // Hapus dari daftar wishlist

// --- FITUR EXPLORE (INFO GUNUNG) ---
// Informasi gunung yang bisa dilihat oleh semua user yang sudah login
$routes->get('gunung', 'Explore::index', $allRoleFilter);
$routes->get('gunung/detail/(:num)', 'Explore::detail/$1', $allRoleFilter);

// Manajemen data gunung (Hanya Admin)
$routes->get('gunung/create', 'Explore::create', $adminFilter);
$routes->post('gunung/tambah', 'Explore::tambah', $adminFilter);
$routes->get('gunung/edit/(:num)', 'Explore::edit/$1', $adminFilter);
$routes->post('gunung/update/(:num)', 'Explore::update/$1', $adminFilter);
$routes->get('gunung/delete/(:num)', 'Explore::delete/$1', $adminFilter);

// --- FITUR TRANSAKSI USER ---
// Alur penyewaan barang dari sisi pelanggan
$routes->get('riwayat', 'Transaksi::index', $userFilter);         // Lihat riwayat sewa saya
$routes->post('transaksi/simpan', 'Transaksi::simpan', $userFilter); // Proses checkout/booking
$routes->get('transaksi/hapus_riwayat/(:num)', 'Transaksi::hapus_riwayat/$1', $userFilter); // Hapus catatan
$routes->get('transaksi/batal/(:num)', 'Transaksi::batal/$1', $userFilter); // Batalkan sewa sebelum dikonfirmasi
// --- FITUR TRANSAKSI ADMIN ---
// Pengelompokan rute admin agar URL diawali dengan /admin/...
$routes->group('admin', $adminFilter, function ($routes) {

    $routes->get('transaksi', 'Transaksi::kelola'); // Halaman utama manajemen sewa
    // Proses konfirmasi dan status
    $routes->get('transaksi/konfirmasi_bayar/(:num)', 'Transaksi::konfirmasi_bayar/$1'); // Validasi bukti bayar
    $routes->get('transaksi/updateStatus/(:num)/(:any)', 'Transaksi::updateStatus/$1/$2'); // Ubah status (Dipinjam/Selesai)
    $routes->get('transaksi/lunaskan_denda/(:num)', 'Transaksi::lunaskan_denda/$1'); // Tandai denda lunas

    // Edit dan Hapus data transaksi
    $routes->get('transaksi/edit/(:num)', 'Transaksi::edit/$1');
    $routes->post('transaksi/update/(:num)', 'Transaksi::update/$1');
    $routes->get('transaksi/delete/(:num)', 'Transaksi::delete/$1');

    // Utilitas Admin
    $routes->get('transaksi/markAsRead/(:num)', 'Transaksi::markAsRead/$1'); // Hilangkan notifikasi pesanan baru
    $routes->get('transaksi/hitungDenda/(:num)', 'Transaksi::hitungDenda/$1'); // Hitung ulang denda manual
});

// Backup Database: Mengunduh file SQL cadangan
$routes->get('/backup', 'Backup::database');

// --- FITUR AI ASSISTANT ---
$routes->post('chat/tanyaAi', 'Chat::tanyaAi'); // Mengirim pertanyaan ke chatbot AI

// --- FITUR PESAN / CHAT ANTAR USER ---
// Sistem komunikasi internal aplikasi
$routes->get('pesan', 'Pesan::index', $allRoleFilter);
$routes->get('pesan/index/(:num)', 'Pesan::index/$1', $allRoleFilter); // Chat dengan orang tertentu
$routes->post('pesan/kirim', 'Pesan::kirim', $allRoleFilter);         // Proses kirim chat
$routes->get('pesan/gambar/(:any)', 'Pesan::gambar/$1', $allRoleFilter); // Menampilkan lampiran chat
$routes->get('pesan/countUnread', 'Pesan::countUnread', $allRoleFilter); // Cek ada berapa pesan masuk
$routes->post('pesan/hapus/(:num)', 'Pesan::hapus/$1');               // Hapus pesan

// Integrasi Real-time Chat (AJAX)
$routes->get('pesan/countUnread', 'Pesan::countUnread'); // (Duplikasi rute untuk keamanan)
$routes->post('pesan/baca/(:any)', 'Pesan::baca/$1');    // Menandai pesan sudah dibaca
$routes->get('pesan/cek_baru/(:any)', 'Pesan::cek_baru/$1'); // Cek pesan baru tanpa refresh halaman
