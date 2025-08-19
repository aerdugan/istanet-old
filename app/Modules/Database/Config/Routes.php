<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin', ['namespace' => 'App\Modules\Database\Controllers'], static function($routes){
    $routes->get('database', 'Database::index');
    $routes->get('database/downloadSQL/(:segment)', 'Database::downloadSQL/$1');
    $routes->get('database/truncate/(:segment)', 'Database::truncate/$1');
    $routes->post('database/uploadSQL', 'Database::uploadSQL');
});


