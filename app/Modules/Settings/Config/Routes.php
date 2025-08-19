<?php

namespace App\Modules\Settings\Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin', ['filter' => 'session'], static function ($routes) {
    $routes->get('settings/email', '\App\Modules\Settings\Controllers\Settings::emailSettings', ['as' => 'admin.settings.email']);
    $routes->post('settings/email/save', '\App\Modules\Settings\Controllers\Settings::emailSettingsSave');
    $routes->post('settings/email/test', '\App\Modules\Settings\Controllers\Settings::emailSendTest', ['as' => 'admin.settings.email.test']);
    $routes->get('settings/shield', '\App\Modules\Settings\Controllers\Settings::shieldSettings', ['as' => 'admin.settings.shield']);
    $routes->post('settings/shield/save', '\App\Modules\Settings\Controllers\Settings::shieldSettingsSave', ['as' => 'admin.settings.shield.save']);
    $routes->get('settings/theme', '\App\Modules\Settings\Controllers\Settings::themeSettings', ['as' => 'admin.settings.theme']);
    $routes->post('settings/theme/save', '\App\Modules\Settings\Controllers\Settings::themeSettingsSave', ['as' => 'admin.settings.theme.save']);
});