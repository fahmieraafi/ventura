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
        $data['users'] = $this->users->findAll();
        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        // PEMBERITAHUAN: Validasi data input dari form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'role'     => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', implode('<br>', $validation->getErrors()));
        }

        // PEMBERITAHUAN: Proses upload foto baru saat pendaftaran user
        $foto = $this->request->getFile('foto');
        $namaFoto = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        // PEMBERITAHUAN: Menyimpan data user baru ke database
        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ]);

        return redirect()->to('/login')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['user'] = $this->users->find($id);
        return view('users/edit', $data);
    }

    public function update($id)
    {
        // PEMBERITAHUAN: Mengambil data user lama untuk pengecekan foto
        $user = $this->users->find($id); 

        $fotoBaru = $this->request->getFile('foto');

        // PEMBERITAHUAN: Jika tidak ada upload baru, gunakan nama foto lama
        $namaFoto = $user['foto'];

        // PEMBERITAHUAN: Logika ganti foto (Hapus foto lama, simpan yang baru)
        if ($fotoBaru && $fotoBaru->isValid() && $fotoBaru->getName() != '') {

            // Hapus file fisik foto lama di folder uploads jika ada
            if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $user['foto']);
            }

            // Generate nama acak dan pindahkan file baru ke folder
            $namaFoto = $fotoBaru->getRandomName();
            $fotoBaru->move(FCPATH . 'uploads/users', $namaFoto);
        }

        // PEMBERITAHUAN: Menyiapkan data untuk dikirim ke database
        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ];

        // PEMBERITAHUAN: Update password hanya jika kolom password di form diisi
        if ($this->request->getPost('password') != "") {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // PEMBERITAHUAN: Eksekusi update data di database
        $this->users->update($id, $data);

        // =========================================================================
        // PEMBERITAHUAN PENTING: UPDATE SESSION
        // Bagian ini memastikan foto di Navbar & Sidebar berubah secara real-time
        // =========================================================================
        if (session()->get('id_user') == $id) {
            session()->set('foto', $namaFoto);
            session()->set('nama', $data['nama']);
            session()->set('username', $data['username']);
        }
        // =========================================================================

        return redirect()->to('/users')->with('success', 'Data user berhasil diupdate!');
    }

    public function delete($id)
    {
        $user = $this->users->find($id);

        // PEMBERITAHUAN: Hapus file foto dari folder sebelum data di database dihapus
        if ($user['foto'] && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
            unlink(FCPATH . 'uploads/users/' . $user['foto']);
        }

        $this->users->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }
}