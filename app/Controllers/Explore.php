<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Explore extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new GunungModel();
        // Penting: Load helper untuk menangani form dan URL
        helper(['form', 'url']);
    }

    public function index()
    {
        // Fitur Cari
        $keyword = $this->request->getGet('cari');

        if ($keyword) {
            $gunung = $this->model->like('nama_gunung', $keyword)
                ->orLike('lokasi', $keyword)
                ->findAll();
        } else {
            $gunung = $this->model->getAll();
        }

        $data = [
            'list_gunung' => $gunung,
            'title'       => 'Explore Pegunungan',
            'keyword'     => $keyword
        ];

        return view('gunung/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Gunung Baru'
        ];
        return view('gunung/create', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {

            // Menerima banyak file foto
            $files = $this->request->getFileMultiple('foto');
            $namaFotoSimpan = [];

            if ($files) {
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/gunung/', $newName);
                        $namaFotoSimpan[] = $newName;
                    }
                }
            }

            // Jika tidak ada foto yang berhasil diupload, gunakan default
            $fotoFinal = !empty($namaFotoSimpan) ? implode(',', $namaFotoSimpan) : 'default.jpg';

            $data = [
                'nama_gunung' => $this->request->getPost('nama_gunung'),
                'lokasi'      => $this->request->getPost('lokasi'),
                'ketinggian'  => $this->request->getPost('ketinggian'),
                'status'      => $this->request->getPost('status'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'foto'        => $fotoFinal
            ];

            if ($this->model->insert($data)) {
                return redirect()->to(base_url('gunung'))->with('success', 'Data berhasil disimpan');
            } else {
                print_r($this->model->errors());
                die();
            }
        }

        return redirect()->to(base_url('gunung'));
    }

    public function delete($id)
    {
        $gunung = $this->model->find($id);
        if ($gunung && $gunung['foto'] != 'default.jpg') {
            $arrayFoto = explode(',', $gunung['foto']);
            foreach ($arrayFoto as $f) {
                if (file_exists(FCPATH . 'uploads/gunung/' . $f)) {
                    unlink(FCPATH . 'uploads/gunung/' . $f);
                }
            }
        }
        $this->model->delete($id);
        return redirect()->to(base_url('gunung'));
    }

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

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST' || $this->request->getMethod() === 'post') {
            $gunungLama = $this->model->find($id);
            $files = $this->request->getFileMultiple('foto');
            $namaFotoSimpan = [];

            // Cek apakah ada file baru yang diunggah
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
                // Upload file-file baru
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads/gunung/', $newName);
                        $namaFotoSimpan[] = $newName;
                    }
                }
                $fotoFinal = implode(',', $namaFotoSimpan);

                // Hapus foto-foto lama dari folder
                if ($gunungLama['foto'] != 'default.jpg') {
                    $arrayFotoLama = explode(',', $gunungLama['foto']);
                    foreach ($arrayFotoLama as $fl) {
                        if (file_exists(FCPATH . 'uploads/gunung/' . $fl)) {
                            unlink(FCPATH . 'uploads/gunung/' . $fl);
                        }
                    }
                }
            } else {
                // Jika tidak upload foto baru, pakai foto yang lama (dari input hidden foto_lama)
                $fotoFinal = $this->request->getPost('foto_lama');
            }

            $data = [
                'nama_gunung' => $this->request->getPost('nama_gunung'),
                'lokasi'      => $this->request->getPost('lokasi'),
                'ketinggian'  => $this->request->getPost('ketinggian'),
                'status'      => $this->request->getPost('status'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'foto'        => $fotoFinal
            ];

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
