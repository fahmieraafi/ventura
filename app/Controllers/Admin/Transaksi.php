<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\BarangModel;

class Transaksi extends BaseController
{
    // Properti untuk menampung model agar bisa diakses di seluruh fungsi
    protected $transaksiModel;
    protected $barangModel;

    public function __construct()
    {
        // Inisialisasi model saat class pertama kali dipanggil
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel    = new BarangModel();
    }

    /**
     * FUNGSI INDEX
     * Menampilkan daftar transaksi dan mengelola data notifikasi lonceng.
     */
    public function index()
    {
        // Mengambil input pencarian dari user (jika ada)
        $keyword = $this->request->getVar('cari');
        // Mendapatkan tanggal hari ini untuk pengecekan keterlambatan
        $today = date('Y-m-d');

        // Menyiapkan query dasar: mengambil data transaksi digabung dengan nama barang dan nama user
        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.nama as nama_user, users.no_wa')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user');

        // Jika user melakukan pencarian, tambahkan filter LIKE ke query
        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('users.nama', $keyword)
                ->orLike('transaksi.id_transaksi', $keyword)
                ->orLike('transaksi.status_transaksi', $keyword)
                ->groupEnd();
        }

        // --- LOGIKA UNTUK NOTIFIKASI LONCENG ---

        // 1. Ambil daftar user yang telat mengembalikan (tgl_kembali < hari ini & status masih dipinjam)
        $listTerlambat = $this->transaksiModel->select('transaksi.*, users.nama as nama_user, barang.nama_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('tgl_kembali <', $today)
            ->where('status_transaksi', 'Dipinjam')
            ->findAll();

        // 2. Ambil daftar pesanan baru (Booking) yang belum dikonfirmasi/dibaca oleh admin
        $notifList = $this->transaksiModel->select('transaksi.*, users.nama as nama_user, barang.nama_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('is_read', 0)
            ->where('status_transaksi', 'Booking')
            ->findAll();

        // Menyiapkan data yang akan dikirim ke halaman View
        $data = [
            'title'             => 'Kelola Transaksi - Admin Ventura',
            'transaksi'         => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'              => $keyword,

            // Mengirim data notifikasi ke Lonceng di Navbar
            'total_terlambat'   => count($listTerlambat),
            'list_terlambat'    => $listTerlambat,
            'notif_count'       => count($notifList),
            'notif_list'        => $notifList,

            // Menghitung jumlah barang yang sedang dibawa user (status Dipinjam)
            'barang_dipinjam'   => $this->transaksiModel->where('status_transaksi', 'Dipinjam')->countAllResults()
        ];

        // Memanggil file view index di folder admin/transaksi
        return view('admin/transaksi/index', $data);
    }

    /**
     * FUNGSI markAsRead
     * Digunakan untuk menghilangkan notifikasi merah di lonceng Admin.
     */
    public function markAsRead($id)
    {
        // Mengubah status kolom is_read menjadi 1 (sudah dibaca)
        $this->transaksiModel->update($id, ['is_read' => 1]);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus dari lonceng.');
    }

    /**
     * FUNGSI hitungDenda
     * Menghitung denda secara otomatis berdasarkan tarif Rp 20.000 per hari.
     */
    public function hitungDenda($id)
    {
        // Mencari data transaksi berdasarkan ID
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        // Konversi tanggal ke format angka (detik) untuk perhitungan matematika
        $tgl_kembali_seharusnya = strtotime($transaksi['tgl_kembali']);
        $tgl_sekarang = time();
        $denda = 0;
        $tarif_per_hari = 20000;

        // Jika waktu sekarang sudah melewati tanggal seharusnya kembali
        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $selisih_detik = $tgl_sekarang - $tgl_kembali_seharusnya;
            // Menghitung berapa hari keterlambatannya (detik dibagi jumlah detik dalam sehari)
            $keterlambatan = floor($selisih_detik / (60 * 60 * 24));

            if ($keterlambatan > 0) {
                $denda = $keterlambatan * $tarif_per_hari;
            }
        }

        // Menyimpan hasil perhitungan denda ke database
        $this->transaksiModel->update($id, [
            'denda' => $denda
        ]);

        return redirect()->to('admin/transaksi')->with('success', "Denda berhasil dihitung: Rp " . number_format($denda, 0, ',', '.'));
    }

    /**
     * FUNGSI updateStatus
     * Fungsi utama untuk alur barang (Booking -> Dipinjam -> Selesai).
     */
    public function updateStatus($id, $status)
    {
        // Cari transaksi yang dimaksud
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $id_barang = $transaksi['id_barang'];
        $dataUpdate = ['status_transaksi' => $status];

        // LOGIKA: Jika barang diambil (Dipinjam), stok di gudang berkurang 1
        if ($status == 'Dipinjam') {
            $barang = $this->barangModel->find($id_barang);
            if ($barang) {
                $this->barangModel->update($id_barang, [
                    'stok' => $barang['stok'] - 1
                ]);
            }
        }
        // LOGIKA: Jika barang dipulangkan (Selesai), hitung denda akhir dan tambah stok gudang 1
        elseif ($status == 'Selesai') {
            $tgl_kembali_seharusnya = strtotime($transaksi['tgl_kembali']);
            $tgl_sekarang = time();
            $denda = 0;
            $tarif_per_hari = 20000;

            if ($tgl_sekarang > $tgl_kembali_seharusnya) {
                $selisih_detik = $tgl_sekarang - $tgl_kembali_seharusnya;
                $keterlambatan = floor($selisih_detik / (60 * 60 * 24));
                if ($keterlambatan > 0) $denda = $keterlambatan * $tarif_per_hari;
            }

            // Mencatat data pengembalian
            $dataUpdate['tgl_dikembalikan']  = date('Y-m-d');
            $dataUpdate['denda']             = $denda;
            $dataUpdate['is_read']           = 1; // Otomatis hilangkan dari notifikasi booking

            // Menambah stok barang kembali karena barang sudah ada di gudang
            $barang = $this->barangModel->find($id_barang);
            if ($barang) {
                $this->barangModel->update($id_barang, [
                    'stok' => $barang['stok'] + 1
                ]);
            }
        }

        // Eksekusi update status transaksi
        $this->transaksiModel->update($id, $dataUpdate);
        return redirect()->to('admin/transaksi')->with('success', "Status berhasil diperbarui menjadi $status");
    }

    /**
     * FUNGSI edit
     * Menampilkan form edit untuk penyesuaian manual data transaksi.
     */
    public function edit($id)
    {
        // Mengambil data detail transaksi untuk ditampilkan di form
        $transaksi = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.nama as nama_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->find($id);

        if (!$transaksi) return redirect()->to('admin/transaksi')->with('error', 'Data tidak ditemukan');

        $data = [
            'title'     => 'Edit Transaksi - Ventura',
            'transaksi' => $transaksi
        ];

        return view('admin/transaksi/edit', $data);
    }

    /**
     * FUNGSI update
     * Menyimpan hasil edit manual (misalnya jika denda diubah manual oleh admin).
     */
    public function update($id)
    {
        $transaksiLama = $this->transaksiModel->find($id);
        $statusBaru = $this->request->getPost('status_transaksi');

        // Logika penyesuaian stok jika status diubah manual lewat form edit
        if ($transaksiLama['status_transaksi'] != 'Selesai' && $statusBaru == 'Selesai') {
            $barang = $this->barangModel->find($transaksiLama['id_barang']);
            $this->barangModel->update($transaksiLama['id_barang'], ['stok' => $barang['stok'] + 1]);
        } elseif ($transaksiLama['status_transaksi'] == 'Selesai' && $statusBaru == 'Dipinjam') {
            $barang = $this->barangModel->find($transaksiLama['id_barang']);
            $this->barangModel->update($transaksiLama['id_barang'], ['stok' => $barang['stok'] - 1]);
        }

        // Menyimpan data denda dan status baru
        $this->transaksiModel->update($id, [
            'denda'             => $this->request->getPost('denda'),
            'status_transaksi'  => $statusBaru
        ]);

        return redirect()->to('admin/transaksi')->with('success', 'Data transaksi berhasil diupdate manual!');
    }

    /**
     * FUNGSI delete
     * Menghapus data transaksi (digunakan jika ada pembatalan permanen atau data sampah).
     */
    public function delete($id)
    {
        $this->transaksiModel->delete($id);
        return redirect()->to('admin/transaksi')->with('success', 'Data transaksi berhasil dihapus!');
    }
}
