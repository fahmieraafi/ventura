<?php

namespace App\Controllers;

// Mengimpor Model Users untuk berinteraksi dengan tabel 'users' di database
use App\Models\UsersModel;

class Users extends BaseController
{
    protected $users;

    /**
     * KONSTRUKTOR
     * Menyiapkan instance UsersModel agar bisa digunakan di seluruh fungsi dalam controller ini
     */
    public function __construct()
    {
        $this->users = new UsersModel();
    }

    /**
     * HALAMAN DAFTAR USER (SISI ADMIN)
     * Menampilkan daftar semua pengguna terdaftar dengan fitur pencarian
     */
    public function index()
    {
        // Proteksi keamanan: Hanya user dengan role 'admin' yang bisa melihat daftar semua user
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }

        // Menangkap kata kunci dari kolom pencarian
        $cari = $this->request->getVar('cari');
        if ($cari) {
            // Mencari user yang nama, username, atau no_wa-nya mirip dengan kata kunci
            $this->users->groupStart()
                ->like('nama', $cari)
                ->orLike('username', $cari)
                ->orLike('no_wa', $cari)
                ->groupEnd();
        }

        // Mengambil semua data user hasil pencarian/semuanya
        $data['users'] = $this->users->findAll();
        $data['cari']  = $cari; // Mengirim kembali kata kunci ke view agar input tidak kosong setelah reload

        return view('users/index', $data);
    }

    /**
     * HALAMAN TAMBAH / REGISTRASI
     * Menampilkan form untuk membuat akun baru
     */
    public function create()
    {
        return view('users/create');
    }

    /**
     * PROSES SIMPAN AKUN (REGISTRASI)
     * Menangani validasi data, upload file, dan enkripsi password
     */
    public function store()
    {
        // Menginisialisasi library validasi
        $validation = \Config\Services::validation();
        // Menentukan aturan input: Nama wajib, Username harus unik (belum ada di DB), Password min 4 karakter
        $validation->setRules([
            'nama'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'no_wa'    => 'required',
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan error
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', implode('<br>', $validation->getErrors()));
        }

        // MENGELOLA FOTO PROFIL
        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png'; // Gunakan default jika user tidak upload foto
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName(); // Buat nama acak agar tidak bentrok
            $foto->move(ROOTPATH . 'uploads/users', $namaFoto); // Pindahkan ke folder uploads/users
        }

        // MENGELOLA FOTO KTP
        $ktp = $this->request->getFile('ktp');
        $namaKtp = null;
        if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
            $namaKtp = $ktp->getRandomName();
            $ktp->move(ROOTPATH . 'uploads/ktp', $namaKtp); // Pindahkan ke folder uploads/ktp
        }

        // MENYIMPAN KE DATABASE
        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            // Password dienkripsi menggunakan Bcrypt (keamanan standar)
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user', // Akun baru otomatis menjadi role 'user'
            'no_wa'    => $this->request->getPost('no_wa'),
            'foto'     => $namaFoto,
            'ktp'      => $namaKtp
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil didaftarkan! Silahkan login.');
    }

    /**
     * HALAMAN EDIT PROFIL
     * Mengambil data satu user berdasarkan ID untuk diedit
     */
    public function edit($id)
    {
        $data['user'] = $this->users->find($id);
        return view('users/edit', $data);
    }

    /**
     * PROSES UPDATE PROFIL
     * Menangani pembaruan data, penggantian file (hapus file lama), dan update session
     */
    public function update($id)
    {
        $user = $this->users->find($id); // Cari data lama di DB

        $fotoBaru = $this->request->getFile('foto');
        $ktpBaru = $this->request->getFile('ktp');

        $namaFoto = $user['foto']; // Gunakan foto lama sebagai default
        $namaKtp = $user['ktp'];   // Gunakan KTP lama sebagai default

        // LOGIKA GANTI FOTO PROFIL
        if ($fotoBaru && $fotoBaru->isValid() && $fotoBaru->getName() != '') {
            // Hapus file foto lama di folder jika bukan default.png agar memori tidak penuh
            if (!empty($user['foto']) && $user['foto'] != 'default.png' && file_exists(ROOTPATH . 'uploads/users/' . $user['foto'])) {
                unlink(ROOTPATH . 'uploads/users/' . $user['foto']);
            }
            $namaFoto = $fotoBaru->getRandomName();
            $fotoBaru->move(ROOTPATH . 'uploads/users', $namaFoto);
        }

        // LOGIKA GANTI FOTO KTP
        if ($ktpBaru && $ktpBaru->isValid() && $ktpBaru->getName() != '') {
            // Hapus file KTP lama di folder
            if (!empty($user['ktp']) && file_exists(ROOTPATH . 'uploads/ktp/' . $user['ktp'])) {
                unlink(ROOTPATH . 'uploads/ktp/' . $user['ktp']);
            }
            $namaKtp = $ktpBaru->getRandomName();
            $ktpBaru->move(ROOTPATH . 'uploads/ktp', $namaKtp);
        }

        // MENYIAPKAN DATA YANG AKAN DIUPDATE
        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'no_wa'    => $this->request->getPost('no_wa'),
            'foto'     => $namaFoto,
            'ktp'      => $namaKtp
        ];

        // Role hanya bisa diubah jika ada inputnya (biasanya form khusus admin)
        if ($this->request->getPost('role')) {
            $data['role'] = $this->request->getPost('role');
        }

        // Password hanya diupdate jika user mengisi kolom password (tidak kosong)
        if ($this->request->getPost('password') != "") {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->users->update($id, $data);

        // SYNC SESSION: Jika user mengedit profilnya sendiri, session harus diperbarui agar nama/foto di navbar langsung berubah
        if (session()->get('id_user') == $id) {
            session()->set('foto', $namaFoto);
            session()->set('nama', $data['nama']);
            session()->set('username', $data['username']);
        }

        // REDIRECT BERDASARKAN ROLE
        if (session()->get('role') == 'user') {
            // Jika user biasa, tetap di halaman profil sendiri
            return redirect()->to('/users/edit/' . $id)->with('success', 'Profil kamu berhasil diperbarui!');
        }

        // Jika admin, kembali ke tabel daftar user
        return redirect()->to('/users')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * HAPUS USER
     * Menghapus data di database serta menghapus file fisik foto & KTP di server
     */
    public function delete($id)
    {
        $user = $this->users->find($id);

        // Hapus foto profil dari folder jika ada
        if ($user['foto'] && $user['foto'] != 'default.png' && file_exists(ROOTPATH . 'uploads/users/' . $user['foto'])) {
            unlink(ROOTPATH . 'uploads/users/' . $user['foto']);
        }

        // Hapus foto KTP dari folder jika ada
        if ($user['ktp'] && file_exists(ROOTPATH . 'uploads/ktp/' . $user['ktp'])) {
            unlink(ROOTPATH . 'uploads/ktp/' . $user['ktp']);
        }

        $this->users->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }
}
