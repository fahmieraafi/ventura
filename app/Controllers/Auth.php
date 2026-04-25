<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\BarangModel; // Tambahkan ini agar bisa akses data barang
use CodeIgniter\Controller;

class Auth extends Controller
{
    // MODIFIKASI: Menampilkan halaman login sebagai Landing Page dengan data Barang
    public function login()
    {
        // Jika user sudah login, jangan kasih halaman landing, langsung lempar ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $barangModel = new BarangModel();

        $data = [
            'title'  => 'Ventura - Sewa Alat Outdoor',
            'barang' => $barangModel->findAll() // Mengambil semua data barang untuk ditampilkan
        ];

        return view('auth/login_landing', $data);
    }

    // Memproses data login (TIDAK BERUBAH)
    public function prosesLogin()
    {
        $session = session();
        $usersModel = new UsersModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $users = $usersModel->getUsersByUsername($username);

        if ($users) {
            if (password_verify($password, $users['password'])) {
                $session->set([
                    'id_user'   => $users['id_user'],
                    'nama'      => $users['nama'],
                    'username'  => $users['username'],
                    'role'      => $users['role'],
                    'foto'      => $users['foto'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('salahpw', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Nama tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    // Logout (TIDAK BERUBAH)
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
