<?php
namespace Config;
$routes = Services::routes();
$routes->group('admin', ['namespace' => 'App\Modules\QualityCertificates\Controllers'], static function($routes) {
    $routes->get('qualityCertificates', 'QualityCertificates::index');
    $routes->post('qualityCertificates/edit', 'QualityCertificates::edit');
    $routes->post('qualityCertificates/store', 'QualityCertificates::store');
    $routes->post('qualityCertificates/delete', 'QualityCertificates::delete');
    $routes->post('qualityCertificates/updateRank', 'QualityCertificates::updateRank');
    $routes->post('qualityCertificates/isActiveSetter', 'QualityCertificates::isActiveSetter');
});