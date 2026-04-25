<?php

namespace App\Models;

// Mengambil class Model dasar dari CodeIgniter 4
use CodeIgniter\Model;

class PesanModel extends Model
{
    // Menentukan nama tabel di database
    protected $table            = 'pesan';
    // Menentukan kunci utama tabel
    protected $primaryKey       = 'id_pesan';
    // Memberitahu bahwa ID akan bertambah otomatis (A_I)
    protected $useAutoIncrement = true;
    // Mengembalikan data hasil query dalam bentuk Array
    protected $returnType       = 'array';
    // Mengaktifkan perlindungan field agar tidak sembarang kolom bisa diisi
    protected $protectFields    = true;

    /**
     * ALLOWED FIELDS
     * Daftar kolom yang diizinkan untuk diisi saat proses insert atau update.
     */
    protected $allowedFields    = [
        'id_pengirim',   // ID user yang mengirim pesan
        'id_penerima',   // ID user/admin yang menerima pesan
        'isi_pesan',     // Teks pesan
        'file_lampiran', // Nama file jika mengirim gambar/audio
        'tipe_pesan',    // Jenis pesan (text, image, audio)
        'status_baca'    // Status (0: Belum dibaca, 1: Sudah dibaca)
    ];

    /**
     * DATES & TIMESTAMPS
     * Fitur otomatis untuk mencatat waktu kapan pesan dikirim.
     */
    protected $useTimestamps = true; // Mengaktifkan fitur timestamp otomatis
    protected $dateFormat    = 'datetime'; // Format tanggal (YYYY-MM-DD HH:MM:SS)
    protected $createdField  = 'created_at'; // Nama kolom untuk waktu kirim
    protected $updatedField  = ''; // Tidak menggunakan kolom update_at untuk pesan

    /**
     * FUNGSI getChatHistory
     * Mengambil riwayat percakapan antara User dan Admin untuk ditampilkan di jendela chat.
     */
    public function getChatHistory($id_user)
    {
        // Memilih data pesan beserta informasi pengirimnya (Nama, Foto, Role)
        return $this->select('pesan.*, pengirim.nama as nama_pengirim, pengirim.foto as foto_pengirim, pengirim.role as role_pengirim')
            // Join ke tabel users untuk mendapatkan detail pengirim
            ->join('users as pengirim', 'pesan.id_pengirim = pengirim.id_user')

            // Logika filter: Ambil pesan di mana User A adalah pengirim ATAU penerima
            ->groupStart()
            // Pesan yang dikirim oleh user tersebut
            ->groupStart()
            ->where('pesan.id_pengirim', $id_user)
            ->groupEnd()
            // ATAU pesan yang ditujukan untuk user tersebut
            ->orGroupStart()
            ->where('pesan.id_penerima', $id_user)
            ->groupEnd()
            ->groupEnd()
            // Urutkan dari pesan terlama ke terbaru (naik)
            ->orderBy('pesan.created_at', 'ASC')
            ->findAll();
    }

    /**
     * FUNGSI getDaftarChatAdmin
     * Digunakan oleh Admin untuk melihat daftar orang yang menghubunginya (list chat).
     */
    public function getDaftarChatAdmin()
    {
        // Menghubungkan ke database secara manual
        $db = \Config\Database::connect();
        // Mengambil ID Admin dari session, defaultnya 1 jika tidak ada
        $id_admin = session()->get('id_user') ?? 1;

        /**
         * 1. Logika SQL Subquery:
         * Mencari ID pesan terakhir (paling baru) dari setiap pasangan pengirim & penerima.
         * Tujuannya agar di list chat admin hanya muncul 1 baris per user (pesan terakhir saja).
         */
        $sql_ids = "SELECT MAX(id_pesan) as last_id 
                FROM pesan 
                GROUP BY LEAST(id_pengirim, id_penerima), GREATEST(id_pengirim, id_penerima)";

        // Menjalankan query SQL di atas
        $query_ids = $db->query($sql_ids)->getResultArray();
        // Mengambil kolom 'last_id' saja dan dijadikan array satu dimensi
        $ids = array_column($query_ids, 'last_id');

        // Jika tidak ada pesan sama sekali, kembalikan array kosong
        if (empty($ids)) {
            return [];
        }

        /**
         * 2. Query Detail:
         * Mengambil data lengkap (isi pesan, waktu, foto profil user) berdasarkan ID pesan terakhir tadi.
         */
        return $this->db->table('pesan')
            ->select('pesan.id_pesan, users.id_user, users.nama, users.foto, users.role, pesan.isi_pesan, pesan.tipe_pesan, pesan.created_at, pesan.status_baca, pesan.id_penerima, pesan.id_pengirim')
            // Join dinamis: Jika admin adalah pengirim, maka tampilkan data penerima. Jika admin penerima, tampilkan data pengirim.
            ->join('users', "users.id_user = (CASE WHEN pesan.id_pengirim = $id_admin OR pesan.id_pengirim = 0 THEN pesan.id_penerima ELSE pesan.id_pengirim END)", 'inner', false)
            // Hanya ambil pesan-pesan terakhir yang sudah difilter di poin 1
            ->whereIn('pesan.id_pesan', $ids)
            // Pastikan yang muncul di list admin hanyalah akun dengan role 'user'
            ->where('users.role', 'user')
            // Urutkan daftar chat berdasarkan waktu terbaru di paling atas
            ->orderBy('pesan.created_at', 'DESC')
            ->get()->getResultArray();
    }
}
