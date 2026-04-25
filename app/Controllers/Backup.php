<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Backup extends Controller
{
    /**
     * FUNGSI DATABASE (Backup Database)
     * Fungsi ini digunakan untuk membuat salinan (export) seluruh database ke dalam file .sql
     */
    public function database()
    {
        // 1. MENGHUBUNGKAN KE DATABASE: Mengambil info koneksi yang sedang aktif
        $db      = \Config\Database::connect();
        $dbName  = $db->getDatabase(); // Mendapatkan nama database yang digunakan

        // 2. MENGAMBIL KONFIGURASI: Mengambil username, password, dan host dari file .env
        $user    = env('database.default.username');
        $pass    = env('database.default.password');
        $host    = env('database.default.hostname');

        // 3. MENENTUKAN LOKASI SIMPAN: File akan disimpan di folder writable/backup/ dengan nama berdasarkan waktu
        $backupFile = WRITEPATH . 'backup/backup-' . date('Y-m-d_H-i-s') . '.sql';

        // 4. VALIDASI FOLDER: Cek apakah folder 'backup' sudah ada di folder writable. 
        // Jika belum ada, maka folder tersebut akan dibuat secara otomatis.
        if (!is_dir(WRITEPATH . 'backup')) {
            mkdir(WRITEPATH . 'backup', 0777, true);
        }

        // 5. PATH MYSQLDUMP: Menentukan lokasi file eksekutor 'mysqldump' milik XAMPP.
        // File ini adalah alat utama milik MySQL untuk melakukan proses export data.
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump'; // Jalur default untuk Windows

        // 6. MENYUSUN PERINTAH: Menggabungkan info database dan path file ke dalam satu baris perintah sistem.
        $command = "{$mysqldumpPath} --user={$user} --password={$pass} --host={$host} {$dbName} > {$backupFile}";

        // 7. EKSEKUSI PERINTAH: Menjalankan perintah shell/terminal melalui fungsi PHP 'system'.
        system($command, $output);

        // 8. CEK HASIL & DOWNLOAD: 
        // Memastikan file backup benar-benar ada dan tidak kosong (ukurannya lebih dari 0 byte).
        if (file_exists($backupFile) && filesize($backupFile) > 0) {
            // Jika berhasil, browser akan otomatis men-download file .sql tersebut.
            return $this->response->download($backupFile, null);
        } else {
            // Jika gagal (misal: password salah atau path mysqldump tidak ditemukan), munculkan pesan error.
            return "Backup gagal. Periksa konfigurasi database Anda atau perintah mysqldump.";
        }
    }
}
