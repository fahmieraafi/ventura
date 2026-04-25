<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;
    protected $db;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
        $this->db      = \Config\Database::connect();

        $id_user = $this->session->get('id_user');
        $role    = $this->session->get('role');

        $notif_count = 0;
        $notif_list  = [];
        // Variabel khusus chat
        $chat_unread = 0;

        // --- 1. NOTIFIKASI TRANSAKSI (Lonceng Atas) ---
        if ($role == 'admin' || $role == 'Admin') {
            $builder = $this->db->table('transaksi');
            $builder->select('transaksi.*, users.nama as nama_user');
            $builder->join('users', 'users.id_user = transaksi.id_user');
            $builder->where('is_read', 0);
            $builder->orderBy('transaksi.created_at', 'DESC');

            $notif_list  = $builder->get()->getResultArray();
            $notif_count = count($notif_list);
        } elseif ($id_user) {
            $notif_count = $this->db->table('transaksi')
                ->where('id_user', $id_user)
                ->where('denda >', 0)
                ->where('status_denda', 0)
                ->countAllResults();
        }

        // --- 2. NOTIFIKASI CHAT (Ikon Biru Kanan Bawah) ---
        // Kita hitung pesan yang id_penerimanya SAYA dan id_pengirimnya BUKAN SAYA
        if ($id_user) {
            $chat_unread = $this->db->table('pesan')
                ->where('id_penerima', $id_user)
                ->where('id_pengirim !=', $id_user)
                ->where('status_baca', '0')
                ->countAllResults();
        }

        $renderer = \Config\Services::renderer();
        $renderer->setData([
            'notif_count' => $notif_count,
            'notif_list'  => $notif_list,
            'chat_unread' => $chat_unread // Gunakan variabel ini di ikon biru
        ]);


        // --- 3. NOTIFIKASI KETERLAMBATAN (Tambahkan Ini) ---
        // --- 3. NOTIFIKASI KETERLAMBATAN ---
        $list_terlambat = [];
        $total_terlambat = 0;

        if ($role == 'admin' || $role == 'Admin') {
            $today = date('Y-m-d');
            $list_terlambat = $this->db->table('transaksi')
                // Kita ambil users.username dan berikan alias 'nama_user'
                ->select('transaksi.*, users.username as nama_user, barang.nama_barang')
                ->join('users', 'users.id_user = transaksi.id_user')
                ->join('barang', 'barang.id_barang = transaksi.id_barang')
                ->where('transaksi.status_transaksi', 'Dipinjam')
                ->where('transaksi.tgl_kembali <', $today)
                ->get()->getResultArray();

            $total_terlambat = count($list_terlambat);
        }

        // Masukkan semua data ke renderer agar bisa dibaca di Navbar (View)
        $renderer = \Config\Services::renderer();
        $renderer->setData([
            'notif_count'     => $notif_count,
            'notif_list'      => $notif_list,
            'chat_unread'     => $chat_unread,
            'list_terlambat'  => $list_terlambat,   // Tambahkan ini
            'total_terlambat' => $total_terlambat    // Tambahkan ini
        ]);
    }
}
