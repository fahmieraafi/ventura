<?php

namespace App\Controllers;

// Mengimpor BaseController agar bisa menggunakan fitur session dan database yang sudah disiapkan di sana
use App\Controllers\BaseController;

class Chat extends BaseController
{
    /**
     * FUNGSI TANYA AI
     * Fungsi ini menerima pesan dari user, mengirimkannya ke Google Gemini AI, 
     * dan memberikan jawaban kembali ke aplikasi.
     */
    public function tanyaAi()
    {
        // Mengambil pesan yang diketik user dari form input (metode POST)
        $pesanUser = $this->request->getPost('pesan');

        // Mengambil API KEY Google Gemini dari file .env agar aman
        $apiKey = getenv('GEMINI_API_KEY') ?: env('GEMINI_API_KEY');

        // 1. Ambil Session User: Mengetahui siapa yang sedang bertanya
        $session = session();
        $userRole = $session->get('role'); // Mengetahui apakah dia Admin atau User biasa
        $namaLogin = $session->get('nama') ?? 'Tamu'; // Jika tidak login, panggil 'Tamu'

        // Menyiapkan koneksi database
        $db = \Config\Database::connect();

        // 2. Mengambil Data Umum: Mengambil daftar barang dan gunung agar AI "pintar" tentang isi aplikasi
        $dataBarang = $db->table('barang')->select('nama_barang, kategori, stok, harga_sewa, kondisi')->get()->getResultArray();
        $dataGunung = $db->table('gunung')->select('nama_gunung, lokasi, ketinggian, status, foto, deskripsi')->get()->getResultArray();

        // 3. Filter Ketat (Keamanan Data)
        $konteksRahasia = "";
        if ($userRole === 'admin') {
            // Jika yang tanya ADMIN: Berikan AI data transaksi dan data user untuk membantu tugas admin
            $dataTransaksi = $db->table('transaksi')->select('id_user, id_barang, tgl_pinjam, tgl_kembali, total_harga, denda, status_denda, status_transaksi, is_read, bukti_bayar')->get()->getResultArray();
            $dataUser = $db->table('users')->select('nama, role, username')->get()->getResultArray();

            $konteksRahasia = "\n=== DATA INTERNAL ADMIN (CONFIDENTIAL) ===\n";
            $konteksRahasia .= "Data Transaksi: " . json_encode($dataTransaksi) . "\n";
            $konteksRahasia .= "Data User/Staff: " . json_encode($dataUser) . "\n";
        } else {
            // Jika yang tanya BUKAN Admin: Berikan instruksi ke AI agar tidak membocorkan data rahasia
            $konteksRahasia = "\n(Sistem Note: User ini bukan Admin. Jangan berikan informasi transaksi atau user lain.)\n";
        }

        // 4. Susun Prompt Final (Instruksi Lengkap untuk AI)
        $promptFinal = "Kamu adalah Ventura Assistant. Kamu sedang melayani $namaLogin (Role: $userRole).\n";
        $promptFinal .= "Gunakan data Ventura_v2 berikut:\n";
        $promptFinal .= "Data Barang: " . json_encode($dataBarang) . "\n"; // AI jadi tahu stok barang
        $promptFinal .= "Data Gunung: " . json_encode($dataGunung) . "\n"; // AI jadi tahu info gunung
        $promptFinal .= $konteksRahasia;

        $promptFinal .= "\nInstruksi Keamanan & Tugas:\n";
        $promptFinal .= "- Jika user bertanya tentang data transaksi atau user lain padahal role-nya bukan admin, jawab bahwa itu rahasia.\n";
        $promptFinal .= "- Kamu diperbolehkan menjawab pertanyaan umum di luar aplikasi (seperti tips teknologi, laptop, hobi, dll) namun tetap dengan gaya bahasa asisten Ventura yang ramah.\n";
        $promptFinal .= "- Jawablah dengan ramah dan informatif.\n\n";
        $promptFinal .= "Pertanyaan: " . $pesanUser; // Pesan asli dari user ditempel di akhir

        // Menentukan URL API Google Gemini (Model: gemini-flash-latest)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;

        // Menyusun paket data (Payload) dalam format JSON untuk dikirim ke Google
        $payload = [
            "contents" => [["parts" => [["text" => $promptFinal]]]]
        ];

        // Inisialisasi CURL untuk melakukan pengiriman data antar server (App ke Google)
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Mengatur header ke JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));              // Memasukkan data prompt
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                           // Agar respon bisa disimpan di variabel
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                          // Mengabaikan verifikasi SSL (untuk lokal/XAMPP)

        // Eksekusi pengiriman dan ambil hasilnya
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Cek kode status (200=OK, 404=Gagal, dll)
        $curlError = curl_error($ch);                      // Cek jika ada error koneksi
        curl_close($ch); // Tutup koneksi CURL

        // Mengubah respon JSON dari Google menjadi Array PHP
        $result = json_decode($response, true);

        // CEK APAKAH ADA ERROR DARI CURL (Masalah Koneksi Internet/Server)
        if ($response === false) {
            return $this->response->setJSON(['jawaban' => "CURL Error: " . $curlError]);
        }

        // CEK APAKAH ADA ERROR DARI GOOGLE (API Key salah, Quota habis, atau Model salah)
        if (isset($result['error'])) {
            return $this->response->setJSON(['jawaban' => "Google Error (" . $httpCode . "): " . $result['error']['message']]);
        }

        // AMBIL JAWABAN JIKA BERHASIL (Mengambil teks saja dari struktur JSON Google yang rumit)
        $jawabanAi = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Gagal: Format respon tidak sesuai.";

        // Kirim jawaban AI kembali ke tampilan Chat (Frontend) dalam format JSON
        return $this->response->setJSON(['jawaban' => $jawabanAi]);
    }
}
