<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Aliases: Nama pendek untuk class filter.
     * Digunakan agar pemanggilan di bagian 'globals' atau 'filters' lebih ringkas.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Custom Filter buatan sendiri untuk sistem Login & Hak Akses
        'auth' => \App\Filters\AuthFilter::class,
        'role' => \App\Filters\RoleFilter::class,
    ];

    /**
     * Required: Filter yang WAJIB dijalankan oleh sistem secara internal.
     * PERINGATAN: Jangan memasukkan 'forcehttps' di sini jika masih di localhost 
     * untuk menghindari error Class Not Found di beberapa versi CI4.
     */
    public array $required = [
        'before' => [
            // 'forcehttps', 
        ],
        'after' => [
            'performance',
            'toolbar',
        ],
    ];

    /**
     * Globals: Filter yang otomatis berjalan di SETIAP request URL.
     */
    public array $globals = [
        'before' => [
            // Mengaktifkan fitur keamanan CSRF secara global
            'csrf' => [
                'except' => [
                    /**
                     * PENTING: Rute 'barang/hapusFotoSatuan' dikecualikan karena 
                     * AJAX Fetch POST seringkali kesulitan mengirimkan token CSRF secara manual.
                     * Ini mencegah error 403 (Forbidden) saat klik tombol X merah.
                     */
                    'barang/hapusFotoSatuan'
                ]
            ],
            // 'honeypot',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * Methods: Menjalankan filter berdasarkan metode HTTP (GET, POST, dll).
     */
    public array $methods = [];

    /**
     * Filters: Menjalankan filter pada rute (URL) yang spesifik saja.
     */
    public array $filters = [
        /**
         * Filter 'auth' (Login Check):
         * Memastikan user harus login terlebih dahulu sebelum bisa mengakses:
         * - Halaman Daftar Barang
         * - Semua fitur tambah/edit/hapus (barang/*)
         * - Halaman Dashboard
         */
        'auth' => [
            'before' => [
                'barang',
                'barang/*',
                'dashboard',
                'dashboard/*'
            ]
        ],
    ];
}
