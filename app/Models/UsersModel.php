<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    // Menambahkan no_wa dan ktp agar bisa disimpan ke database
    protected $allowedFields = ['nama', 'username', 'password', 'role', 'foto', 'no_wa', 'ktp'];

    public function getUsersByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
