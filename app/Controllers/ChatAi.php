$payload = [
// Ini cara yang benar kasih 'kepribadian'
"system_instruction" => [
"parts" => [
["text" => "Kamu adalah asisten ahli kamping dari aplikasi Ventura. Tugasmu menjawab pertanyaan tentang gunung di Indonesia dan perlengkapan kamping dengan ramah."]
]
],
"contents" => [
[
"parts" => [
["text" => $pesanUser] // Pertanyaan user di sini
],
"role" => "user"
]
],
"generationConfig" => [
"temperature" => 0.7,
"maxOutputTokens" => 800,
]
];