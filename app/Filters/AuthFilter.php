<?php

// Menentukan lokasi folder file ini di dalam struktur folder CodeIgniter 4
namespace App\Filters;

// Mengimpor komponen standar CodeIgniter untuk menangani permintaan (Request), 
// tanggapan (Response), dan antarmuka Filter itu sendiri.
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * FUNGSI BEFORE (SEBELUM)
     * Fungsi ini dijalankan SEBELUM user berhasil mengakses sebuah halaman (Controller).
     * Di sinilah pengecekan keamanan dilakukan.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Mengecek apakah data 'logged_in' ada di dalam session akun user.
        // Tanda "!" di depan berarti "JIKA TIDAK".
        if (!session()->get('logged_in')) {

            // Jika user BELUM login, maka paksa arahkan (redirect) kembali ke halaman login.
            return redirect()->to('/login');
        }
    }

    /**
     * FUNGSI AFTER (SESUDAH)
     * Fungsi ini dijalankan SETELAH Controller selesai memproses data tapi sebelum dikirim ke browser.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Bagian ini dikosongkan karena biasanya tidak diperlukan untuk sistem proteksi login standar.
        // Tidak perlu diubah.
    }
}
