<?php
namespace Config;

$routes = Services::routes();
$routes->group('admin/page', ['namespace' => 'App\Modules\Page\Controllers'], static function($routes) {
    $routes->get('/', 'Page::index');
    $routes->post('edit', 'Page::edit');
    $routes->post('store', 'Page::store');
    $routes->get('updateForm/(:num)', 'Page::updateForm/$1');
    $routes->post('pageUpdate/(:num)', 'Page::currentPageUpdate/$1');


    $routes->get('addMissingLanguagePages/(:num)', 'Page::addMissingLanguagePages/$1');
    $routes->get('translateFields/(:num)', 'Page::translatePageFields/$1');
    $routes->get('generateSeoFields/(:num)', 'Page::generateSeoFields/$1');
    $routes->get('generateSeoData/(:num)', 'Page::generateSeoData/$1');


    $routes->get('translateAndCreateLangVersion/(:num)/(:segment)', 'Page::translateAndCreateLangVersion/$1/$2');


    $routes->get('copyCurrentPage/(:num)', 'Page::copyCurrentPage/$1');
    $routes->get('copyAndTranslateCurrentPage/(:num)/(:segment)', 'Page::copyAndTranslateCurrentPage/$1/$2');

    $routes->post('clonePage', 'Page::clonePage');
    $routes->post('pageDelete', 'Page::pageDelete');
    $routes->post('isActiveSetter', 'Page::isActiveSetter');
    $routes->post('ordering', 'Page::ordering');
    $routes->get('content-box-preview/(:num)', 'Page::contentBoxPreview/$1');
    $routes->get('contentBoxEdit/(:num)','Page::contentBoxEdit/$1');//
    $routes->get('contentBoxMobileEdit/(:num)','Page::contentBoxMobileEdit/$1');//
    $routes->get('contentBuilderEdit/(:num)','Page::contentBuilderEdit/$1');//
    $routes->post('contentBuilderSave', 'Page::contentBuilderSave');
    $routes->get('contentBuilderMobileEdit/(:num)','Page::contentBuilderMobileEdit/$1');//
    $routes->post('contentBuilderMobileSave', 'Page::contentBuilderMobileSave');

    $routes->get('copyPageToNewLang/(:num)/(:segment)', 'Page::copyPageToNewLang/$1/$2');

    $routes->get('content-box-page-blank', 'Page::contentBoxPageBlank');
    $routes->post('saveContent', 'Page::saveContent');

    $routes->post('save-mobile-content', 'Page::saveMobileContent');
    $routes->post('upload-file', 'Page::uploadFile');
    $routes->post('upload-base64', 'Page::uploadBase64');
    $routes->get('file-manager-files', 'Page::fileManagerFiles');

});

//
//$routes->post('page/send-command', 'Page::sendCommand');


