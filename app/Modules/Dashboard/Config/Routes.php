<?php
namespace App\Modules\Dashboard\Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('admin', [
    'namespace' => 'App\Modules\Dashboard\Controllers',
    'filter'    => 'session', // Shield oturumu zorunlu
], static function ($routes) {

    // Admin için dil filtresi (istiyorsan kalsın)
    $routes->group('', ['filter' => 'languageInit'], static function ($routes) {

        // ✅ /admin tam eşleşmesi
        $routes->get('/', 'Dashboard::index', ['as' => 'backend.dashboard.index']);

        // opsiyonel: /admin/dashboard da aynı sayfaya götürsün
        $routes->get('dashboard', 'Dashboard::index', ['as' => 'dashboard']);

        $routes->post('lang/switch', 'Language::switch', ['as' => 'backend.lang.switch']);
    });
});