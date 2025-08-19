<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================
// ÖNCE MODULE ROUTES YÜKLE
// ========================
$modulesDir = APPPATH . 'Modules';
if (is_dir($modulesDir)) {
    $items = scandir($modulesDir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $modulePath = $modulesDir . DIRECTORY_SEPARATOR . $item;
        if (! is_dir($modulePath)) {
            continue;
        }
        $routeFile = $modulePath . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Routes.php';
        if (is_file($routeFile)) {
            require $routeFile; // module içindeki route tanımları
        }
    }
}
service('auth')->routes($routes);

// ========================
// SONRA GENEL ROUTES
// ========================

// Dinamik locale placeholder
helper('common');
$activeCodes = [];
foreach (getActiveLanguages() as $L) {
    $c = strtolower(is_array($L) ? ($L['shorten'] ?? '') : ($L->shorten ?? ''));
    if ($c) {
        $activeCodes[] = preg_quote($c, '/');
    }
}
if (empty($activeCodes)) {
    $activeCodes[] = preg_quote(strtolower(getDefaultSiteLanguage()), '/');
}
$routes->addPlaceholder('locale', '(' . implode('|', $activeCodes) . ')');

// Root → varsayılan dile yönlendir
$routes->get('/', 'Site::redirectToDefault');

// Public routes
$routes->group('{locale}', ['filter' => 'languageInit'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('(:segment)', 'Home::publicPages/$1');
    // $routes->get('(:any)', 'Home::publicPages/$1');
});