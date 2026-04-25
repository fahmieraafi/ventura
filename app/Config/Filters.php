<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Filters\PerformanceMetrics;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'role'          => \App\Filters\RoleFilter::class,
    ];

    // INI DIA: Biarkan required kosong agar globals yang pegang kendali
    public array $required = [
        'before' => [],
        'after'  => [
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            // CSRF dimatikan total dengan memberikan komentar (//) di bawah ini
            // 'csrf', 
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => [
                'barang',
                'barang/*',
                'dashboard',
                'dashboard/*',
                'users',
                'users/index',
                'users/edit/*',
                'users/update/*',
                'users/delete/*',
                'gunung',
                'gunung/*',
            ]
        ],
    ];
}
