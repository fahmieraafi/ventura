<?php

namespace Config;

// Mengimpor konfigurasi dasar Filter dari sistem CodeIgniter
use CodeIgniter\Config\Filters as BaseFilters;
// Mengimpor berbagai filter bawaan CI4 untuk keamanan (CSRF, Toolbar, dll)
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Filters\PerformanceMetrics;

class Filters extends BaseFilters
{
    /**
     * ALIASES (Daftar Nama Alias)
     * Di sini kita memberikan "nama panggilan" untuk class filter agar lebih mudah dipanggil.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,           // Alias untuk keamanan form (Cross-Site Request Forgery)
        'toolbar'       => DebugToolbar::class,    // Alias untuk menampilkan baris debugging di bawah web
        'honeypot'      => Honeypot::class,       // Alias untuk menjebak bot/spam
        'invalidchars'  => InvalidChars::class,   // Alias untuk memfilter karakter terlarang
        'secureheaders' => SecureHeaders::class,  // Alias untuk keamanan header HTTP
        'performance'   => PerformanceMetrics::class, // Alias untuk memantau performa aplikasi

        // --- CUSTOM FILTERS (Buatan Sendiri) ---
        'auth'          => \App\Filters\AuthFilter::class, // Menghubungkan alias 'auth' ke file AuthFilter kamu
        'role'          => \App\Filters\RoleFilter::class, // Menghubungkan alias 'role' ke file RoleFilter kamu
    ];

    /**
     * REQUIRED (Wajib)
     * Filter yang akan selalu dijalankan oleh sistem secara otomatis.
     */
    public array $required = [
        'before' => [], // Kosong, karena keamanan utama diatur di bagian 'globals'
        'after'  => [
            'performance', // Selalu jalankan pemantau performa setelah halaman dimuat
            'toolbar',     // Selalu jalankan toolbar debug setelah halaman dimuat
        ],
    ];

    /**
     * GLOBALS (Global/Menyeluruh)
     * Filter yang akan dijalankan di SETIAP request/halaman tanpa terkecuali.
     */
    public array $globals = [
        'before' => [
            // 'csrf', // CSRF dimatikan total agar tidak perlu repot dengan token saat testing form
        ],
        'after' => [
            'toolbar', // Memastikan toolbar debug muncul di semua halaman
        ],
    ];

    /**
     * METHODS
     * Digunakan jika kamu ingin filter tertentu hanya jalan di method HTTP tertentu (misal: hanya POST)
     */
    public array $methods = [];

    /**
     * FILTERS (Penjagaan Spesifik)
     * Di sinilah kamu menentukan "halaman mana dijaga oleh siapa".
     */
    public array $filters = [
        'auth' => [
            // Filter 'auth' akan mengecek login SEBELUM user masuk ke daftar URL di bawah ini
            'before' => [
                'barang',           // Halaman utama barang
                'barang/*',         // Semua sub-halaman barang (tambah, edit, dll)
                'dashboard',        // Halaman dashboard
                'dashboard/*',      // Semua sub-halaman dashboard
                'users',            // Halaman daftar user
                'users/index',      // Method index di controller user
                'users/edit/*',     // Form edit user berdasarkan ID
                'users/update/*',   // Proses update data user
                'users/delete/*',   // Proses hapus user
                'gunung',           // Halaman data gunung
                'gunung/*',         // Semua sub-halaman gunung
            ]
        ],
    ];
}
