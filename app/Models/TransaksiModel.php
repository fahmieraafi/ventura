<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    // Nama tabel yang digunakan di database
    protected $table            = 'transaksi';

    // Nama kolom kunci utama (Primary Key)
    protected $primaryKey       = 'id_transaksi';

    // Mengatur agar ID bertambah otomatis (Auto Increment)
    protected $useAutoIncrement = true;

    // Format data yang dikembalikan (Array agar mudah diolah di view)
    protected $returnType       = 'array';

    /**
     * White-list kolom yang boleh dimanipulasi (Insert/Update).
     * Jika kolom tidak ada di sini, CI4 akan menolak proses simpan data.
     */
    protected $allowedFields    = [
        'id_user',
        'id_barang',
        'tgl_pinjam',
        'tgl_kembali',
        'total_harga',
        'denda',
        'status_denda',         // Untuk menyimpan nominal denda keterlambatan
        'status_transaksi',  // Status barang (Booking/Dipinjam/Selesai)
        'is_read',           // Digunakan agar fitur "Tandai Dibaca" di lonceng Admin tidak error
        'bukti_bayar'        // TAMBAHAN: Agar foto bukti transfer 15rb bisa disimpan ke database
    ];

    // Mengaktifkan fitur pencatatan waktu otomatis (created_at & updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fungsi kustom untuk mengambil data transaksi secara detail.
     * Menggabungkan (JOIN) data dari tabel barang dan tabel users.
     * * @param int|null $id_user Jika diisi, hanya mengambil transaksi milik user tersebut (untuk Riwayat).
     * @return array List data transaksi lengkap.
     */
    public function getTransaksiLengkap($id_user = null)
    {
        // Memulai query builder pada tabel transaksi
        $builder = $this->db->table($this->table);

        // Memilih kolom mana saja yang ingin ditampilkan (termasuk kolom dari tabel hasil JOIN)
        $builder->select('transaksi.*, barang.nama_barang, barang.harga_sewa, users.nama as nama_user, users.no_wa');

        // Menghubungkan tabel transaksi dengan tabel barang berdasarkan id_barang
        $builder->join('barang', 'barang.id_barang = transaksi.id_barang');

        // Menghubungkan tabel transaksi dengan tabel users berdasarkan id_user
        $builder->join('users', 'users.id_user = transaksi.id_user');

        // Jika ada filter ID User, tambahkan kondisi WHERE (biasanya untuk tampilan Riwayat Pinjam User)
        if ($id_user) {
            $builder->where('transaksi.id_user', $id_user);
        }

        // Urutkan berdasarkan transaksi terbaru agar Admin mudah memantau pesanan masuk
        $builder->orderBy('transaksi.id_transaksi', 'DESC');

        // Eksekusi query dan kembalikan hasilnya dalam bentuk array
        return $builder->get()->getResultArray();
    }
}
