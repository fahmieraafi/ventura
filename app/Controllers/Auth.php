<?php

namespace App\Controllers;

// Mengimpor model yang dibutuhkan untuk mengelola data user dan barang
use App\Models\UsersModel;
use App\Models\BarangModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    /**
     * FUNGSI LOGIN (Tampilan Utama/Landing Page)
     * Fungsi ini bertugas menampilkan halaman awal saat orang membuka web.
     */
    public function login()
    {
        // CEK SESSION: Jika user sudah login sebelumnya, otomatis diarahkan ke dashboard
        // agar user tidak perlu login berulang kali.
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        // Memanggil model barang untuk mengambil daftar alat outdoor
        $barangModel = new BarangModel();

        // MENYIAPKAN DATA: Mengambil semua list barang dari database
        // untuk dipajang di halaman depan (Landing Page).
        $data = [
            'title'  => 'Ventura - Sewa Alat Outdoor',
            'barang' => $barangModel->findAll()
        ];

        // Menampilkan view login_landing sambil mengirimkan data barang
        return view('auth/login_landing', $data);
    }

    /**
     * FUNGSI PROSES LOGIN
     * Fungsi ini bekerja di balik layar untuk memeriksa apakah username dan password benar.
     */
    public function prosesLogin()
    {
        $session = session();
        $usersModel = new UsersModel();

        // MENGAMBIL DATA: Menangkap inputan username & password dari form login
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // MENCARI USER: Mencari data di database berdasarkan username yang diketik
        $users = $usersModel->getUsersByUsername($username);

        if ($users) {
            // VERIFIKASI PASSWORD: Mencocokkan password yang diketik dengan yang ada di database (terenkripsi)
            if (password_verify($password, $users['password'])) {

                // JIKA BENAR: Simpan data penting user ke dalam SESSION (id, nama, role, foto, dll)
                $session->set([
                    'id_user'   => $users['id_user'],
                    'nama'      => $users['nama'],
                    'username'  => $users['username'],
                    'role'      => $users['role'],
                    'foto'      => $users['foto'],
                    'logged_in' => true
                ]);

                // Pindah ke halaman dashboard
                return redirect()->to('/dashboard');
            } else {
                // JIKA PASSWORD SALAH: Kirim pesan peringatan "Password salah"
                $session->setFlashdata('salahpw', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            // JIKA USERNAME TIDAK ADA: Kirim pesan peringatan "Nama tidak ditemukan"
            $session->setFlashdata('error', 'Nama tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    /**
     * FUNGSI LOGOUT
     * Fungsi ini bertugas menghapus semua jejak login user.
     */
    public function logout()
    {
        // MENGHAPUS SESSION: Semua data login dibuang dari server
        session()->destroy();

        // Kembalikan user ke halaman login awal
        return redirect()->to('/login');
    }
}
