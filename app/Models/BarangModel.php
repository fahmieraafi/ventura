<?php

// Menentukan lokasi folder file ini di dalam struktur folder CodeIgniter 4
namespace App\Models;

// Mengimpor class Model bawaan CodeIgniter untuk digunakan sebagai induk (parent)
use CodeIgniter\Model;

class BarangModel extends Model
{
    // MENENTUKAN TABEL: Model ini secara khusus akan mengelola tabel bernama 'barang'
    protected $table            = 'barang';

    // MENENTUKAN PRIMARY KEY: Kolom unik yang menjadi kunci utama tabel ini
    protected $primaryKey       = 'id_barang';

    /**
     * ALLOWED FIELDS (Field yang diizinkan)
     * Daftar kolom di database yang boleh diisi atau diubah melalui form input.
     * Ini adalah fitur keamanan untuk mencegah penyusupan data ke kolom yang tidak diinginkan.
     */
    protected $allowedFields    = [
        'nama_barang', // Nama peralatan (misal: Tenda, Carrier)
        'kategori',    // Jenis barang (misal: Alat Masak, Tenda)
        'stok',        // Jumlah barang yang tersedia
        'harga_sewa',  // Harga sewa per hari
        'kondisi',     // Status fisik barang (Baru/Lama)
        'foto_barang', // Nama file gambar yang disimpan
        'views'        // Menghitung berapa kali barang ini dilihat user
    ];

    /**
     * USE TIMESTAMPS
     * Jika diset 'true', CodeIgniter akan otomatis mengisi kolom 'created_at' (saat data dibuat)
     * dan 'updated_at' (saat data diedit) tanpa perlu kita ketik manual di kode.
     */
    protected $useTimestamps = true;

    /**
     * FUNGSI KURANGI STOK
     * Fungsi custom untuk mengurangi stok barang sebanyak 1 secara otomatis.
     * Biasanya dipanggil ketika ada user yang melakukan booking/sewa barang.
     */
    public function kurangiStok($id_barang)
    {
        // Menjalankan query update ke database
        return $this->db->table($this->table)
            ->where('id_barang', $id_barang) // Mencari barang berdasarkan ID-nya
            // Menggunakan fungsi SET untuk menghitung stok baru
            // 'false' di akhir agar CodeIgniter tidak menambahkan tanda kutip pada perhitungan 'stok - 1'
            ->set('stok', 'stok - 1', false)
            ->update(); // Eksekusi perubahan ke database
    }
}
