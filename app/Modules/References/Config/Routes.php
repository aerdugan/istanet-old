<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/references', ['namespace' => 'App\Modules\References\Controllers'], static function($routes) {
    $routes->get('', 'References::index');
    $routes->get('newForm', 'References::newForm');
    $routes->post('store', 'References::store');
    $routes->post('getReferences', 'References::getReferences');
    $routes->get('updateForm/(:num)','References::updateForm/$1');
    $routes->post('update/(:num)','References::update/$1');
    $routes->get('imageForm/(:num)','References::imageForm/$1');
    $routes->match(['post', 'get'], 'image_upload/(:num)', 'References::image_upload/$1');
    $routes->post('delete', 'References::delete');
    $routes->get('generateLangVersion/(:num)/(:alpha)', 'References::generateLangVersion/$1/$2');
    $routes->post('imageRankSetter', 'References::imageRankSetter');
    $routes->post('isCoverSetter/(:num)/(:num)', 'References::isCoverSetter/$1/$2');

    $routes->get('newForm', 'References::newForm');
    $routes->get('refresh_image_list/(:num)','References::refresh_image_list/$1');
    $routes->post('imageIsActiveSetter/(:num)','References::imageIsActiveSetter/$1');
    $routes->post('imageDelete/(:num)/(:num)', 'References::imageDelete/$1/$2');
    $routes->post('deleteAllImages/(:num)','References::deleteAllImages/$1');
    $routes->post('toggle', 'References::toggle');
    $routes->post('categoryRankUpdate', 'References::categoryRankUpdate');
    $routes->get('referenceCategories', 'References::referenceCategories');
    $routes->post('categoryAdd', 'References::categoryAdd');
    $routes->post('categoryEdit', 'References::categoryEdit');
    $routes->post('categoryDelete', 'References::categoryDelete');
    $routes->post('categoryIsActiveSetter', 'References::categoryIsActiveSetter');
});


