<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        // Menyiapkan model agar bisa digunakan di semua fungsi
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // Ambil daftar kategori unik untuk dropdown filter di view barang
        $listKategori = $db->table('barang')
            ->select('kategori')
            ->where('kategori !=', '')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        // --- AWAL PENAMBAHAN LOGIKA FILTER ---

        // 1. Ambil input dari filter dropdown (kategori)
        $kategoriSelected = $this->request->getVar('kategori');

        // 2. Ambil input dari Dashboard (cari)
        $keywordDashboard = $this->request->getVar('cari');

        if ($kategoriSelected !== null && $kategoriSelected !== '') {
            // Jika user memfilter lewat dropdown kategori di halaman barang
            $this->barangModel->where('kategori', $kategoriSelected);
        } elseif ($keywordDashboard !== null && $keywordDashboard !== '') {
            // Jika user datang dari klik kartu di Dashboard
            $this->barangModel->like('kategori', $keywordDashboard);
            $kategoriSelected = $keywordDashboard;
        }

        // --- AKHIR PENAMBAHAN LOGIKA FILTER ---

        $data = [
            'title'         => 'Daftar Alat Kamping',
            'listKategori'  => $listKategori,
            'kategoriAktif' => $kategoriSelected,
            // Mengambil semua barang dan diurutkan berdasarkan Views terbanyak (populer)
            'barang'        => $this->barangModel->orderBy('views', 'DESC')->findAll()
        ];

        return view('barang/index', $data);
    }

    /**
     * FUNGSI DETAIL & UPDATE VIEW
     * Fungsi ini dipanggil saat user mengklik foto barang.
     * Sudah dimodifikasi: Admin tidak menambah hitungan views.
     */
    public function detail($id)
    {
        // Ambil data barang terlebih dahulu
        $barang = $this->barangModel->find($id);

        // Jika data barang tidak ditemukan
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // --- LOGIKA UPDATE VIEW (MODIFIKASI) ---
        // Cek role user dari session. Views hanya bertambah jika yang login BUKAN admin.
        if (strtolower(session()->get('role')) !== 'admin') {
            $this->barangModel->where('id_barang', $id)
                ->set('views', 'views + 1', FALSE)
                ->update();

            // Perbarui nilai views di variabel agar tampilan sinkron saat itu juga
            $barang['views'] += 1;
        }

        $data = [
            'title'  => 'Detail Barang',
            'barang' => $barang
        ];

        return view('barang/detail', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $listKategori = $db->table('barang')
            ->select('kategori')
            ->where('kategori !=', '')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Tambah Alat Kamping',
            'listKategori' => $listKategori
        ];

        return view('barang/create', $data);
    }

    public function store()
    {
        $kategoriPilih = $this->request->getPost('kategori_pilih');
        $kategoriBaru  = $this->request->getPost('kategori_baru');

        $kategoriFinal = ($kategoriPilih === 'baru' && !empty($kategoriBaru)) ? $kategoriBaru : $kategoriPilih;

        $files = $this->request->getFileMultiple('foto_barang');
        $listNamaFoto = [];

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/barang', $newName);
                    $listNamaFoto[] = $newName;
                }
            }
        }

        $stringFoto = empty($listNamaFoto) ? 'tenda.jpg' : implode(',', $listNamaFoto);

        $this->barangModel->save([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $kategoriFinal,
            'stok'         => $this->request->getPost('stok'),
            'harga_sewa'   => $this->request->getPost('harga_sewa'),
            'kondisi'      => $this->request->getPost('kondisi'),
            'foto_barang'  => $stringFoto,
            'views'        => 0 // Memulai views dari angka 0 untuk barang baru
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambah!');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $listKategori = $db->table('barang')
            ->select('kategori')
            ->where('kategori !=', '')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Edit Alat Kamping',
            'barang'       => $this->barangModel->find($id),
            'listKategori' => $listKategori
        ];

        return view('barang/edit', $data);
    }

    public function update($id)
    {
        $kategoriPilih = $this->request->getPost('kategori_pilih');
        $kategoriBaru  = $this->request->getPost('kategori_baru');

        $kategoriFinal = ($kategoriPilih === 'baru' && !empty($kategoriBaru)) ? $kategoriBaru : $kategoriPilih;

        $barangLama = $this->barangModel->find($id);
        $fotoLamaArray = ($barangLama['foto_barang'] == 'tenda.jpg' || empty($barangLama['foto_barang'])) ? [] : explode(',', $barangLama['foto_barang']);

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

        $arrayFotoFinal = array_merge($fotoLamaArray, $fotoBaruDiunggah);
        $stringFotoFinal = empty($arrayFotoFinal) ? 'tenda.jpg' : implode(',', $arrayFotoFinal);

        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $kategoriFinal,
            'stok'         => $this->request->getPost('stok'),
            'harga_sewa'   => $this->request->getPost('harga_sewa'),
            'kondisi'      => $this->request->getPost('kondisi'),
            'foto_barang'  => $stringFotoFinal
        ]);

        return redirect()->to('/barang')->with('success', 'Data berhasil diubah!');
    }

    public function hapusFotoSatuan()
    {
        $namaFile = $this->request->getPost('nama_file');
        $idBarang = $this->request->getPost('id_barang');

        $barang = $this->barangModel->find($idBarang);
        if (!$barang) return $this->response->setJSON(['status' => 'error', 'msg' => 'Data hilang']);

        $fotos = explode(',', $barang['foto_barang']);

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
        $barang = $this->barangModel->find($id);

        if ($barang) {
            $fotos = explode(',', $barang['foto_barang']);
            foreach ($fotos as $f) {
                if ($f != 'tenda.jpg' && file_exists('uploads/barang/' . $f)) {
                    unlink('uploads/barang/' . $f);
                }
            }
            $this->barangModel->delete($id);
        }

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus!');
    }
}
