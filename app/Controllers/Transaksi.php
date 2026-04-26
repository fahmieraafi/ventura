<?php

namespace App\Controllers;

// Mengimpor Model Transaksi dan Model Barang untuk mengelola data penyewaan dan stok
use App\Models\TransaksiModel;
use App\Models\BarangModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $barangModel;

    /**
     * KONSTRUKTOR
     * Menyiapkan instance model transaksi dan barang agar bisa digunakan di semua fungsi
     */
    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel = new BarangModel();
    }

    /**
     * HALAMAN RIWAYAT (SISI USER)
     * Menampilkan daftar penyewaan milik user yang sedang login dengan fitur filter & cari
     */
    public function index()
    {
        $id_user = session()->get('id_user');
        if (!$id_user) return redirect()->to('/login'); // Proteksi jika belum login

        // Mengambil input filter pencarian dan tanggal dari URL (GET)
        $keyword = $this->request->getVar('cari');
        $tgl_filter = $this->request->getVar('tgl');

        // Menyusun query: Ambil data transaksi gabung dengan nama barang
        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('id_user', $id_user);

        // Filter berdasarkan tanggal jika diisi
        if ($tgl_filter) {
            $query->where('transaksi.tgl_pinjam', $tgl_filter);
        }

        // Filter berdasarkan pencarian nama barang atau ID transaksi
        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('transaksi.id_transaksi', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'     => 'Riwayat Pinjam - Ventura',
            'transaksi' => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'      => $keyword,
            'tgl'       => $tgl_filter
        ];

        return view('users/riwayat', $data);
    }

    /**
     * HAPUS RIWAYAT (SISI USER)
     * User hanya bisa menghapus catatan transaksi jika statusnya sudah Selesai atau Batal
     */
    public function hapus_riwayat($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi && ($transaksi['status_transaksi'] == 'Selesai' || $transaksi['status_transaksi'] == 'Dibatalkan')) {
            $this->transaksiModel->delete($id);
            return redirect()->back()->with('success', 'Riwayat transaksi berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus! Hanya status Selesai/Batal yang bisa dihapus.');
    }

    /**
     * HALAMAN KELOLA TRANSAKSI (SISI ADMIN)
     * Bagian terpenting: Menampilkan semua transaksi sekaligus menghitung denda secara otomatis
     */
    public function kelola()
    {
        // --- LOGIKA DENDA OTOMATIS START ---
        $tgl_sekarang = time(); // Ambil waktu server saat ini
        $tarif_denda = 10000;   // Denda per hari

        // Ambil transaksi yang statusnya masih 'Dipinjam' dan dendanya belum dibayar
        $cek_telat = $this->transaksiModel->where('status_transaksi', 'Dipinjam')
            ->where('status_denda', 0)
            ->findAll();

        foreach ($cek_telat as $t) {
            $tgl_kembali_harusnya = strtotime($t['tgl_kembali']);

            // Jika waktu sekarang sudah melewati tanggal yang seharusnya kembali
            if ($tgl_sekarang > $tgl_kembali_harusnya) {
                $selisih_detik = $tgl_sekarang - $tgl_kembali_harusnya;
                $selisih_hari = floor($selisih_detik / (60 * 60 * 24)); // Konversi detik ke hari

                if ($selisih_hari > 0) {
                    $total_denda = $selisih_hari * $tarif_denda;

                    // Update database jika hitungan denda baru berbeda dengan yang lama
                    if ($t['denda'] != $total_denda) {
                        $this->transaksiModel->update($t['id_transaksi'], [
                            'denda' => $total_denda
                        ]);
                    }
                }
            }
        }
        // --- LOGIKA DENDA OTOMATIS END ---

        $keyword = $this->request->getVar('cari');
        $tgl_filter = $this->request->getVar('tgl');

        // Query untuk admin menampilkan data lengkap user dan barang
        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.nama as nama_user, users.no_wa')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user');

        if ($tgl_filter) $query->where('transaksi.tgl_pinjam', $tgl_filter);

        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('users.nama', $keyword)
                ->orLike('transaksi.status_transaksi', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'     => 'Kelola Transaksi - Admin',
            'transaksi' => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'      => $keyword,
            'tgl'       => $tgl_filter
        ];

        return view('admin/transaksi/index', $data);
    }

    /**
     * PROSES BOOKING (SISI USER)
     * Menghitung total harga berdasarkan durasi hari dan menyimpan bukti bayar
     */
    public function simpan()
    {
        $id_barang   = $this->request->getPost('id_barang');
        $tgl_pinjam  = $this->request->getPost('tgl_pinjam');
        $tgl_kembali = $this->request->getPost('tgl_kembali');

        $barang = $this->barangModel->find($id_barang);
        $start  = strtotime($tgl_pinjam);
        $end    = strtotime($tgl_kembali);
        $durasi = ($end - $start) / (60 * 60 * 24); // Hitung selisih hari
        if ($durasi <= 0) $durasi = 1; // Minimal sewa 1 hari

        $total_harga = $durasi * $barang['harga_sewa'];

        // Menangani upload bukti pembayaran
        $fileBukti = $this->request->getFile('bukti_bayar');
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaFoto = $fileBukti->getRandomName();
            $fileBukti->move(ROOTPATH . 'uploads/bukti_bayar/', $namaFoto);
        } else {
            return redirect()->back()->with('error', 'Bukti pembayaran wajib diunggah.');
        }

        // Simpan data transaksi baru ke database
        $this->transaksiModel->save([
            'id_user'           => session()->get('id_user'),
            'id_barang'         => $id_barang,
            'tgl_pinjam'        => $tgl_pinjam,
            'tgl_kembali'       => $tgl_kembali,
            'total_harga'       => $total_harga,
            'bukti_bayar'       => $namaFoto,
            'denda'             => 0,
            'status_denda'      => 0,
            'status_transaksi'  => 'Waiting', // Status awal menunggu konfirmasi admin
            'is_read'           => 0
        ]);

        return redirect()->to('/riwayat')->with('success', 'Booking berhasil! Tunggu konfirmasi admin.');
    }

    /**
     * KONFIRMASI BAYAR (ADMIN)
     * Mengubah status menjadi Booking dan OTOMATIS mengurangi stok barang
     */
    public function konfirmasi_bayar($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi) {
            $this->transaksiModel->update($id, ['status_transaksi' => 'Booking']);
            // Mengurangi stok barang sebanyak 1 (menggunakan raw query agar akurat)
            $this->barangModel->where('id_barang', $transaksi['id_barang'])
                ->set('stok', 'stok - 1', FALSE)
                ->update();
            return redirect()->back()->with('success', 'Pembayaran dikonfirmasi dan stok telah dikurangi.');
        }
        return redirect()->back()->with('error', 'Gagal konfirmasi! Data tidak ditemukan.');
    }

    /**
     * UPDATE STATUS UMUM (ADMIN)
     * Mengubah status transaksi, jika Selesai maka OTOMATIS mengembalikan stok barang
     */
    public function updateStatus($id, $status)
    {
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi) {
            $this->transaksiModel->update($id, ['status_transaksi' => $status]);
            if ($status == 'Selesai') {
                // Mengembalikan stok barang karena barang sudah kembali ke gudang
                $this->barangModel->where('id_barang', $transaksi['id_barang'])
                    ->set('stok', 'stok + 1', FALSE)
                    ->update();
            }
            return redirect()->back()->with('success', 'Status diperbarui.');
        }
        return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
    }

    /**
     * HITUNG DENDA MANUAL (ADMIN)
     * Tombol manual untuk memaksa sistem menghitung denda saat itu juga
     */
    public function hitungDenda($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        $tgl_kembali_seharusnya = strtotime($transaksi['tgl_kembali']);
        $tgl_sekarang = time();

        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $selisih = floor(($tgl_sekarang - $tgl_kembali_seharusnya) / (60 * 60 * 24));
            $denda = $selisih * 10000;
            $this->transaksiModel->update($id, ['denda' => $denda]);
        }
        return redirect()->back()->with('success', 'Denda berhasil diperbarui.');
    }

    /**
     * LUNASKAN DENDA (ADMIN)
     * Menandai denda sudah dibayar (Lunas)
     */
    public function lunaskan_denda($id)
    {
        $this->transaksiModel->update($id, ['status_denda' => 1]);
        return redirect()->back()->with('success', 'Denda telah ditandai Lunas.');
    }

    /**
     * TANDAI SUDAH DIBACA (ADMIN)
     * Menghilangkan notifikasi pesanan baru untuk admin
     */
    public function markAsRead($id)
    {
        $this->transaksiModel->update($id, ['is_read' => 1]);
        return redirect()->back();
    }




    /**
     * HAPUS DATA (ADMIN)
     * Menghapus baris transaksi secara permanen
     */
    public function delete($id)
    {
        $this->transaksiModel->delete($id);
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    /**
     * HALAMAN EDIT (ADMIN)
     * Menampilkan form edit transaksi untuk mengubah rincian secara manual
     */
    public function edit($id)
    {
        $transaksi = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.username, users.nama as nama_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->where('transaksi.id_transaksi', $id)
            ->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $data = [
            'title'     => 'Edit Transaksi',
            'transaksi' => $transaksi
        ];
        return view('admin/transaksi/edit', $data);
    }

    /**
     * PROSES UPDATE (ADMIN)
     * Menyimpan perubahan dari form edit ke database
     */
    public function update($id)
    {
        $this->transaksiModel->update($id, $this->request->getPost());
        return redirect()->to('admin/transaksi')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * PEMBATALAN BOOKING (ADMIN)
     * Membatalkan transaksi dan mengembalikan stok barang yang sebelumnya sudah dikunci
     */
    public function batal($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi && $transaksi['status_transaksi'] == 'Booking') {
            $barang = $this->barangModel->find($transaksi['id_barang']);
            if ($barang) {
                // Kembalikan stok karena batal dipinjam
                $this->barangModel->update($transaksi['id_barang'], ['stok' => $barang['stok'] + 1]);
            }
            $this->transaksiModel->update($id, ['status_transaksi' => 'Dibatalkan']);
            return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
        }
        return redirect()->back()->with('error', 'Gagal membatalkan booking.');
    }
}
