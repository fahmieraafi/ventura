<?php

namespace App\Controllers;

// Mengimpor library standar dari CodeIgniter 4
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController menyediakan tempat untuk memuat komponen yang digunakan oleh semua controller lainnya.
 * Controller lain (seperti Home, Barang, dll) akan meng-extend class ini.
 */
abstract class BaseController extends Controller
{
    protected $request;       // Menyimpan data request (GET/POST)
    protected $helpers = [];  // Menyimpan helper yang akan dimuat otomatis
    protected $session;       // Menyimpan instance session
    protected $db;            // Menyimpan instance database

    /**
     * Fungsi initController otomatis dijalankan oleh sistem saat controller dipanggil.
     * Di sini kita mengatur data yang harus selalu ada di setiap halaman (Global Data).
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Menjalankan inisialisasi dasar dari parent class
        parent::initController($request, $response, $logger);

        // Memuat library Session dan Database agar bisa digunakan langsung
        $this->session = \Config\Services::session();
        $this->db      = \Config\Database::connect();

        // Mengambil ID User dan Role (Admin/User) dari session yang sedang aktif
        $id_user = $this->session->get('id_user');
        $role    = $this->session->get('role');

        // Inisialisasi variabel awal untuk notifikasi
        $notif_count = 0;
        $notif_list  = [];
        $chat_unread = 0; // Menghitung pesan chat yang belum dibaca

        // --- 1. LOGIKA NOTIFIKASI TRANSAKSI (Ikon Lonceng di Navbar) ---
        if ($role == 'admin' || $role == 'Admin') {
            // Jika login sebagai ADMIN: Ambil semua transaksi baru yang belum dibaca (is_read = 0)
            $builder = $this->db->table('transaksi');
            $builder->select('transaksi.*, users.nama as nama_user');
            $builder->join('users', 'users.id_user = transaksi.id_user'); // Mengambil nama pembeli
            $builder->where('is_read', 0);
            $builder->orderBy('transaksi.created_at', 'DESC'); // Urutkan dari yang terbaru

            $notif_list  = $builder->get()->getResultArray(); // List data transaksi baru
            $notif_count = count($notif_list); // Jumlah angka di atas lonceng
        } elseif ($id_user) {
            // Jika login sebagai USER: Hitung ada berapa transaksi yang kena denda tapi belum lunas
            $notif_count = $this->db->table('transaksi')
                ->where('id_user', $id_user)
                ->where('denda >', 0)
                ->where('status_denda', 0)
                ->countAllResults();
        }

        // --- 2. LOGIKA NOTIFIKASI CHAT (Ikon Pesan Biru) ---
        if ($id_user) {
            // Hitung pesan masuk (id_penerima adalah saya) yang belum dibaca (status_baca = 0)
            $chat_unread = $this->db->table('pesan')
                ->where('id_penerima', $id_user)
                ->where('id_pengirim !=', $id_user)
                ->where('status_baca', '0')
                ->countAllResults();
        }

        // --- 3. LOGIKA NOTIFIKASI KETERLAMBATAN (Khusus Admin) ---
        $list_terlambat = [];
        $total_terlambat = 0;

        if ($role == 'admin' || $role == 'Admin') {
            $today = date('Y-m-d'); // Tanggal hari ini
            // Ambil data transaksi yang statusnya masih 'Dipinjam' tapi sudah lewat tanggal kembali
            $list_terlambat = $this->db->table('transaksi')
                ->select('transaksi.*, users.username as nama_user, barang.nama_barang')
                ->join('users', 'users.id_user = transaksi.id_user')
                ->join('barang', 'barang.id_barang = transaksi.id_barang')
                ->where('transaksi.status_transaksi', 'Dipinjam')
                ->where('transaksi.tgl_kembali <', $today) // Melewati deadline
                ->get()->getResultArray();

            $total_terlambat = count($list_terlambat); // Hitung jumlah orang yang telat
        }

        /**
         * RENDERER DATA:
         * Bagian ini SANGAT PENTING. Semua variabel di bawah dikirim secara global.
         * Ini alasannya variabel $notif_count dan $list_terlambat bisa langsung muncul di view (Navbar).
         */
        $renderer = \Config\Services::renderer();
        $renderer->setData([
            'notif_count'     => $notif_count,
            'notif_list'      => $notif_list,
            'chat_unread'     => $chat_unread,
            'list_terlambat'  => $list_terlambat,
            'total_terlambat' => $total_terlambat
        ]);
    }
}
