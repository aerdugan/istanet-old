<?php

namespace Modules\ThemeSettings\Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('backend/theme-settings', ['filter' => 'session'], static function ($routes) {
    $routes->get('/',  '\App\Modules\ThemeSettings\Controllers\ThemeSettings::index', ['as' => 'theme.settings']);
    $routes->post('/', '\App\Modules\ThemeSettings\Controllers\ThemeSettings::save',  ['as' => 'theme.settings.save']);
});
