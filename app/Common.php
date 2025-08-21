<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */


// Bu dosyada helper(), service(), session() ÇAĞIRMAYIN.

// Örnek: basit bir yardımcı fonksiyon
if (!function_exists('app_version')) {
    function app_version(): string
    {
        return '1.0.0';
    }
}

if (!function_exists('getPages')) {
    function getPages($db)
    {
        $query = $db->table('pages')
            ->select('*')
            ->where('isActive', 1)
            ->orderBy('rank', 'ASC')
            ->get();

        return $query->getResult();
    }
}

// BURADA buildTree TANIMI OLMAYACAK.

use CodeIgniter\Settings\Settings;

if (! function_exists('setting')) {
    function setting(?string $key = null, $value = null)
    {
        $setting = service('settings'); /** @var Settings $setting */

        if (empty($key)) {
            return $setting;
        }
        if (func_num_args() === 1) {
            return $setting->get($key);
        }
        $setting->set($key, $value);
    }
}