<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table            = 'pesan';
    protected $primaryKey       = 'id_pesan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Kolom yang boleh diisi (sesuai tabel SQL kita tadi)
    protected $allowedFields    = [
        'id_pengirim',
        'id_penerima',
        'isi_pesan',
        'file_lampiran',
        'tipe_pesan',
        'status_baca'
    ];

    // Dates (Otomatis mengisi created_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Kosongkan jika tidak ada kolom updated_at

    /**
     * Fungsi untuk mengambil chat antara User A dan User B
     * Digunakan untuk menampilkan history chat ala Instagram
     */ public function getChatHistory($id_user)
    {
        return $this->select('pesan.*, pengirim.nama as nama_pengirim, pengirim.foto as foto_pengirim, pengirim.role as role_pengirim') // Tambahkan role_pengirim
            ->join('users as pengirim', 'pesan.id_pengirim = pengirim.id_user')
            // ... sisa query sama seperti sebelumnya
            ->groupStart()
            // SEMUA pesan yang DIKIRIM oleh user tersebut ke siapapun (admin)
            ->groupStart()
            ->where('pesan.id_pengirim', $id_user)
            ->groupEnd()
            // ATAU SEMUA pesan yang DITERIMA oleh user tersebut dari siapapun (admin)
            ->orGroupStart()
            ->where('pesan.id_penerima', $id_user)
            ->groupEnd()
            ->groupEnd()
            ->orderBy('pesan.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Fungsi untuk mengambil daftar user yang mengirim pesan ke admin
     * Digunakan untuk halaman list chat admin
     */
    /**
     * Fungsi untuk mengambil daftar user yang mengirim pesan ke admin
     * Digunakan untuk halaman list chat admin
     */
    public function getDaftarChatAdmin()
    {
        $db = \Config\Database::connect();
        $id_admin = session()->get('id_user') ?? 1;

        // 1. Ambil ID pesan terakhir dari setiap percakapan
        $sql_ids = "SELECT MAX(id_pesan) as last_id 
                FROM pesan 
                GROUP BY LEAST(id_pengirim, id_penerima), GREATEST(id_pengirim, id_penerima)";

        $query_ids = $db->query($sql_ids)->getResultArray();
        $ids = array_column($query_ids, 'last_id');

        if (empty($ids)) {
            return [];
        }

        // 2. Ambil detailnya (PASTIKAN id_pesan ADA DI SINI)
        return $this->db->table('pesan')
            ->select('pesan.id_pesan, users.id_user, users.nama, users.foto, users.role, pesan.isi_pesan, pesan.tipe_pesan, pesan.created_at, pesan.status_baca, pesan.id_penerima, pesan.id_pengirim')
            ->join('users', "users.id_user = (CASE WHEN pesan.id_pengirim = $id_admin OR pesan.id_pengirim = 0 THEN pesan.id_penerima ELSE pesan.id_pengirim END)", 'inner', false)
            ->whereIn('pesan.id_pesan', $ids)
            ->where('users.role', 'user')
            ->orderBy('pesan.created_at', 'DESC')
            ->get()->getResultArray();
    }
}
