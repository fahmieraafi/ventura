<?php

// Menentukan namespace agar file ini berada dalam kelompok Models di CodeIgniter 4
namespace App\Models;

// Mengambil class Model dasar dari core sistem CodeIgniter 4
use CodeIgniter\Model;

class UsersModel extends Model
{
    // MENENTUKAN TABEL: Model ini secara khusus akan mengelola tabel bernama 'users'
    protected $table = 'users';

    // MENENTUKAN PRIMARY KEY: Kolom kunci utama sebagai identitas unik user adalah 'id_user'
    protected $primaryKey = 'id_user';

    /**
     * ALLOWED FIELDS (Kolom yang Diizinkan)
     * Daftar kolom di database yang boleh diisi atau diubah melalui aplikasi.
     * Sangat penting untuk mendaftarkan 'no_wa' dan 'ktp' di sini agar data tersebut
     * tidak ditolak oleh sistem saat proses pendaftaran atau update profil.
     */
    protected $allowedFields = [
        'nama',      // Nama lengkap pengguna
        'username',  // Nama unik untuk login
        'password',  // Kata sandi (yang sudah dienkripsi)
        'role',      // Peran pengguna (admin atau user)
        'foto',      // Nama file foto profil
        'no_wa',     // Nomor WhatsApp untuk koordinasi sewa
        'ktp'        // Nama file foto KTP sebagai jaminan sewa
    ];

    /**
     * FUNGSI getUsersByUsername
     * Fungsi kustom untuk mencari data satu user berdasarkan username-nya.
     * Biasanya fungsi ini digunakan pada proses LOGIN untuk mengecek apakah
     * username yang dimasukkan ada di database atau tidak.
     */
    public function getUsersByUsername($username)
    {
        // Mencari data di tabel users yang kolom 'username'-nya cocok, 
        // lalu mengambil data pertama (first) yang ditemukan.
        return $this->where('username', $username)->first();
    }
}
