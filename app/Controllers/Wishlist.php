<?php

namespace App\Controllers;

// Mengimpor BaseController utama dari CodeIgniter 4
use App\Controllers\BaseController;

class Wishlist extends BaseController
{
    /**
     * FUNGSI INDEX
     * Menampilkan daftar barang yang sudah disimpan (wishlist) oleh user tertentu
     */
    public function index()
    {
        // Mengambil ID User dari session untuk tahu siapa yang sedang login
        $id_user = session()->get('id_user');

        // Proteksi: Jika variabel id_user kosong (belum login), tendang paksa ke halaman login
        if (!$id_user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Menghubungkan ke database
        $db = \Config\Database::connect();

        // Mengambil data barang dengan teknik JOIN
        // Kita mengambil data dari tabel 'wishlist' lalu mencocokkannya dengan tabel 'barang' 
        // berdasarkan id_barang agar kita bisa mendapatkan Nama, Harga, dan Foto barang tersebut.
        $data['barang'] = $db->table('wishlist')
            ->select('barang.*') // Mengambil semua kolom dari tabel barang
            ->join('barang', 'barang.id_barang = wishlist.id_barang') // Menggabungkan kedua tabel
            ->where('wishlist.id_user', $id_user) // Hanya ambil milik user yang sedang login
            ->get()->getResultArray(); // Menjalankan query dan mengubah hasilnya menjadi array

        // Menentukan judul halaman
        $data['title'] = "Wishlist Saya";

        // Mengirim data ke View 'wishlist/index' untuk ditampilkan ke user
        return view('wishlist/index', $data);
    }

    /**
     * FUNGSI TAMBAH (TOGGLE)
     * Fungsi ini unik karena bekerja seperti tombol Like: 
     * Klik sekali untuk simpan, klik lagi di barang yang sama untuk menghapus.
     */
    public function tambah($id_barang)
    {
        // Menghubungkan ke database
        $db = \Config\Database::connect();
        // Mengambil ID User yang sedang aktif
        $id_user = session()->get('id_user');

        // Validasi: Harus login untuk bisa menyimpan wishlist
        if (!$id_user) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu ya!');
        }

        // Cek dulu ke database: Apakah barang ini sudah pernah disimpan oleh user ini?
        $cek = $db->table('wishlist')
            ->where(['id_user' => $id_user, 'id_barang' => $id_barang])
            ->get()->getRow();

        if ($cek) {
            // LOGIKA BATAL SUKA: 
            // Jika data sudah ditemukan di database, berarti user ingin membatalkan simpanan barang tersebut.
            $db->table('wishlist')->delete(['id_user' => $id_user, 'id_barang' => $id_barang]);
            // Kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Dihapus dari wishlist!');
        } else {
            // LOGIKA SIMPAN BARANG: 
            // Jika data belum ada, berarti user ingin menambahkan barang ini ke favoritnya.
            $db->table('wishlist')->insert([
                'id_user'    => $id_user,
                'id_barang'  => $id_barang,
                'created_at' => date('Y-m-d H:i:s') // Mencatat waktu penyimpanan
            ]);
            // Kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Berhasil disimpan ke wishlist!');
        }
    }

    /**
     * FUNGSI HAPUS
     * Fungsi ini biasanya dipanggil dari tombol "Hapus" yang ada di dalam halaman Wishlist Saya.
     */
    public function hapus($id_barang)
    {
        // Menghubungkan ke database
        $db = \Config\Database::connect();
        // Mengambil ID User
        $id_user = session()->get('id_user');

        // Proteksi login
        if (!$id_user) {
            return redirect()->to('/login');
        }

        // Menghapus baris data di tabel wishlist yang cocok dengan id_user dan id_barang tersebut
        $db->table('wishlist')->delete([
            'id_user'   => $id_user,
            'id_barang' => $id_barang
        ]);

        // Mengalihkan kembali ke halaman daftar wishlist dengan pesan sukses
        return redirect()->to('/wishlist')->with('success', 'Item berhasil dihapus.');
    }
}
