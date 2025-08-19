<?php

namespace App\Modules\Users\Config;

use CodeIgniter\Router\RouteCollection;

$routes->group('admin', ['filter' => 'session'], static function ($routes) {
    $routes->get('users', '\App\Modules\Users\Controllers\Users::index', ['as' => 'admin.users']);
    $routes->get('users/new', '\App\Modules\Users\Controllers\Users::new');
    $routes->post('users/create', '\App\Modules\Users\Controllers\Users::create');
    $routes->get('users/(:num)/edit', '\App\Modules\Users\Controllers\Users::edit/$1');
    $routes->post('users/(:num)/update', '\App\Modules\Users\Controllers\Users::update/$1');
    $routes->post('users/(:num)/delete', '\App\Modules\Users\Controllers\Users::delete/$1');

    $routes->get('roles/', '\App\Modules\Users\Controllers\Roles::index');
    $routes->get('roles/create', '\App\Modules\Users\Controllers\Roles::create');
    $routes->post('roles/store', '\App\Modules\Users\Controllers\Roles::store');
    $routes->get('roles/edit/(:num)', '\App\Modules\Users\Controllers\Roles::edit/$1'); // 👈 bu satır
    $routes->post('roles/update/(:num)', '\App\Modules\Users\Controllers\Roles::update/$1'); // 👈 bu satır
    $routes->get('roles/delete/(:num)', '\App\Modules\Users\Controllers\Roles::delete/$1');
    $routes->get('roles/permissions/(:num)', '\App\Modules\Users\Controllers\Roles::editPermissions/$1');
    $routes->post('roles/permissions/(:num)', '\App\Modules\Users\Controllers\Roles::updatePermissions/$1');
    $routes->get('roles/syncPermissions', '\App\Modules\Users\Controllers\Roles::syncPermissions');
    $routes->post('roles/scan-permissions', '\App\Modules\Users\Controllers\Permissions::scan', ['as' => 'admin.roles.scanperms']);


});