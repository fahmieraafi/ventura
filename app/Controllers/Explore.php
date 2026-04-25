<?php

namespace App\Controllers;

// Mengimpor Model Gunung untuk berinteraksi dengan tabel 'gunung' di database
use App\Models\GunungModel;

class Explore extends BaseController
{
    // Properti private untuk menampung instance model
    private $model;

    /**
     * KONSTRUKTOR
     * Menyiapkan model dan helper agar siap digunakan di semua fungsi.
     */
    public function __construct()
    {
        $this->model = new GunungModel();
        // Helper form dan url memudahkan pembuatan form dan navigasi link
        helper(['form', 'url']);
    }

    /**
     * HALAMAN UTAMA (LIST GUNUNG)
     * Menampilkan daftar gunung dan menangani fitur pencarian (Search).
     */
    public function index()
    {
        // Menangkap kata kunci dari input pencarian di URL (metode GET)
        $keyword = $this->request->getGet('cari');

        if ($keyword) {
            // Jika ada kata kunci, cari nama gunung atau lokasi yang mirip (LIKE)
            $gunung = $this->model->like('nama_gunung', $keyword)
                ->orLike('lokasi', $keyword)
                ->findAll();
        } else {
            // Jika tidak ada kata kunci, ambil semua data menggunakan method custom getAll()
            $gunung = $this->model->getAll();
        }

        $data = [
            'list_gunung' => $gunung,
            'title'       => 'Explore Pegunungan',
            'keyword'     => $keyword
        ];

        // Mengirim data ke view index di folder gunung
        return view('gunung/index', $data);
    }

    /**
     * HALAMAN TAMBAH DATA
     * Hanya menampilkan form kosong untuk input data gunung baru.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Gunung Baru'
        ];
        return view('gunung/create', $data);
    }

    /**
     * PROSES SIMPAN DATA (INSERT)
     * Menangani pengiriman data dari form tambah dan proses upload banyak foto.
     */
    public function tambah()
    {
        // Memastikan request adalah POST
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {

            // Menangkap input banyak file (Multiple Upload)
            $files = $this->request->getFileMultiple('foto');
            $namaFotoSimpan = [];

            if ($files) {
                foreach ($files as $file) {
                    // Validasi: Pastikan file valid dan belum dipindahkan
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Beri nama acak agar tidak ada file yang namanya sama di folder
                        $newName = $file->getRandomName();
                        // Pindahkan file ke folder public/uploads/gunung/
                        $file->move(FCPATH . 'uploads/gunung/', $newName);
                        // Masukkan nama file baru ke dalam array
                        $namaFotoSimpan[] = $newName;
                    }
                }
            }

            // Gabungkan nama-nama foto menjadi satu string dipisah koma (CSV)
            // Jika kosong, berikan foto default.jpg
            $fotoFinal = !empty($namaFotoSimpan) ? implode(',', $namaFotoSimpan) : 'default.jpg';

            // Menyiapkan data untuk dimasukkan ke database
            $data = [
                'nama_gunung' => $this->request->getPost('nama_gunung'),
                'lokasi'      => $this->request->getPost('lokasi'),
                'ketinggian'  => $this->request->getPost('ketinggian'),
                'status'      => $this->request->getPost('status'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'foto'        => $fotoFinal
            ];

            // Eksekusi insert data ke tabel
            if ($this->model->insert($data)) {
                return redirect()->to(base_url('gunung'))->with('success', 'Data berhasil disimpan');
            } else {
                // Jika gagal (misal validasi model gagal), tampilkan errornya
                print_r($this->model->errors());
                die();
            }
        }

        return redirect()->to(base_url('gunung'));
    }

    /**
     * PROSES HAPUS DATA
     * Menghapus baris di database dan menghapus semua file foto terkait di folder.
     */
    public function delete($id)
    {
        $gunung = $this->model->find($id);
        // Hapus file fisik jika fotonya bukan default.jpg
        if ($gunung && $gunung['foto'] != 'default.jpg') {
            $arrayFoto = explode(',', $gunung['foto']); // Pecah string koma menjadi array
            foreach ($arrayFoto as $f) {
                // Hapus file dari server menggunakan unlink
                if (file_exists(FCPATH . 'uploads/gunung/' . $f)) {
                    unlink(FCPATH . 'uploads/gunung/' . $f);
                }
            }
        }
        // Hapus data dari database
        $this->model->delete($id);
        return redirect()->to(base_url('gunung'));
    }

    /**
     * HALAMAN DETAIL
     * Menampilkan informasi lengkap satu gunung berdasarkan ID.
     */
    public function detail($id)
    {
        $data = [
            'gunung' => $this->model->find($id),
            'title'  => 'Detail Gunung'
        ];

        if (empty($data['gunung'])) {
            return redirect()->to(base_url('gunung'))->with('error', 'Data tidak ditemukan');
        }

        return view('gunung/detail', $data);
    }

    /**
     * HALAMAN EDIT
     * Mengambil data lama untuk ditampilkan kembali di dalam form edit.
     */
    public function edit($id)
    {
        $data = [
            'gunung' => $this->model->find($id),
            'title'  => 'Edit Informasi Gunung'
        ];

        if (empty($data['gunung'])) {
            return redirect()->to(base_url('gunung'))->with('error', 'Data tidak ditemukan');
        }

        return view('gunung/edit', $data);
    }

    /**
     * PROSES PERBARUI DATA (UPDATE)
     * Mengganti data lama dengan data baru. Jika upload foto baru, foto lama dihapus.
     */
    public function update($id)
    {
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $gunungLama = $this->model->find($id);
            $files = $this->request->getFileMultiple('foto');
            $namaFotoSimpan = [];

            // Cek apakah ada file baru yang diunggah secara valid
            $adaFileBaru = false;
            if ($files) {
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $adaFileBaru = true;
                        break;
                    }
                }
            }

            if ($adaFileBaru) {
                // Jika ada file baru: Upload semua file baru tersebut
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/gunung/', $newName);
                        $namaFotoSimpan[] = $newName;
                    }
                }
                $fotoFinal = implode(',', $namaFotoSimpan);

                // Hapus foto-foto lama dari server agar tidak memenuhi penyimpanan
                if ($gunungLama['foto'] != 'default.jpg') {
                    $arrayFotoLama = explode(',', $gunungLama['foto']);
                    foreach ($arrayFotoLama as $fl) {
                        if (file_exists(FCPATH . 'uploads/gunung/' . $fl)) {
                            unlink(FCPATH . 'uploads/gunung/' . $fl);
                        }
                    }
                }
            } else {
                // Jika tidak ada upload foto baru, tetap gunakan nama foto yang lama
                $fotoFinal = $this->request->getPost('foto_lama');
            }

            // Menyusun data update
            $data = [
                'nama_gunung' => $this->request->getPost('nama_gunung'),
                'lokasi'      => $this->request->getPost('lokasi'),
                'ketinggian'  => $this->request->getPost('ketinggian'),
                'status'      => $this->request->getPost('status'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'foto'        => $fotoFinal
            ];

            // Eksekusi pembaruan data berdasarkan ID
            if ($this->model->update($id, $data)) {
                return redirect()->to(base_url('gunung'))->with('success', 'Data berhasil diperbarui');
            } else {
                print_r($this->model->errors());
                die();
            }
        }
        return redirect()->to(base_url('gunung'));
    }
}
