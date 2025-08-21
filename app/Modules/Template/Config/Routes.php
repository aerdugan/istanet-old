<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/template', ['namespace' => 'App\Modules\Template\Controllers'], static function($routes){

    $routes->get('/', 'Template::index');
    $routes->post('templateSettingsUpdate/', 'Template::templateUpdate');
    $routes->post('updateSiteStatus', 'Template::updateSiteStatus');
    $routes->get ('header',                    'Template::header');
    $routes->post('headerStore',               'Template::headerStore');
    $routes->get ('headerEdit/(:segment)',     'Template::headerEdit/$1');
    $routes->post('headerUpdate/(:segment)',   'Template::headerUpdate/$1');
    $routes->get ('headerDelete/(:segment)',   'Template::headerDelete/$1');
    $routes->post('headerRename/(:segment)',   'Template::headerRename/$1');
    $routes->get ('footer',                    'Template::footer');
    $routes->post('footerStore',               'Template::footerStore');
    $routes->get ('footerEdit/(:segment)',     'Template::footerEdit/$1');
    $routes->post('footerUpdate/(:segment)',   'Template::footerUpdate/$1');
    $routes->get ('footerDelete/(:segment)',   'Template::footerDelete/$1');
    $routes->post('footerRename/(:segment)',   'Template::footerRename/$1');
    $routes->get ('breadCrumb',                'Template::breadCrumb');
    $routes->post('breadCrumbStore',           'Template::breadCrumbStore');
    $routes->get ('breadCrumbEdit/(:segment)', 'Template::breadCrumbEdit/$1');
    $routes->post('breadCrumbUpdate/(:segment)','Template::breadCrumbUpdate/$1');
    $routes->get ('breadCrumbDelete/(:segment)','Template::breadCrumbDelete/$1');
    $routes->post('breadCrumbRename/(:segment)','Template::breadCrumbRename/$1');
    $routes->get ('slider',                    'Template::slider');
    $routes->post('sliderStore',               'Template::sliderStore');
    $routes->get ('sliderEdit/(:segment)',     'Template::sliderEdit/$1');
    $routes->post('sliderUpdate/(:segment)',   'Template::sliderUpdate/$1');
    $routes->get ('sliderDelete/(:segment)',   'Template::sliderDelete/$1');
    $routes->post('sliderRename/(:segment)',   'Template::sliderRename/$1');
});