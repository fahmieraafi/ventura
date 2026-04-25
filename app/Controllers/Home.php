<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * FUNGSI INDEX (Halaman Dashboard Utama)
     * Mengumpulkan semua statistik dari database untuk ditampilkan di dashboard.
     */
    public function index()
    {
        // 1. Koneksi ke Database menggunakan Query Builder CI4
        $db = \Config\Database::connect();

        // 2. Mengambil data identitas dari Session yang sedang login
        $id_user   = session()->get('id_user');
        $username = session()->get('username');
        $role     = session()->get('role');

        /**
         * 3. LOGIKA NOTIFIKASI USER: 
         * Menghitung total denda milik user tersebut yang statusnya belum dibayar (0).
         */
        $total_denda = $db->table('transaksi')
            ->selectSum('denda')
            ->where('id_user', $id_user)
            ->where('status_denda', 0)
            ->get()->getRow()->denda;

        /**
         * 3.1 LOGIKA NOTIFIKASI ADMIN:
         * Bagian ini khusus untuk Admin guna memantau pesanan masuk dan keterlambatan.
         */
        $pesanan_baru_count = 0;
        $total_terlambat = 0; // Inisialisasi variabel telat

        if ($role == 'admin' || $role == 'Admin') {
            // Hitung pesanan yang belum dibaca (is_read = 0) oleh Admin
            $pesanan_baru_count = $db->table('transaksi')
                ->where('is_read', 0)
                ->countAllResults();

            // Hitung transaksi yang telat (Status masih 'Dipinjam' tapi sudah lewat tanggal kembali)
            $today = date('Y-m-d');
            $total_terlambat = $db->table('transaksi')
                ->where('status_transaksi', 'Dipinjam')
                ->where('tgl_kembali <', $today)
                ->countAllResults();
        }

        /**
         * 4. LOGIKA KATEGORI OTOMATIS: 
         * Mengelompokkan barang berdasarkan kategori dan mengambil satu contoh foto untuk cover kartu kategori.
         */
        $rincianKategori = $db->table('barang')
            ->select('kategori, COUNT(*) as total, MAX(foto_barang) as foto_barang')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        /**
         * 5. LOGIKA GRAFIK PENDAPATAN TAHUNAN (Line Chart):
         * Menghitung uang masuk tiap bulan di tahun berjalan.
         * Aturan: Selesai = (Biaya Admin 15rb + Harga Sewa + Denda jika lunas).
         * Status lain (Booking/Dipinjam/Batal) = Flat 15rb (Biaya Admin/DP).
         */
        $dataPendapatan = $db->table('transaksi')
            ->select('MONTH(tgl_pinjam) as bulan')
            ->select('SUM(
                CASE 
                    WHEN status_transaksi = "Selesai" THEN (15000 + total_harga + IF(status_denda = 1, denda, 0))
                    WHEN status_transaksi IN ("Booking", "Dipinjam", "Dibatalkan") THEN 15000
                    ELSE 0 
                END
            ) as total')
            ->where('YEAR(tgl_pinjam)', date('Y')) // Hanya tahun ini
            ->whereIn('status_transaksi', ['Selesai', 'Booking', 'Dipinjam', 'Dibatalkan'])
            ->groupBy('MONTH(tgl_pinjam)')
            ->get()->getResultArray();

        // Menyusun array 12 bulan (Jan-Des) dengan nilai awal 0 agar grafik tidak error jika ada bulan kosong
        $grafik = array_fill(0, 12, 0);
        foreach ($dataPendapatan as $row) {
            $grafik[$row['bulan'] - 1] = (int)$row['total'];
        }

        /**
         * 5.1 LOGIKA TOTAL PENDAPATAN KESELURUHAN:
         * Menjumlahkan semua uang masuk dari awal hingga sekarang berdasarkan status transaksi.
         */
        $resPendapatan = $db->table('transaksi')
            ->select('SUM(
                CASE 
                    WHEN status_transaksi = "Selesai" THEN (15000 + total_harga + IF(status_denda = 1, denda, 0))
                    WHEN status_transaksi IN ("Booking", "Dipinjam", "Dibatalkan") THEN 15000
                    ELSE 0 
                END
            ) as grand_total')
            ->get()->getRow();

        $totalSeluruhPendapatan = $resPendapatan->grand_total ?? 0;

        /**
         * 5.2 LOGIKA GRAFIK PIE (Bulan Ini): 
         * Melihat kategori barang mana yang paling banyak menghasilkan uang di bulan ini.
         */
        $pendapatanPie = $db->table('transaksi')
            ->select('barang.kategori')
            ->select('SUM(
                CASE 
                    WHEN transaksi.status_transaksi = "Selesai" THEN (15000 + transaksi.total_harga + IF(transaksi.status_denda = 1, transaksi.denda, 0))
                    WHEN transaksi.status_transaksi IN ("Booking", "Dipinjam", "Dibatalkan") THEN 15000
                    ELSE 0 
                END
            ) as total')
            ->join('barang', 'barang.id_barang = transaksi.id_barang') // Gabung tabel barang untuk ambil nama kategori
            ->where('MONTH(transaksi.tgl_pinjam)', date('m'))
            ->where('YEAR(transaksi.tgl_pinjam)', date('Y'))
            ->whereIn('transaksi.status_transaksi', ['Selesai', 'Booking', 'Dipinjam', 'Dibatalkan'])
            ->groupBy('barang.kategori')
            ->get()->getResultArray();

        $label_pie = array_column($pendapatanPie, 'kategori');
        $data_pie  = array_map('intval', array_column($pendapatanPie, 'total'));

        // Jika bulan ini belum ada transaksi, buat label 'Belum Ada Data' agar grafik tidak kosong melompong
        if (empty($label_pie)) {
            $label_pie = ['Belum Ada Data'];
            $data_pie  = [0];
        }

        /**
         * --- LOGIKA REKOMENDASI & TRANSAKSI USER ---
         */
        // Ambil 4 barang yang paling sering dilihat (populer)
        $rekomendasiBarang = $db->table('barang')
            ->orderBy('views', 'DESC')
            ->limit(4)
            ->get()->getResultArray();

        $transaksiAktif = [];
        // Khusus User: Tampilkan barang apa saja yang sedang mereka pinjam saat ini
        if ($role == 'user' || $role == 'User') {
            $transaksiAktif = $db->table('transaksi')
                ->select('transaksi.*, barang.nama_barang')
                ->join('barang', 'barang.id_barang = transaksi.id_barang')
                ->where('transaksi.id_user', $id_user)
                ->where('transaksi.status_transaksi', 'Dipinjam')
                ->get()->getResultArray();
        }

        // Hitung ulang total terlambat (untuk memastikan akurasi data yang dikirim ke view)
        $today = date('Y-m-d');
        $total_terlambat = $db->table('transaksi')
            ->where('status_transaksi', 'Dipinjam')
            ->where('tgl_kembali <', $today)
            ->countAllResults();

        /**
         * 6. MENYIAPKAN ARRAY DATA:
         * Mengumpulkan semua hasil hitungan di atas ke dalam satu variabel $data untuk dikirim ke View.
         */
        $data = [
            'title'              => 'Dashboard Utama',
            'totalBarang'        => $db->table('barang')->countAll(),
            'totalUser'          => $db->table('users')->countAll(),
            'rincianKategori'    => $rincianKategori,
            'total_denda'        => $total_denda ?? 0,
            'pesanan_baru'       => $pesanan_baru_count,
            'total_terlambat'    => $total_terlambat,
            'pendapatan_bulanan' => json_encode($grafik), // Encode ke JSON agar dibaca oleh Chart.js
            'totalPendapatan'    => $totalSeluruhPendapatan,
            'rekomendasi_barang' => $rekomendasiBarang,
            'transaksi_aktif'    => $transaksiAktif,
            'label_pie'          => json_encode($label_pie),
            'data_pie'           => json_encode($data_pie),
        ];

        /**
         * 7. UPDATE INFO STOK:
         * Mengambil total stok fisik seluruh barang yang ada di gudang.
         */
        $modelBarang = new \App\Models\BarangModel();
        $data['totalBarang'] = $modelBarang->countAll();
        $data['totalStok']   = $modelBarang->selectSum('stok')->get()->getRow()->stok;

        /**
         * 8. RENDER VIEW:
         * Menampilkan file layouts/dashboard.php dengan membawa semua data di atas.
         */
        return view('layouts/dashboard', $data);
    }
}
