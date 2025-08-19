<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/', ['namespace' => 'App\Modules\Popup\Controllers'], static function($routes){
    $routes->get('popup', 'Popup::index');
    $routes->post('popup/popupStore', 'Popup::popupStore');
    $routes->post('popup/popupUpdate/(:any)', 'Popup::popupUpdate/$1');
    $routes->get('popup/delete/(:segment)', 'Popup::delete/$1');
    $routes->get('popup/popupEdit/(:segment)', 'Popup::popupEdit/$1');
    $routes->post('popup/popupRename/(:segment)', 'Popup::popupRename/$1');
    $routes->get('popup/popupView/(:segment)', 'Popup::popupView/$1');
});