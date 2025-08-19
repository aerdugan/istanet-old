<?php
namespace App\Modules\Users\Config;
use CodeIgniter\Config\BaseConfig;

class Permissions extends BaseConfig
{
    // 'permissions' (app tablon) veya 'auth_permissions' (Shield tablosu)
    public string $table = 'permissions';
}