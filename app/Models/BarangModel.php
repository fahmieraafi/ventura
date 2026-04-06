<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id_barang';
    
    // PEMBERITAHUAN: Field yang diizinkan untuk diisi lewat form
    protected $allowedFields    = [
        'nama_barang', 
        'kategori', 
        'stok', 
        'harga_sewa', 
        'kondisi', 
        'foto_barang'
    ];

    // PEMBERITAHUAN: Fitur otomatis mencatat waktu input/update
    protected $useTimestamps = true;
}