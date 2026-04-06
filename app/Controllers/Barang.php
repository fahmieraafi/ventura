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
        // 1. Ambil file foto dari form (Multiple)
        $files = $this->request->getFileMultiple('foto_barang');

        $listNamaFoto = [];
        foreach ($files as $file) {
            // Cek apakah ada file yang diunggah dan valid
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName(); // Generate nama unik
                $file->move('uploads/barang', $newName); // Pindahkan ke folder uploads
                $listNamaFoto[] = $newName;
            }
        }

        // 2. Jika tidak ada foto sama sekali, pakai default
        $stringFoto = empty($listNamaFoto) ? 'tenda.jpg' : implode(',', $listNamaFoto);

        // 3. Simpan data ke database
        $this->barangModel->save([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => $this->request->getPost('stok'),
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'foto_barang' => $stringFoto
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
        // 1. Ambil data barang lama
        $barangLama = $this->barangModel->find($id);

        // 2. Ambil list foto lama dari database
        $fotoLama = ($barangLama['foto_barang'] == 'tenda.jpg') ? [] : explode(',', $barangLama['foto_barang']);

        // 3. Ambil file baru dari form (jika ada)
        $files = $this->request->getFileMultiple('foto_barang');

        $fotoBaruDiunggah = [];
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/barang', $newName);
                    $fotoBaruDiunggah[] = $newName;
                }
            }
        }

        // 4. Gabungkan list lama dengan yang baru diupload
        $arrayFotoFinal = array_merge($fotoLama, $fotoBaruDiunggah);
        $stringFotoFinal = empty($arrayFotoFinal) ? 'tenda.jpg' : implode(',', $arrayFotoFinal);

        // 5. Update database
        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'stok'        => $this->request->getPost('stok'),
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'foto_barang' => $stringFotoFinal
        ]);

        return redirect()->to('/barang')->with('success', 'Data berhasil diubah!');
    }

    /**
     * FUNGSI AJAX: Menghapus satu foto saja dari daftar foto barang (Tombol X)
     */
    public function hapusFotoSatuan()
    {
        $namaFile = $this->request->getPost('nama_file');
        $idBarang = $this->request->getPost('id_barang');

        $barang = $this->barangModel->find($idBarang);
        if (!$barang) return $this->response->setJSON(['status' => 'error', 'msg' => 'Data hilang']);

        $fotos = explode(',', $barang['foto_barang']);

        // Cari file di array, hapus dari daftar, lalu hapus fisiknya
        if (($key = array_search($namaFile, $fotos)) !== false) {
            unset($fotos[$key]);

            if ($namaFile != 'tenda.jpg' && file_exists('uploads/barang/' . $namaFile)) {
                unlink('uploads/barang/' . $namaFile);
            }

            $stringBaru = empty($fotos) ? 'tenda.jpg' : implode(',', $fotos);
            $this->barangModel->update($idBarang, ['foto_barang' => $stringBaru]);

            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'msg' => 'Foto tidak ditemukan']);
    }

    public function delete($id)
    {
        // 1. Cari data barangnya
        $barang = $this->barangModel->find($id);

        // 2. Hapus SEMUA foto fisik yang ada di database
        $fotos = explode(',', $barang['foto_barang']);
        foreach ($fotos as $f) {
            if ($f != 'tenda.jpg' && file_exists('uploads/barang/' . $f)) {
                unlink('uploads/barang/' . $f);
            }
        }

        // 3. Hapus data di database
        $this->barangModel->delete($id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus!');
    }
}
