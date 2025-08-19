<?php

namespace App\Modules\Template\Models;

use CodeIgniter\Model;

class ThemeModel extends Model
{
    protected $table = 'themeSettings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'siteDisableTheme','siteCloseMessage','siteStatus','colorCode', 'colorCode2', 'setHeader', 'setFooter', 'setSlider',
        'setBreadcrumb', 'setLogo', 'setFooterLogo', 'copyright',
        'headerSocial', 'headerLang', 'footerSocial', 'breadcrumbStatus',
        'updatedAt', 'lastUpdatedUser',
    ];
}
