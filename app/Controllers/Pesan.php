<?php

namespace App\Controllers;

use App\Models\PesanModel;
use CodeIgniter\Controller;

class Pesan extends BaseController
{
    protected $pesanModel;
    protected $uploadPath = WRITEPATH . 'uploads/chat/';

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    public function index($id_lawan = null)
    {
        $id_saya = session()->get('id_user');
        $role    = session()->get('role');

        if ($role == 'admin') {
            if ($id_lawan === null) {
                $data['daftar_chat'] = $this->pesanModel->getDaftarChatAdmin();
                return view('pesanadmin/index', $data);
            }
            $id_untuk_history = $id_lawan;
        } else {
            if ($id_lawan === null) {
                $id_lawan = 0;
            }
            $id_untuk_history = $id_saya;
        }

        // Jalankan fungsi update baca saat index dibuka
        $this->updateStatusBaca($id_lawan);

        $data['semua_pesan'] = $this->pesanModel->getChatHistory($id_untuk_history);
        $data['id_lawan'] = $id_lawan;
        $data['detail_lawan'] = $this->db->table('users')->where('id_user', $id_lawan)->get()->getRowArray();

        return view('pesan/index', $data);
    }

    /**
     * PERBAIKAN: Fungsi ini yang dipanggil oleh AJAX di View
     */
    public function baca($id_lawan)
    {
        $this->updateStatusBaca($id_lawan);
        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * Helper untuk update status baca agar tidak duplikasi code
     */
    private function updateStatusBaca($id_lawan)
    {
        $id_saya = session()->get('id_user');
        if (!$id_lawan) return;

        $this->pesanModel->where('id_pengirim', $id_lawan)
            ->where('id_penerima', $id_saya)
            ->where('status_baca', '0')
            ->set(['status_baca' => '1'])
            ->update();
    }

    public function gambar($namaFile)
    {
        $path = $this->uploadPath . $namaFile;
        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $file = new \CodeIgniter\Files\File($path);
        return $this->response->setHeader('Content-Type', $file->getMimeType())
            ->setBody(file_get_contents($path));
    }

    public function countUnread()
    {
        $id_saya = session()->get('id_user');

        // Logika notifikasi: Jika admin, cek pesan yang ke ID 0 atau ID dia sendiri
        $query = $this->pesanModel->where('status_baca', '0');
        if (session()->get('role') == 'admin') {
            $query->groupStart()
                ->where('id_penerima', 0)
                ->orWhere('id_penerima', $id_saya)
                ->groupEnd();
        } else {
            $query->where('id_penerima', $id_saya);
        }

        $count = $query->countAllResults();

        return $this->response->setJSON(['total' => $count]);
    }

    public function cek_baru($id_lawan)
    {
        $id_saya = session()->get('id_user');
        $ada = $this->pesanModel->where('id_pengirim', $id_lawan)
            ->where('id_penerima', $id_saya)
            ->where('status_baca', '0')
            ->countAllResults();

        return $this->response->setJSON(['ada_baru' => $ada > 0]);
    }




    public function kirim()
    {
        $id_pengirim = session()->get('id_user');
        $id_penerima = $this->request->getPost('id_penerima');
        $isi_pesan   = $this->request->getPost('pesan');

        // MODIFIKASI DISINI: Bisa menerima input 'gambar' atau 'audio'
        $file = $this->request->getFile('gambar') ?? $this->request->getFile('audio');

        // Mencegah error jika kirim ke diri sendiri secara tidak sengaja
        if ($id_pengirim == $id_penerima) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Tidak bisa mengirim ke diri sendiri']);
        }

        $namaFile = null;
        $tipe     = 'text';

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mime = $file->getMimeType();
            $ext  = $file->getExtension();

            // LOGIKA AUDIO DARI ATAS: Deteksi apakah file ini audio
            if (strpos($mime, 'audio') !== false || $ext == 'webm' || $ext == 'mp3') {
                $tipe = 'audio';
            } else {
                $tipe = 'image';
            }

            $namaFile = $file->getRandomName();
            $file->move($this->uploadPath, $namaFile);
        }

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
                'token'  => csrf_hash(),
                'msg'    => 'Pesan terkirim!'
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'msg' => 'Gagal kirim']);
    }

    public function hapus($id_pesan)
    {
        $id_saya = session()->get('id_user');
        $role_saya = session()->get('role'); // Ambil role dari session
        $pesan = $this->pesanModel->find($id_pesan);

        // IZINKAN HAPUS JIKA: 
        // 1. Pesan itu milik saya sendiri OR 
        // 2. Saya adalah Admin
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
