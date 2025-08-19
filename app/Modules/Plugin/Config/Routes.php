<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/', ['namespace' => 'App\Modules\Plugin\Controllers'], static function($routes){
    $routes->get('plugin', 'Plugin::index');
    $routes->post('plugin/pluginStore', 'Plugin::pluginStore');
    $routes->post('plugin/pluginUpdate/(:any)', 'Plugin::pluginUpdate/$1');
    $routes->get('plugin/pluginDelete/(:segment)', 'Plugin::pluginDelete/$1');
    $routes->get('plugin/pluginEdit/(:segment)', 'Plugin::pluginEdit/$1');
    $routes->post('plugin/pluginRename/(:segment)', 'Plugin::pluginRename/$1');
    $routes->get('plugin/pluginView/(:any)', 'Plugin::pluginView/$1');
});