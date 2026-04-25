<?php

// Menentukan namespace agar file ini dikenali oleh sistem CodeIgniter di folder Models
namespace App\Models;

// Mengambil class Model dasar dari sistem CodeIgniter 4
use CodeIgniter\Model;

class TransaksiModel extends Model
{
    // Nama tabel yang digunakan di database untuk menyimpan data penyewaan
    protected $table            = 'transaksi';

    // Nama kolom kunci utama (Primary Key) sebagai identitas unik setiap transaksi
    protected $primaryKey       = 'id_transaksi';

    // Mengatur agar ID bertambah otomatis secara berurutan (1, 2, 3, dst)
    protected $useAutoIncrement = true;

    // Menentukan format data yang dihasilkan dari query adalah Array (agar mudah di-looping di View)
    protected $returnType       = 'array';

    /**
     * ALLOWED FIELDS (Kolom yang Diizinkan)
     * Daftar kolom di tabel database yang boleh diisi atau diubah.
     * Jika kolom tidak ada di sini, CodeIgniter akan memblokir data tersebut demi keamanan.
     */
    protected $allowedFields    = [
        'id_user',          // ID pelanggan yang menyewa
        'id_barang',        // ID barang yang disewa
        'tgl_pinjam',       // Tanggal mulai sewa
        'tgl_kembali',      // Tanggal jatuh tempo pengembalian
        'total_harga',      // Total biaya sewa yang harus dibayar
        'denda',            // Nominal denda jika terlambat mengembalikan
        'status_denda',     // Status apakah denda sudah lunas atau belum
        'status_transaksi', // Tahapan sewa: 'Booking' (Pesan), 'Dipinjam', atau 'Selesai'
        'is_read',          // Status notifikasi untuk admin (0: Belum dilihat, 1: Sudah dilihat)
        'bukti_bayar'       // Menyimpan nama file foto bukti transfer pembayaran
    ];

    /**
     * TIMESTAMPS
     * Fitur otomatis untuk mencatat kapan transaksi dibuat dan kapan terakhir kali datanya diubah.
     */
    protected $useTimestamps = true;              // Mengaktifkan fitur waktu otomatis
    protected $createdField  = 'created_at';      // Nama kolom untuk waktu pembuatan data
    protected $updatedField  = 'updated_at';      // Nama kolom untuk waktu pembaruan data

    /**
     * FUNGSI getTransaksiLengkap
     * Fungsi buatan sendiri untuk mengambil data transaksi secara mendalam (Detail).
     * Fungsi ini menggabungkan 3 tabel sekaligus (Transaksi, Barang, Users).
     */
    public function getTransaksiLengkap($id_user = null)
    {
        // Memulai query builder pada tabel transaksi (sebagai tabel utama)
        $builder = $this->db->table($this->table);

        /**
         * Memilih kolom spesifik:
         * - Semua kolom dari tabel transaksi
         * - Nama barang dan harga sewa dari tabel barang
         * - Nama dan No WA pelanggan dari tabel users
         */
        $builder->select('transaksi.*, barang.nama_barang, barang.harga_sewa, users.nama as nama_user, users.no_wa');

        // JOIN 1: Menghubungkan tabel transaksi dengan tabel barang (berdasarkan ID Barang)
        $builder->join('barang', 'barang.id_barang = transaksi.id_barang');

        // JOIN 2: Menghubungkan tabel transaksi dengan tabel users (berdasarkan ID User)
        $builder->join('users', 'users.id_user = transaksi.id_user');

        /**
         * FILTER USER:
         * Jika fungsi ini dipanggil dengan membawa ID User (misal: saat user melihat riwayatnya sendiri),
         * maka sistem hanya akan menampilkan transaksi miliknya saja.
         */
        if ($id_user) {
            $builder->where('transaksi.id_user', $id_user);
        }

        // Mengurutkan data agar transaksi yang paling baru berada di urutan paling atas (Terbaru ke Terlama)
        $builder->orderBy('transaksi.id_transaksi', 'DESC');

        // Menjalankan perintah SQL di atas dan mengembalikan hasilnya dalam bentuk array sekumpulan data
        return $builder->get()->getResultArray();
    }
}
