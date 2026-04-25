<?php

// Menentukan namespace agar file ini berada dalam folder Models di aplikasi CodeIgniter 4
namespace App\Models;

// Mengambil class Model dasar dari sistem core CodeIgniter 4
use CodeIgniter\Model;

class WishlistModel extends Model
{
    // NAMA TABEL: Menentukan bahwa model ini akan beroperasi pada tabel bernama 'wishlist'
    protected $table            = 'wishlist';

    // PRIMARY KEY: Menentukan kolom 'id_wishlist' sebagai kunci utama unik di tabel ini
    protected $primaryKey       = 'id_wishlist';

    /**
     * ALLOWED FIELDS (Kolom yang Diizinkan)
     * Daftar kolom yang boleh diisi atau dimanipulasi melalui aplikasi.
     * id_user   : Menyimpan siapa yang menyukai barang.
     * id_barang : Menyimpan barang apa yang disukai.
     * created_at: Mencatat kapan barang tersebut dimasukkan ke wishlist.
     */
    protected $allowedFields    = ['id_user', 'id_barang', 'created_at'];

    /**
     * USE TIMESTAMPS
     * Diatur ke 'false' karena kamu memilih untuk mengisi kolom 'created_at' 
     * secara manual di Controller menggunakan fungsi date(), sehingga sistem 
     * tidak perlu mengisinya secara otomatis.
     */
    protected $useTimestamps    = false;
}
