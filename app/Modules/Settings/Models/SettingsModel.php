<?php

namespace App\Modules\Settings\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';

    protected $allowedFields = ['key', 'value'];

    public function get($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function getSetting($key)
    {
        return $this->where('setting_key', $key)->value('setting_value');
    }
}