<?php

namespace Config;

$routes = Services::routes();

$routes->group('admin/general', ['namespace' => 'App\Modules\General\Controllers'], static function ($routes) {
    // General Settings
    $routes->get('', 'General::index');
    $routes->post('companySave', 'General::companySettingsUpdate');

    // Contact Settings
    $routes->get('contact', 'General::contactSettings');
    $routes->post('saveContact', 'General::contactAdd');
    $routes->post('updateContact/(:any)', 'General::contactUpdate/$1');
    $routes->post('deleteContact/(:any)', 'General::contactDelete/$1');

    // Other Settings
    $routes->get('other', 'General::otherSettings');
    $routes->post('otherSave', 'General::otherSettingsUpdate');

    // Logo Settings
    $routes->get('logo', 'General::logoSettings');
    $routes->post('logoSave', 'General::logoSettingsUpdate');

    // SEO Settings
    $routes->get('seo', 'General::seoSettings');
    $routes->post('seoSave', 'General::seoSettingsUpdate');

    // Social Media Settings
    $routes->get('social', 'General::socialSettings');
    $routes->post('socialSave', 'General::socialSettingsUpdate');

    $routes->get('socials',  'General::socials',      ['as' => 'backend.general.socials']);
    $routes->post('socials', 'General::socialsSave',  ['as' => 'backend.general.socials.save']);

    // Common Actions
    $routes->post('edit', 'General::edit');
    $routes->post('store', 'General::store');
    $routes->post('delete', 'General::delete');
    $routes->post('updateRank', 'General::updateRank');
    $routes->post('isActiveSetter', 'General::isActiveSetter');
});
