<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Chat extends BaseController
{
    public function tanyaAi()
    {
        $pesanUser = $this->request->getPost('pesan');
        $apiKey = getenv('GEMINI_API_KEY') ?: env('GEMINI_API_KEY');

        // 1. Ambil Session User
        $session = session();
        $userRole = $session->get('role');
        $namaLogin = $session->get('nama') ?? 'Tamu';

        $db = \Config\Database::connect();

        // 2. Data Umum
        $dataBarang = $db->table('barang')->select('nama_barang, kategori, stok, harga_sewa, kondisi')->get()->getResultArray();
        $dataGunung = $db->table('gunung')->select('nama_gunung, lokasi, ketinggian, status, foto, deskripsi')->get()->getResultArray();

        // 3. Filter Ketat
        $konteksRahasia = "";
        if ($userRole === 'admin') {
            $dataTransaksi = $db->table('transaksi')->select('id_user, id_barang, tgl_pinjam, tgl_kembali, total_harga, denda, status_denda, status_transaksi, is_read, bukti_bayar')->get()->getResultArray();

            $dataUser = $db->table('users')->select('nama, role, username')->get()->getResultArray();

            $konteksRahasia = "\n=== DATA INTERNAL ADMIN (CONFIDENTIAL) ===\n";
            $konteksRahasia .= "Data Transaksi: " . json_encode($dataTransaksi) . "\n";
            $konteksRahasia .= "Data User/Staff: " . json_encode($dataUser) . "\n";
        } else {
            $konteksRahasia = "\n(Sistem Note: User ini bukan Admin. Jangan berikan informasi transaksi atau user lain.)\n";
        }

        // 4. Susun Prompt Final
        $promptFinal = "Kamu adalah Ventura Assistant. Kamu sedang melayani $namaLogin (Role: $userRole).\n";
        $promptFinal .= "Gunakan data Ventura_v2 berikut:\n";
        $promptFinal .= "Data Barang: " . json_encode($dataBarang) . "\n";
        $promptFinal .= "Data Gunung: " . json_encode($dataGunung) . "\n";
        $promptFinal .= $konteksRahasia;

        $promptFinal .= "\nInstruksi Keamanan & Tugas:\n";
        $promptFinal .= "- Jika user bertanya tentang data transaksi atau user lain padahal role-nya bukan admin, jawab bahwa itu rahasia.\n";
        // BARIS BARU DI BAWAH INI ADALAH KUNCINYA
        $promptFinal .= "- Kamu diperbolehkan menjawab pertanyaan umum di luar aplikasi (seperti tips teknologi, laptop, hobi, dll) namun tetap dengan gaya bahasa asisten Ventura yang ramah.\n";
        $promptFinal .= "- Jawablah dengan ramah dan informatif.\n\n";
        $promptFinal .= "Pertanyaan: " . $pesanUser;

        //gemini-2.0-flash-lite:generateContent?key ;UNTUK JAGAJAGA
        // Ganti gemini-2.0-flash-lite menjadi gemini-flash-latest
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;
        $payload = [
            "contents" => [["parts" => [["text" => $promptFinal]]]]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        // CEK APAKAH ADA ERROR DARI CURL (Koneksi Server)
        if ($response === false) {
            return $this->response->setJSON(['jawaban' => "CURL Error: " . $curlError]);
        }

        // CEK APAKAH ADA ERROR DARI GOOGLE (API Key/Model)
        if (isset($result['error'])) {
            return $this->response->setJSON(['jawaban' => "Google Error (" . $httpCode . "): " . $result['error']['message']]);
        }

        // AMBIL JAWABAN JIKA BERHASIL
        $jawabanAi = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Gagal: Format respon tidak sesuai.";

        return $this->response->setJSON(['jawaban' => $jawabanAi]);
    }
}
