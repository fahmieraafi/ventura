<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Wishlist extends BaseController
{
    /**
     * FUNGSI INDEX
     * Menampilkan daftar barang yang sudah disimpan oleh user
     */
    public function index()
    {
        $id_user = session()->get('id_user');

        // Proteksi: Jika belum login, tendang ke halaman login
        if (!$id_user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();

        // Ambil data barang dengan cara JOIN tabel wishlist dan tabel barang
        $data['barang'] = $db->table('wishlist')
            ->select('barang.*')
            ->join('barang', 'barang.id_barang = wishlist.id_barang')
            ->where('wishlist.id_user', $id_user)
            ->get()->getResultArray();

        $data['title'] = "Wishlist Saya";

        // Pastikan kamu sudah membuat folder Views/wishlist/index.php
        return view('wishlist/index', $data);
    }

    /**
     * FUNGSI TAMBAH (TOGGLE)
     * Dipanggil dari tombol hati di katalog barang
     */
    public function tambah($id_barang)
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id_user');

        if (!$id_user) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu ya!');
        }

        // Cek apakah barang ini sudah ada di wishlist user
        $cek = $db->table('wishlist')
            ->where(['id_user' => $id_user, 'id_barang' => $id_barang])
            ->get()->getRow();

        if ($cek) {
            // Jika sudah ada, maka dihapus (Batal Suka)
            $db->table('wishlist')->delete(['id_user' => $id_user, 'id_barang' => $id_barang]);
            return redirect()->back()->with('success', 'Dihapus dari wishlist!');
        } else {
            // Jika belum ada, maka ditambah (Suka)
            $db->table('wishlist')->insert([
                'id_user'   => $id_user,
                'id_barang' => $id_barang,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success', 'Berhasil disimpan ke wishlist!');
        }
    }

    /**
     * FUNGSI HAPUS
     * Khusus untuk menghapus item saat berada di halaman wishlist
     */
    public function hapus($id_barang)
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id_user');

        if (!$id_user) {
            return redirect()->to('/login');
        }

        $db->table('wishlist')->delete([
            'id_user'   => $id_user,
            'id_barang' => $id_barang
        ]);

        return redirect()->to('/wishlist')->with('success', 'Item berhasil dihapus.');
    }
}
