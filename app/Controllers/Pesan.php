<?php

namespace App\Controllers;

// Mengimpor Model Pesan untuk mengelola tabel pesan di database
use App\Models\PesanModel;
use CodeIgniter\Controller;

class Pesan extends BaseController
{
    protected $pesanModel;
    // Menentukan lokasi folder penyimpanan file chat (gambar/audio) secara privat
    protected $uploadPath = WRITEPATH . 'uploads/chat/';

    /**
     * KONSTRUKTOR
     * Menyiapkan model pesan agar bisa digunakan di semua fungsi dalam class ini
     */
    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    /**
     * HALAMAN UTAMA CHAT
     * Mengatur tampilan chat baik untuk sisi Admin maupun sisi User
     */
    public function index($id_lawan = null)
    {
        $id_saya = session()->get('id_user'); // Ambil ID akun yang sedang login
        $role    = session()->get('role');    // Cek apakah Admin atau User

        if ($role == 'admin') {
            // Jika Admin belum memilih orang untuk dichat, tampilkan daftar daftar chat (list user)
            if ($id_lawan === null) {
                $data['daftar_chat'] = $this->pesanModel->getDaftarChatAdmin();
                return view('pesanadmin/index', $data);
            }
            $id_untuk_history = $id_lawan; // History chat yang dibuka adalah milik user yang dipilih
        } else {
            // Jika User biasa, jika tidak ada lawan maka defaultnya kirim ke Admin (ID 0)
            if ($id_lawan === null) {
                $id_lawan = 0;
            }
            $id_untuk_history = $id_saya; // History chat yang diambil adalah milik user ini
        }

        // Otomatis ubah status pesan menjadi 'sudah dibaca' saat halaman chat dibuka
        $this->updateStatusBaca($id_lawan);

        // Ambil data percakapan dari database
        $data['semua_pesan'] = $this->pesanModel->getChatHistory($id_untuk_history);
        $data['id_lawan'] = $id_lawan;
        // Ambil informasi profil lawan chat (nama, foto, dll)
        $data['detail_lawan'] = $this->db->table('users')->where('id_user', $id_lawan)->get()->getRowArray();

        return view('pesan/index', $data);
    }

    /**
     * FUNGSI BACA (AJAX)
     * Dipanggil oleh JavaScript secara berkala untuk menandai pesan masuk sebagai 'sudah dibaca'
     */
    public function baca($id_lawan)
    {
        $this->updateStatusBaca($id_lawan);
        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * HELPER UPDATE STATUS BACA
     * Logika internal untuk mengubah status_baca dari 0 (belum) menjadi 1 (sudah)
     */
    private function updateStatusBaca($id_lawan)
    {
        $id_saya = session()->get('id_user');
        if (!$id_lawan && $id_lawan !== 0) return; // Jika lawan tidak jelas, berhenti

        // Update semua pesan di mana pengirimnya adalah lawan dan penerimanya adalah saya
        $this->pesanModel->where('id_pengirim', $id_lawan)
            ->where('id_penerima', $id_saya)
            ->where('status_baca', '0')
            ->set(['status_baca' => '1'])
            ->update();
    }

    /**
     * FUNGSI MENAMPILKAN GAMBAR
     * Karena file disimpan di WRITEPATH (luar folder public), fungsi ini bertugas mengambil file lalu menampilkannya
     */
    public function gambar($namaFile)
    {
        $path = $this->uploadPath . $namaFile;
        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $file = new \CodeIgniter\Files\File($path);
        // Kirim header agar browser tahu ini adalah file gambar/audio, bukan teks
        return $this->response->setHeader('Content-Type', $file->getMimeType())
            ->setBody(file_get_contents($path));
    }

    /**
     * HITUNG PESAN BELUM DIBACA
     * Menghitung angka notifikasi (badge) yang muncul di ikon chat navbar
     */
    public function countUnread()
    {
        $id_saya = session()->get('id_user');

        $query = $this->pesanModel->where('status_baca', '0');
        if (session()->get('role') == 'admin') {
            // Admin menghitung pesan yang ditujukan ke ID 0 (Admin umum) atau ke ID pribadinya
            $query->groupStart()
                ->where('id_penerima', 0)
                ->orWhere('id_penerima', $id_saya)
                ->groupEnd();
        } else {
            // User hanya menghitung pesan yang dikirim khusus ke ID mereka
            $query->where('id_penerima', $id_saya);
        }

        $count = $query->countAllResults();
        return $this->response->setJSON(['total' => $count]);
    }

    /**
     * CEK PESAN BARU (REAL-TIME)
     * Digunakan oleh JavaScript untuk tahu apakah ada pesan masuk saat chat sedang terbuka
     */
    public function cek_baru($id_lawan)
    {
        $id_saya = session()->get('id_user');
        $ada = $this->pesanModel->where('id_pengirim', $id_lawan)
            ->where('id_penerima', $id_saya)
            ->where('status_baca', '0')
            ->countAllResults();

        return $this->response->setJSON(['ada_baru' => $ada > 0]);
    }

    /**
     * FUNGSI KIRIM PESAN
     * Menangani pengiriman teks, gambar, dan pesan suara (VN)
     */
    public function kirim()
    {
        $id_pengirim = session()->get('id_user');
        $id_penerima = $this->request->getPost('id_penerima');
        $isi_pesan   = $this->request->getPost('pesan');

        // Mengambil file yang diunggah (bisa dari input 'gambar' atau 'audio')
        $file = $this->request->getFile('gambar') ?? $this->request->getFile('audio');

        // Validasi agar tidak mengirim pesan ke diri sendiri
        if ($id_pengirim == $id_penerima) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Tidak bisa mengirim ke diri sendiri']);
        }

        $namaFile = null;
        $tipe     = 'text'; // Default tipe pesan adalah teks

        // Logika jika user mengunggah file (gambar/suara)
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = $file->getMimeType();
            $ext  = $file->getExtension();

            // Deteksi tipe file: Jika mime-nya audio atau extensionnya webm/mp3, anggap 'audio'
            if (strpos($mime, 'audio') !== false || $ext == 'webm' || $ext == 'mp3') {
                $tipe = 'audio';
            } else {
                $tipe = 'image'; // Selain itu dianggap gambar
            }

            $namaFile = $file->getRandomName(); // Beri nama unik
            $file->move($this->uploadPath, $namaFile); // Pindahkan ke folder upload
        }

        // Menyiapkan data untuk disimpan ke tabel 'pesan'
        $data = [
            'id_pengirim'   => $id_pengirim,
            'id_penerima'   => $id_penerima,
            'isi_pesan'     => $isi_pesan,
            'file_lampiran' => $namaFile,
            'tipe_pesan'    => $tipe,
            'status_baca'   => '0'
        ];

        if ($this->pesanModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'token'  => csrf_hash(), // Kirim ulang CSRF token agar form tidak expired
                'msg'    => 'Pesan terkirim!'
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'msg' => 'Gagal kirim']);
    }

    /**
     * FUNGSI HAPUS PESAN
     * Menghapus pesan dengan aturan akses tertentu
     */
    public function hapus($id_pesan)
    {
        $id_saya = session()->get('id_user');
        $role_saya = session()->get('role');
        $pesan = $this->pesanModel->find($id_pesan);

        // SYARAT HAPUS: Pemilik pesan itu sendiri atau dia seorang Admin
        if ($pesan && ($pesan['id_pengirim'] == $id_saya || $role_saya == 'admin')) {
            if ($this->pesanModel->delete($id_pesan)) {
                return $this->response->setJSON(['status' => 'success']);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'msg'    => 'Gagal menghapus: Anda tidak memiliki akses atau pesan tidak ditemukan'
        ]);
    }
}
