<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Users extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        // --- 
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }
        // ------------------------------

        // TAMBAHAN FITUR CARI
        $cari = $this->request->getVar('cari');
        if ($cari) {
            $this->users->groupStart()
                ->like('nama', $cari)
                ->orLike('username', $cari)
                ->orLike('no_wa', $cari)
                ->groupEnd();
        }

        $data['users'] = $this->users->findAll();
        $data['cari']  = $cari; // Agar input text tidak hilang setelah klik cari

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        // 1. Perbaikan Validasi: Menghapus 'role' dari aturan validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'no_wa'    => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', implode('<br>', $validation->getErrors()));
        }

        // Proses upload FOTO PROFIL
        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png'; // Set default jika tidak upload
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(ROOTPATH . 'uploads/users', $namaFoto);
        }

        // Proses upload FOTO KTP
        $ktp = $this->request->getFile('ktp');
        $namaKtp = null;
        if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
            $namaKtp = $ktp->getRandomName();
            $ktp->move(ROOTPATH . 'uploads/ktp', $namaKtp);
        }

        // 2. Simpan ke database dengan ROLE OTOMATIS 'user'
        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user', // <--- Perbaikan: Dipaksa menjadi user
            'no_wa'    => $this->request->getPost('no_wa'),
            'foto'     => $namaFoto,
            'ktp'      => $namaKtp
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil didaftarkan! Silahkan login.');
    }

    public function edit($id)
    {
        $data['user'] = $this->users->find($id);
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->users->find($id);

        $fotoBaru = $this->request->getFile('foto');
        $ktpBaru = $this->request->getFile('ktp');

        $namaFoto = $user['foto'];
        $namaKtp = $user['ktp'];

        // Logika Ganti Foto Profil
        if ($fotoBaru && $fotoBaru->isValid() && $fotoBaru->getName() != '') {
            if (!empty($user['foto']) && $user['foto'] != 'default.png' && file_exists(ROOTPATH . 'uploads/users/' . $user['foto'])) {
                unlink(ROOTPATH . 'uploads/users/' . $user['foto']);
            }
            $namaFoto = $fotoBaru->getRandomName();
            $fotoBaru->move(ROOTPATH . 'uploads/users', $namaFoto);
        }

        // Logika Ganti Foto KTP
        if ($ktpBaru && $ktpBaru->isValid() && $ktpBaru->getName() != '') {
            if (!empty($user['ktp']) && file_exists(ROOTPATH . 'uploads/ktp/' . $user['ktp'])) {
                unlink(ROOTPATH . 'uploads/ktp/' . $user['ktp']);
            }
            $namaKtp = $ktpBaru->getRandomName();
            $ktpBaru->move(ROOTPATH . 'uploads/ktp', $namaKtp);
        }

        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'no_wa'    => $this->request->getPost('no_wa'),
            'foto'     => $namaFoto,
            'ktp'      => $namaKtp
        ];

        // 3. Update Role hanya jika inputnya ada (biasanya di halaman edit admin)
        if ($this->request->getPost('role')) {
            $data['role'] = $this->request->getPost('role');
        }

        if ($this->request->getPost('password') != "") {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->users->update($id, $data);

        // Update Session jika yang diupdate adalah akun sendiri
        if (session()->get('id_user') == $id) {
            session()->set('foto', $namaFoto);
            session()->set('nama', $data['nama']);
            session()->set('username', $data['username']);
        }


        // Jika yang login adalah 'user' biasa, kembali ke halaman edit profilnya sendiri
        if (session()->get('role') == 'user') {
            return redirect()->to('/users/edit/' . $id)->with('success', 'Profil kamu berhasil diperbarui!');
        }

        // Jika yang login adalah 'admin', baru boleh kembali ke daftar seluruh user
        return redirect()->to('/users')->with('success', 'Data berhasil diupdate!');
    }


    public function delete($id)
    {
        $user = $this->users->find($id);

        if ($user['foto'] && $user['foto'] != 'default.png' && file_exists(ROOTPATH . 'uploads/users/' . $user['foto'])) {
            unlink(ROOTPATH . 'uploads/users/' . $user['foto']);
        }

        if ($user['ktp'] && file_exists(ROOTPATH . 'uploads/ktp/' . $user['ktp'])) {
            unlink(ROOTPATH . 'uploads/ktp/' . $user['ktp']);
        }

        $this->users->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }
}
