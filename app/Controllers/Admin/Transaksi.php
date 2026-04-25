<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\BarangModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $barangModel;

    public function __construct()
    {
        // Menyiapkan model agar bisa digunakan di semua fungsi dalam class ini
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel    = new BarangModel();
    }

    /**
     * Menampilkan daftar transaksi di halaman Admin.
     * Sudah termasuk fitur pencarian berdasarkan nama user atau barang.
     */
    public function index()
    {
        $keyword = $this->request->getVar('cari');
        $today = date('Y-m-d');

        // Query utama untuk tabel transaksi
        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.nama as nama_user, users.no_wa')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user');

        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('users.nama', $keyword)
                ->orLike('transaksi.id_transaksi', $keyword)
                ->orLike('transaksi.status_transaksi', $keyword)
                ->groupEnd();
        }

        // --- LOGIKA UNTUK NOTIFIKASI LONCENG ---

        // 1. Ambil DAFTAR orang yang terlambat (untuk dimunculkan di dropdown)
        $listTerlambat = $this->transaksiModel->select('transaksi.*, users.nama as nama_user, barang.nama_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('tgl_kembali <', $today)
            ->where('status_transaksi', 'Dipinjam')
            ->findAll();

        // 2. Ambil DAFTAR pesanan baru (Booking) yang belum dibaca admin
        $notifList = $this->transaksiModel->select('transaksi.*, users.nama as nama_user, barang.nama_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('is_read', 0)
            ->where('status_transaksi', 'Booking')
            ->findAll();

        $data = [
            'title'             => 'Kelola Transaksi - Admin Ventura',
            'transaksi'         => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'              => $keyword,

            // Data untuk Lonceng Navbar
            'total_terlambat'   => count($listTerlambat),
            'list_terlambat'    => $listTerlambat, // WAJIB ADA agar bisa di-foreach di View
            'notif_count'       => count($notifList),
            'notif_list'        => $notifList,     // WAJIB ADA

            'barang_dipinjam'   => $this->transaksiModel->where('status_transaksi', 'Dipinjam')->countAllResults()
        ];

        return view('admin/transaksi/index', $data);
    }

    /**
     * FUNGSI NOTIFIKASI: Menandai notifikasi sebagai "sudah dibaca" oleh admin.
     * Fungsi inilah yang akan menghilangkan angka di lonceng Admin saat tombol centang diklik.
     */
    public function markAsRead($id)
    {
        // Mengubah kolom is_read menjadi 1 agar tidak muncul lagi di notifikasi lonceng Admin
        $this->transaksiModel->update($id, ['is_read' => 1]);

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus dari lonceng.');
    }

    /**
     * Menghitung denda secara otomatis tanpa mengubah status transaksi.
     * Berguna jika admin ingin menginfokan denda sementara barang masih dipinjam.
     */
    public function hitungDenda($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $tgl_kembali_seharusnya = strtotime($transaksi['tgl_kembali']);
        $tgl_sekarang = time();
        $denda = 0;
        $tarif_per_hari = 20000;

        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $selisih_detik = $tgl_sekarang - $tgl_kembali_seharusnya;
            $keterlambatan = floor($selisih_detik / (60 * 60 * 24));

            if ($keterlambatan > 0) {
                $denda = $keterlambatan * $tarif_per_hari;
            }
        }

        // Simpan denda, status transaksi tidak berubah (tetap dipinjam/booking)
        $this->transaksiModel->update($id, [
            'denda' => $denda
        ]);

        return redirect()->to('admin/transaksi')->with('success', "Denda berhasil dihitung: Rp " . number_format($denda, 0, ',', '.'));
    }

    /**
     * Mengubah status transaksi secara cepat (tombol Ambil/Selesai/Dipinjam).
     * Juga menangani penambahan/pengurangan stok barang secara otomatis.
     */
    public function updateStatus($id, $status)
    {
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) return redirect()->back()->with('error', 'Transaksi tidak ditemukan');

        $id_barang = $transaksi['id_barang'];
        $dataUpdate = ['status_transaksi' => $status];

        // Jika status diubah ke Dipinjam, kurangi stok barang
        if ($status == 'Dipinjam') {

            $barang = $this->barangModel->find($id_barang);
            if ($barang) {
                $this->barangModel->update($id_barang, [
                    'stok' => $barang['stok'] - 1
                ]);
            }
        }
        // Jika status diubah ke Selesai, hitung denda lunas dan kembalikan stok
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

            $dataUpdate['tgl_dikembalikan']  = date('Y-m-d');
            $dataUpdate['denda']             = $denda;
            $dataUpdate['is_read']           = 1; // Otomatis tandai dibaca jika sudah selesai

            $barang = $this->barangModel->find($id_barang);
            if ($barang) {
                $this->barangModel->update($id_barang, [
                    'stok' => $barang['stok'] + 1
                ]);
            }
        }

        $this->transaksiModel->update($id, $dataUpdate);
        return redirect()->to('admin/transaksi')->with('success', "Status berhasil diperbarui menjadi $status");
    }

    /**
     * Menampilkan halaman form edit transaksi secara manual.
     */
    public function edit($id)
    {
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
     * Menyimpan perubahan dari form edit manual (termasuk input denda manual).
     */
    public function update($id)
    {
        $transaksiLama = $this->transaksiModel->find($id);
        $statusBaru = $this->request->getPost('status_transaksi');

        // Mengatur stok barang jika admin mengubah status secara manual lewat form edit
        if ($transaksiLama['status_transaksi'] != 'Selesai' && $statusBaru == 'Selesai') {
            $barang = $this->barangModel->find($transaksiLama['id_barang']);
            $this->barangModel->update($transaksiLama['id_barang'], ['stok' => $barang['stok'] + 1]);
        } elseif ($transaksiLama['status_transaksi'] == 'Selesai' && $statusBaru == 'Dipinjam') {
            $barang = $this->barangModel->find($transaksiLama['id_barang']);
            $this->barangModel->update($transaksiLama['id_barang'], ['stok' => $barang['stok'] - 1]);
        }

        $this->transaksiModel->update($id, [
            'denda'             => $this->request->getPost('denda'),
            'status_transaksi'  => $statusBaru
        ]);

        return redirect()->to('admin/transaksi')->with('success', 'Data transaksi berhasil diupdate manual!');
    }

    /**
     * Menghapus data transaksi dari database secara permanen.
     */
    public function delete($id)
    {
        $this->transaksiModel->delete($id);
        return redirect()->to('admin/transaksi')->with('success', 'Data transaksi berhasil dihapus!');
    }
}
