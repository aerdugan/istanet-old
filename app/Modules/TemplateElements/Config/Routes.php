<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/', ['namespace' => 'App\Modules\TemplateElements\Controllers'], static function($routes){
    $routes->get('themeElements', 'ThemeElements::index');
    $routes->post('themeElements/themeElementsStore', 'ThemeElements::themeElementsStore');
    $routes->post('themeElements/themeElementsUpdate/(:any)', 'ThemeElements::themeElementsUpdate/$1');
    $routes->get('themeElements/themeElementsDelete/(:segment)', 'ThemeElements::themeElementsDelete/$1');
    $routes->get('themeElements/themeElementsEdit/(:segment)', 'ThemeElements::themeElementsEdit/$1');
    $routes->post('themeElements/themeElementsRename/(:segment)', 'ThemeElements::themeElementsRename/$1');
    $routes->get('themeElements/themeElementsView/(:any)', 'ThemeElements::themeElementsView/$1');
});