<?php

// Menentukan namespace untuk folder Filters di CodeIgniter 4
namespace App\Filters;

// Mengimpor library standar yang dibutuhkan untuk proses filtering HTTP
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    /**
     * FUNGSI BEFORE (SEBELUM)
     * Dijalankan sebelum request mencapai Controller tujuan.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Memanggil library session untuk mengambil data user yang sedang login
        $session = session();
        // Mengambil data 'role' (misal: 'admin' atau 'user') dari session
        $role = $session->get('role');

        // VALIDASI 1: Cek apakah user sudah login. 
        // Jika status 'logged_in' tidak ada, maka tendang balik ke halaman login.
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // VALIDASI 2: Periksa hak akses berdasarkan role yang diizinkan (arguments)
        // $arguments berisi daftar role yang boleh lewat (diatur di file Config/Filters.php)
        if ($arguments) {
            // Jika role user saat ini tidak ada dalam daftar role yang diizinkan
            if (!in_array($role, $arguments)) {
                // Tendang user ke dashboard dengan pesan error "tidak memiliki akses"
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }
    }

    /**
     * FUNGSI AFTER (SESUDAH)
     * Dijalankan setelah request selesai diproses oleh Controller.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Bagian ini biasanya dibiarkan kosong untuk filter keamanan login/role.
        // Tidak perlu diubah
    }
}
