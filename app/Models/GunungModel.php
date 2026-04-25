<?php

namespace App\Models;

use CodeIgniter\Model;

class GunungModel extends Model
{
    protected $table         = 'gunung';
    protected $primaryKey    = 'id_gunung'; // Pastikan di DB namanya id_gunung, bukan id
    protected $allowedFields = ['nama_gunung', 'lokasi', 'ketinggian', 'status', 'foto', 'deskripsi'];

    public function getAll()
    {
        return $this->findAll();
    }

    public function simpan($data)
    {
        return $this->insert($data);
    }

    public function hapus($id)
    {
        return $this->delete($id);
    }
}
