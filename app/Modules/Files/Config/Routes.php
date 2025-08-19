<?php
namespace Config;

$routes = Services::routes();

$routes->group('admin/', ['namespace' => 'App\Modules\Files\Controllers'], static function($routes){
    $routes->get('files', 'Files::index');
    $routes->match(['get', 'post'], 'files/listFiles', 'Files::listFiles');
    $routes->get('files/listFolders', 'Files::listFolders');
    $routes->post('files/deleteFile', 'Files::deleteFile');
    $routes->post('files/moveFile', 'Files::moveFile');
    $routes->post('files/createFolder', 'Files::createFolder');
    $routes->post('files/fileUpload', 'Files::fileUpload');
    $routes->post('files/renameFile', 'Files::renameFile');
    $routes->get('files/getModels', 'Files::getModels');
    $routes->post('files/textToImage', 'Files::textToImage');
    $routes->post('files/upscaleImage', 'Files::upscaleImage');
    $routes->post('files/controlNet', 'Files::controlNet');
    $routes->post('files/saveText', 'Files::saveText');
    $routes->get('files/getModals', 'Files::getModals');
    $routes->match(['get', 'post'], 'files/fileManagerFiles', 'Files::fileManagerFiles');
});




