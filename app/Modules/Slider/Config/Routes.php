<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/slider', ['namespace' => 'App\Modules\Slider\Controllers'], static function($routes) {
    $routes->get('', 'Slider::index');
    $routes->post('edit', 'Slider::edit');
    $routes->post('store', 'Slider::store');
    $routes->post('delete', 'Slider::delete');
    $routes->post('updateRank', 'Slider::updateRank');
    $routes->post('isActiveSetter', 'Slider::isActiveSetter');
    $routes->get('generateLangVersion/(:num)/(:alpha)', 'Slider::generateLangVersion/$1/$2');

});


