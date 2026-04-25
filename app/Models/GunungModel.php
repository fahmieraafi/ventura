<?php

// Menentukan namespace agar file ini dikenali oleh sistem CodeIgniter di folder Models
namespace App\Models;

// Mengambil class Model dasar dari sistem CodeIgniter 4
use CodeIgniter\Model;

class GunungModel extends Model
{
    // MENENTUKAN TABEL: Model ini bertugas mengelola tabel bernama 'gunung' di database
    protected $table         = 'gunung';

    // MENENTUKAN PRIMARY KEY: Kolom identitas unik untuk tabel ini adalah 'id_gunung'
    protected $primaryKey    = 'id_gunung'; // Pastikan di DB namanya id_gunung, bukan id

    /**
     * ALLOWED FIELDS (Kolom yang Diizinkan)
     * Daftar kolom yang boleh diisi melalui input user. 
     * Jika kolom tidak terdaftar di sini, data tidak akan masuk ke database (keamanan).
     */
    protected $allowedFields = [
        'nama_gunung', // Nama gunung (misal: Semeru, Rinjani)
        'lokasi',      // Lokasi provinsi/wilayah
        'ketinggian',  // MDPL (Meter Diatas Permukaan Laut)
        'status',      // Status gunung (misal: Aktif, Tidak Aktif, atau Buka/Tutup Jalur)
        'foto',        // Nama file gambar pemandangan gunung
        'deskripsi'    // Penjelasan detail mengenai gunung tersebut
    ];

    /**
     * FUNGSI GET ALL
     * Digunakan untuk mengambil semua baris data gunung dari database tanpa terkecuali.
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * FUNGSI SIMPAN
     * Digunakan untuk memasukkan data baru ke dalam tabel gunung.
     * Menerima parameter $data yang berisi array kolom dan nilainya.
     */
    public function simpan($data)
    {
        return $this->insert($data);
    }

    /**
     * FUNGSI HAPUS
     * Digunakan untuk menghapus satu data gunung berdasarkan ID-nya.
     */
    public function hapus($id)
    {
        return $this->delete($id);
    }
}
