<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        // Memanggil Model Barang agar bisa digunakan di semua fungsi
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        // Mengambil semua data barang dari database
        $data = [
            'title'  => 'Daftar Alat Kamping',
            'barang' => $this->barangModel->findAll()
        ];

        return view('barang/index', $data);
    }

    public function create()
    {
        // Menampilkan halaman form tambah barang
        return view('barang/create');
    }

    public function store()
    {
        // 1. Proses ambil file foto dari form
        $foto = $this->request->getFile('foto_barang');
        
        // Cek apakah ada foto yang diupload
        if ($foto->getError() == 4) {
            $namaFoto = 'tenda.jpg'; // Foto default jika tidak upload
        } else {
            $namaFoto = $foto->getRandomName(); // Generate nama unik
            $foto->move('uploads/barang', $namaFoto); // Pindahkan ke public/uploads/barang
        }

        // 2. Simpan data ke database
        $this->barangModel->save([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => $this->request->getPost('stok'),
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'foto_barang' => $namaFoto
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambah!');
    }

    public function edit($id)
    {
        // Mencari data barang berdasarkan ID untuk diedit
        $data = [
            'title'  => 'Edit Alat Kamping',
            'barang' => $this->barangModel->find($id)
        ];

        return view('barang/edit', $data);
    }

    public function update($id)
    {
        // 1. Ambil data barang yang lama untuk cek foto
        $barangLama = $this->barangModel->find($id);
        $foto = $this->request->getFile('foto_barang');

        // 2. Cek apakah user upload foto baru?
        if ($foto->getError() == 4) {
            $namaFoto = $this->request->getPost('fotoLama'); // Pakai foto lama
        } else {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/barang', $namaFoto);
            
            // Hapus foto fisik yang lama di folder agar tidak menumpuk (kecuali foto default)
            if ($barangLama['foto_barang'] != 'tenda.jpg' && file_exists('uploads/barang/' . $barangLama['foto_barang'])) {
                unlink('uploads/barang/' . $barangLama['foto_barang']);
            }
        }

        // 3. Update data ke database berdasarkan ID
        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => $this->request->getPost('stok'),
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'foto_barang' => $namaFoto
        ]);

        return redirect()->to('/barang')->with('success', 'Data berhasil diubah!');
    }

    public function delete($id)
    {
        // 1. Cari data barangnya dulu
        $barang = $this->barangModel->find($id);

        // 2. Hapus foto fisik di folder (jika bukan foto default)
        if ($barang['foto_barang'] != 'tenda.jpg' && file_exists('uploads/barang/' . $barang['foto_barang'])) {
            unlink('uploads/barang/' . $barang['foto_barang']);
        }

        // 3. Hapus data di database
        $this->barangModel->delete($id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus!');
    }
}