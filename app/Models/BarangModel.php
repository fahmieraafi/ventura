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
        'foto_barang',
        'views'
    ];

    // PEMBERITAHUAN: Fitur otomatis mencatat waktu input/update
    protected $useTimestamps = true;

    public function kurangiStok($id_barang)
    {
        return $this->db->table($this->table)
            ->where('id_barang', $id_barang)
            ->set('stok', 'stok - 1', false) // Mengurangi stok asli di DB dengan 1
            ->update();
    }
}
