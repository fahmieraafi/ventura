<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    // Nama tabel di database
    protected $table            = 'wishlist';
    // Nama primary key-nya
    protected $primaryKey       = 'id_wishlist';
    // Biar bisa input data lewat save() atau insert()
    protected $allowedFields    = ['id_user', 'id_barang', 'created_at'];
    // Otomatis ngisi tanggal waktu simpan
    protected $useTimestamps    = false;
}
