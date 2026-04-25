$payload = [
// 1. SYSTEM INSTRUCTION: Ini adalah 'perintah dasar' atau identitas AI.
// Bagian ini memberi tahu AI siapa dirinya sebelum dia membaca pertanyaan user.
"system_instruction" => [
"parts" => [
[
"text" => "Kamu adalah asisten ahli kamping dari aplikasi Ventura. Tugasmu menjawab pertanyaan tentang gunung di Indonesia dan perlengkapan kamping dengan ramah."
]
]
],

// 2. CONTENTS: Ini adalah bagian percakapan atau interaksi utama.
"contents" => [
[
"parts" => [
// Mengambil isi pesan asli yang diketik oleh user di aplikasi
["text" => $pesanUser]
],
// Memberi tahu AI bahwa teks di atas berasal dari 'user' (pengguna)
"role" => "user"
]
],

// 3. GENERATION CONFIG: Pengaturan cara AI menyusun kata-kata (gaya bicara).
"generationConfig" => [
// Temperature: Mengatur kreativitas (0.1 sangat kaku/serius, 1.0 sangat kreatif/ngaco).
// 0.7 adalah angka yang pas: kreatif tapi tetap terkontrol.
"temperature" => 0.7,

// Max Output Tokens: Membatasi panjang jawaban AI (sekitar 800 kata).
// Agar AI tidak menjawab terlalu panjang yang bisa menghabiskan kuota/kredit API.
"maxOutputTokens" => 800,
]
];