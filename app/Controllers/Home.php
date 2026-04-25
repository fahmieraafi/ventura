<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // 1. Koneksi ke Database menggunakan Query Builder
        $db = \Config\Database::connect();

        // 2. Mengambil ID User dan Role dari Session
        $id_user   = session()->get('id_user');
        $username = session()->get('username');
        $role     = session()->get('role');

        /**
         * 3. LOGIKA NOTIFIKASI USER: Menghitung total nominal denda.
         */
        $total_denda = $db->table('transaksi')
            ->selectSum('denda')
            ->where('id_user', $id_user)
            ->where('status_denda', 0)
            ->get()->getRow()->denda;

        /**
         * 3.1 LOGIKA NOTIFIKASI ADMIN: Menghitung pesanan baru.
         */
        $pesanan_baru_count = 0;
        if ($role == 'admin' || $role == 'Admin') {
            $pesanan_baru_count = $db->table('transaksi')
                ->where('is_read', 0)
                ->countAllResults();

            /**
             * 3.1 LOGIKA NOTIFIKASI ADMIN: Menghitung pesanan baru.
             */
            $pesanan_baru_count = 0;
            $total_terlambat = 0; // Inisialisasi variabel telat

            if ($role == 'admin' || $role == 'Admin') {
                $pesanan_baru_count = $db->table('transaksi')
                    ->where('is_read', 0)
                    ->countAllResults();

                // TAMBAHKAN INI: Hitung transaksi yang telat
                $today = date('Y-m-d');
                $total_terlambat = $db->table('transaksi')
                    ->where('status_transaksi', 'Dipinjam')
                    ->where('tgl_kembali <', $today)
                    ->countAllResults();
            }
        }

        /**
         * 4. LOGIKA OTOMATIS: Mengambil rincian per kategori.
         */
        $rincianKategori = $db->table('barang')
            ->select('kategori, COUNT(*) as total, MAX(foto_barang) as foto_barang')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        /**
/**
        /**
         * 5. LOGIKA GRAFIK PENDAPATAN TAHUNAN (Line Chart):
         * Selesai = Total Harga + Denda Lunas
         * Booking, Dipinjam, Dibatalkan = Flat 15.000
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
            ->where('YEAR(tgl_pinjam)', date('Y'))
            ->whereIn('status_transaksi', ['Selesai', 'Booking', 'Dipinjam', 'Dibatalkan'])
            ->groupBy('MONTH(tgl_pinjam)')
            ->get()->getResultArray();

        $grafik = array_fill(0, 12, 0);
        foreach ($dataPendapatan as $row) {
            $grafik[$row['bulan'] - 1] = (int)$row['total'];
        }

        /**
        /**
       /**
         * 5.1 LOGIKA TOTAL PENDAPATAN KESELURUHAN:
         * Menggunakan satu query CASE agar hasil sinkron dengan grafik.
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
       /**
         * 5.2 MODIFIKASI GRAFIK PIE: 
         * Menghitung pendapatan per kategori bulan ini sesuai aturan status.
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
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('MONTH(transaksi.tgl_pinjam)', date('m'))
            ->where('YEAR(transaksi.tgl_pinjam)', date('Y'))
            ->whereIn('transaksi.status_transaksi', ['Selesai', 'Booking', 'Dipinjam', 'Dibatalkan'])
            ->groupBy('barang.kategori')
            ->get()->getResultArray();

        $label_pie = array_column($pendapatanPie, 'kategori');
        $data_pie  = array_map('intval', array_column($pendapatanPie, 'total'));

        // Fallback jika data Pie kosong
        if (empty($label_pie)) {
            $label_pie = ['Belum Ada Data'];
            $data_pie  = [0];
        }

        /**
         * --- LOGIKA VIEW TRACKER & TRANSAKSI AKTIF ---
         */
        $rekomendasiBarang = $db->table('barang')
            ->orderBy('views', 'DESC')
            ->limit(4)
            ->get()->getResultArray();

        $transaksiAktif = [];
        if ($role == 'user' || $role == 'User') {
            $transaksiAktif = $db->table('transaksi')
                ->select('transaksi.*, barang.nama_barang')
                ->join('barang', 'barang.id_barang = transaksi.id_barang')
                ->where('transaksi.id_user', $id_user)
                ->where('transaksi.status_transaksi', 'Dipinjam')
                ->get()->getResultArray();
        }



        $today = date('Y-m-d');
        $total_terlambat = $db->table('transaksi')
            ->where('status_transaksi', 'Dipinjam')
            ->where('tgl_kembali <', $today)
            ->countAllResults();
        /**
         * 6. Menyiapkan data untuk dikirim ke View Dashboard.
         */
        $data = [
            'title'              => 'Dashboard Utama',
            'totalBarang'        => $db->table('barang')->countAll(),
            'totalUser'          => $db->table('users')->countAll(),
            'rincianKategori'    => $rincianKategori,
            'total_denda'        => $total_denda ?? 0,
            'pesanan_baru'       => $pesanan_baru_count,
            'total_terlambat'    => $total_terlambat,
            'pendapatan_bulanan' => json_encode($grafik),
            'totalPendapatan'    => $totalSeluruhPendapatan,
            'rekomendasi_barang' => $rekomendasiBarang,
            'transaksi_aktif'    => $transaksiAktif,
            'label_pie'          => json_encode($label_pie),
            'data_pie'           => json_encode($data_pie),
        ];



        /**
         * 7. Mengambil info stok dari Model Barang.
         */
        $modelBarang = new \App\Models\BarangModel();
        $data['totalBarang'] = $modelBarang->countAll();
        $data['totalStok']   = $modelBarang->selectSum('stok')->get()->getRow()->stok;

        /**
         * 8. Mengirim data ke View Dashboard.
         */
        // Pastikan nama view ini sesuai dengan file .php kamu (home atau layouts/dashboard)
        return view('layouts/dashboard', $data);
    }
}
