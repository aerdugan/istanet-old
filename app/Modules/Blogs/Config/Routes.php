<?php
namespace Config;

$routes = Services::routes();
$routes->group('', ['namespace' => 'App\Modules\Blogs\Controllers'], static function($routes) {
    $routes->get('blogs', 'Blogs::index');
    $routes->get('blogs/newForm', 'Blogs::newForm');
    $routes->post('blogs/store', 'Blogs::store');
    $routes->post('blogs/getBlogs', 'Blogs::getBlogs');
    $routes->get('blogs/updateForm/(:num)','Blogs::updateForm/$1');
    $routes->post('blogs/update/(:num)','Blogs::update/$1');
    $routes->get('blogs/imageForm/(:num)','Blogs::imageForm/$1');
    $routes->post('blogs/image_upload/(:num)','Blogs::image_upload/$1');
    $routes->get('blogs/newForm', 'Blogs::newForm');
    $routes->get('blogs/refresh_image_list/(:num)','Blogs::refresh_image_list/$1');
    $routes->post('blogs/imageIsActiveSetter/(:num)','Blogs::imageIsActiveSetter/$1');
    $routes->post('blogs/imageDelete/(:num)/(:num)', 'Blogs::imageDelete/$1/$2');
    $routes->post('blogs/isCoverSetter/(:num)/(:num)', 'Blogs::isCoverSetter/$1/$2');
    $routes->post('blogs/deleteAllImages/(:num)','Blogs::deleteAllImages/$1');
    $routes->post('blogs/toggle', 'Blogs::toggle');
    $routes->post('blogs/categoryRankUpdate', 'Blogs::categoryRankUpdate');
    $routes->get('blogs/blogCategories', 'Blogs::blogCategories');
    $routes->post('blogs/categoryAdd', 'Blogs::categoryAdd');
    $routes->post('blogs/categoryEdit', 'Blogs::categoryEdit');
    $routes->post('blogs/categoryDelete', 'Blogs::categoryDelete');
    $routes->post('blogs/categoryIsActiveSetter', 'Blogs::categoryIsActiveSetter');
});


