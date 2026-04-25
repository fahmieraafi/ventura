<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    /**
     * KONSTRUKTOR
     * Fungsi yang otomatis dijalankan pertama kali saat controller dipanggil.
     * Digunakan untuk inisialisasi model barang agar siap dipakai di semua fungsi lain.
     */
    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    /**
     * HALAMAN UTAMA DAFTAR BARANG
     * Menampilkan semua koleksi alat kamping, kategori untuk filter, 
     * serta menangani pencarian dari Dashboard atau dropdown.
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // Mengambil daftar kategori unik (tidak duplikat) untuk mengisi pilihan di filter dropdown
        $listKategori = $db->table('barang')
            ->select('kategori')
            ->where('kategori !=', '')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        // Menangkap data pencarian/filter
        $kategoriSelected = $this->request->getVar('kategori'); // Dari dropdown halaman barang
        $keywordDashboard = $this->request->getVar('cari');     // Dari kolom pencarian dashboard

        // Logika Filter: Jika ada kategori yang dipilih atau kata kunci yang dicari, filter data modelnya
        if ($kategoriSelected !== null && $kategoriSelected !== '') {
            $this->barangModel->where('kategori', $kategoriSelected);
        } elseif ($keywordDashboard !== null && $keywordDashboard !== '') {
            $this->barangModel->like('kategori', $keywordDashboard);
            $kategoriSelected = $keywordDashboard;
        }

        $data = [
            'title'         => 'Daftar Alat Kamping',
            'listKategori'  => $listKategori,
            'kategoriAktif' => $kategoriSelected,
            // Ambil data barang, urutkan dari yang paling banyak dilihat (populer)
            'barang'        => $this->barangModel->orderBy('views', 'DESC')->findAll()
        ];

        return view('barang/index', $data);
    }

    /**
     * HALAMAN DETAIL BARANG
     * Menampilkan informasi lengkap satu barang dan menambah jumlah 'views' 
     * jika yang melihat bukan Admin.
     */
    public function detail($id)
    {
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Views Counter: Jika user bukan admin, maka jumlah dilihat (views) bertambah +1
        if (strtolower(session()->get('role')) !== 'admin') {
            $this->barangModel->where('id_barang', $id)
                ->set('views', 'views + 1', FALSE)
                ->update();

            $barang['views'] += 1; // Update variabel lokal agar tampilan langsung berubah
        }

        $data = [
            'title'  => 'Detail Barang',
            'barang' => $barang
        ];

        return view('barang/detail', $data);
    }

    /**
     * HALAMAN FORM TAMBAH BARANG
     * Menampilkan form untuk input barang baru dan list kategori yang sudah ada.
     */
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

    /**
     * PROSES SIMPAN DATA (INSERT)
     * Menangani pengolahan data dari form tambah, termasuk upload banyak foto sekaligus.
     */
    public function store()
    {
        $kategoriPilih = $this->request->getPost('kategori_pilih');
        $kategoriBaru  = $this->request->getPost('kategori_baru');

        // Jika user memilih kategori baru, gunakan input kategori_baru. Jika tidak, gunakan yang dipilih.
        $kategoriFinal = ($kategoriPilih === 'baru' && !empty($kategoriBaru)) ? $kategoriBaru : $kategoriPilih;

        // Proses upload banyak file foto
        $files = $this->request->getFileMultiple('foto_barang');
        $listNamaFoto = [];

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName(); // Beri nama acak agar tidak bentrok
                    $file->move('uploads/barang', $newName);
                    $listNamaFoto[] = $newName;
                }
            }
        }

        // Jika tidak ada foto diunggah, gunakan tenda.jpg sebagai default. 
        // Jika ada, gabungkan nama-nama file dengan koma (CSV format).
        $stringFoto = empty($listNamaFoto) ? 'tenda.jpg' : implode(',', $listNamaFoto);

        $this->barangModel->save([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $kategoriFinal,
            'stok'         => $this->request->getPost('stok'),
            'harga_sewa'   => $this->request->getPost('harga_sewa'),
            'kondisi'      => $this->request->getPost('kondisi'),
            'foto_barang'  => $stringFoto,
            'views'        => 0
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambah!');
    }

    /**
     * HALAMAN FORM EDIT
     * Mengambil data barang yang akan diedit berdasarkan ID.
     */
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

    /**
     * PROSES UPDATE DATA
     * Memperbarui data barang dan menangani penambahan foto baru tanpa menghapus yang lama.
     */
    public function update($id)
    {
        $kategoriPilih = $this->request->getPost('kategori_pilih');
        $kategoriBaru  = $this->request->getPost('kategori_baru');
        $kategoriFinal = ($kategoriPilih === 'baru' && !empty($kategoriBaru)) ? $kategoriBaru : $kategoriPilih;

        // Ambil data lama untuk mempertahankan foto yang sudah ada
        $barangLama = $this->barangModel->find($id);
        $fotoLamaArray = ($barangLama['foto_barang'] == 'tenda.jpg' || empty($barangLama['foto_barang'])) ? [] : explode(',', $barangLama['foto_barang']);

        // Proses unggah foto tambahan (jika ada)
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

        // Menggabungkan list foto lama dengan foto yang baru saja diunggah
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

    /**
     * FUNGSI HAPUS FOTO SATUAN (AJAX)
     * Menghapus satu file foto tertentu dari folder dan database tanpa menghapus baris barangnya.
     */
    public function hapusFotoSatuan()
    {
        $namaFile = $this->request->getPost('nama_file');
        $idBarang = $this->request->getPost('id_barang');

        $barang = $this->barangModel->find($idBarang);
        if (!$barang) return $this->response->setJSON(['status' => 'error', 'msg' => 'Data hilang']);

        $fotos = explode(',', $barang['foto_barang']);

        // Cari nama file di dalam array foto barang
        if (($key = array_search($namaFile, $fotos)) !== false) {
            unset($fotos[$key]); // Hapus dari daftar array

            // Hapus file fisik dari folder uploads jika bukan file default (tenda.jpg)
            if ($namaFile != 'tenda.jpg' && file_exists('uploads/barang/' . $namaFile)) {
                unlink('uploads/barang/' . $namaFile);
            }

            // Simpan kembali daftar foto yang tersisa ke database
            $stringBaru = empty($fotos) ? 'tenda.jpg' : implode(',', $fotos);
            $this->barangModel->update($idBarang, ['foto_barang' => $stringBaru]);

            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'msg' => 'Foto tidak ditemukan']);
    }

    /**
     * PROSES HAPUS BARANG
     * Menghapus seluruh data barang beserta semua file foto yang terkait di folder.
     */
    public function delete($id)
    {
        $barang = $this->barangModel->find($id);

        if ($barang) {
            // Hapus semua file fisik foto yang terdaftar di barang tersebut
            $fotos = explode(',', $barang['foto_barang']);
            foreach ($fotos as $f) {
                if ($f != 'tenda.jpg' && file_exists('uploads/barang/' . $f)) {
                    unlink('uploads/barang/' . $f);
                }
            }
            // Hapus data dari database
            $this->barangModel->delete($id);
        }

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus!');
    }
}
