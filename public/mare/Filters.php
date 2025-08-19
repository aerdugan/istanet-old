<?php

namespace mare;

use App\Filters\JWTAuthFilter;
use App\Filters\LoginAuthFilter;
use App\Filters\ThrottlerFilter;
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
        'auth'          => JWTAuthFilter::class,
        'throttler'     => ThrottlerFilter::class,
        'login'         => LoginAuthFilter::class,
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            'csrf' => [
                'except' => [
                    'pages',
                    'pages/*',
                    'files',
                    'files/*',
                    'api',
                    'api/*',
                    'ajax',
                    'ajax/*',
                ]
            ],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => [
                'api/user/*',
                'api/user/',
            ],
        ],
        'login' => [
            'before' => [],
        ],
        'throttler' => [
            'before' => [
                'api/*',
                'api/',
            ],
        ],
    ];

    public function __construct()
    {
        $this->filters['login']['before'] = array_merge([
            'oauth',
            'oauth/*',
            'api',
            'api/*',
            'cron',
            'dashboard',
            'cron/*',
            'language',
            'language/*',
            'integration',
            'integration/*',
            'pages',
            'pages/*',
            'settings',
            'settings/*',
            'files',
            'files/*',
            'tools',
            'tools/*',
            'ajax',
            'ajax/*',
            'user',
            'user/*',
            'group',
            'group/*',
            'activity',
            'activity/*',
        ], AutoFilters::getLoginModules());
    }
}
