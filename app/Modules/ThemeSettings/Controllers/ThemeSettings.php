<?php

namespace App\Modules\ThemeSettings\Controllers;

use App\Controllers\BaseController;

class ThemeSettings extends BaseController
{
    // Ayar anahtarlarını tek yerde tutalım (theme.* prefix)
    private array $keys = [
        'colorCode',
        'colorCode2',
        'setHeader',
        'setMobileHeader',
        'setFooter',
        'setMobileFooter',
        'setSlider',
        'setMobileSlider',
        'setBreadcrumbStatus',
        'setMobileBreadcrumbStatus',
        'setBreadcrumb',
        'setMobileBreadcrumb',
        'setTheme',            // int (select)
        'isMobile',            // bool
        'setMobileTheme',      // int (select)
        'setLogo',             // int (select - media id vs.)
        'setWhiteLogo',        // int
        'setMobileLogo',       // int
        'setMobileWhiteLogo',  // int
        'headerSocial',
        'mobileHeaderSocial',
        'headerLang',
        'mobileHeaderLang',
        'setFoterSocial',        // yazım korunuyor
        'setMobileFoterSocial',  // yazım korunuyor
        'setFooterContact',
        'setMobileFooterContact',
        'setFooterNewsLetter',
        'setMobileFooterNewsLetter',
        'copyright',          // text
        'openClose',          // text
        'siteStatus',         // bool
        'siteCloseMessage',   // longtext
    ];

    // Boolean olanlar (checkbox)
    private array $booleanKeys = [
        'setBreadcrumbStatus',
        'setMobileBreadcrumbStatus',
        'isMobile',
        'headerSocial',
        'mobileHeaderSocial',
        'headerLang',
        'mobileHeaderLang',
        'setFoterSocial',
        'setMobileFoterSocial',
        'setFooterContact',
        'setMobileFooterContact',
        'setFooterNewsLetter',
        'setMobileFooterNewsLetter',
        'siteStatus',
    ];

    public function index()
    {
        helper('setting'); // setting() ve settings() için

        $data = [];
        foreach ($this->keys as $k) {
            $data[$k] = setting('theme.' . $k); // yoksa null döner
        }

        // Varsayılanlar (isteğe bağlı)
        $data['colorCode']  = $data['colorCode']  ?? '#000000';
        $data['colorCode2'] = $data['colorCode2'] ?? '#ffffff';

        return view('Modules\ThemeSettings\Views\form', $data);
    }

    public function save()
    {
        helper(['setting', 'text']);

        $post = $this->request->getPost() ?? [];

        // Checkbox alanları, gönderilmediyse 0 kabul edelim
        foreach ($this->booleanKeys as $bk) {
            $post[$bk] = isset($post[$bk]) && (string)$post[$bk] === '1' ? 1 : 0;
        }

        // Basit validasyon kuralları
        $rules = [
            'colorCode'               => 'permit_empty|max_length[10]',
            'colorCode2'              => 'permit_empty|max_length[10]',
            'setHeader'               => 'permit_empty|max_length[255]',
            'setMobileHeader'         => 'permit_empty|max_length[255]',
            'setFooter'               => 'permit_empty|max_length[255]',
            'setMobileFooter'         => 'permit_empty|max_length[255]',
            'setSlider'               => 'permit_empty|max_length[255]',
            'setMobileSlider'         => 'permit_empty|max_length[255]',
            'setBreadcrumb'           => 'permit_empty|max_length[255]',
            'setMobileBreadcrumb'     => 'permit_empty|max_length[255]',
            'setTheme'                => 'permit_empty|integer',
            'isMobile'                => 'permit_empty|in_list[0,1]',
            'setMobileTheme'          => 'permit_empty|integer',
            'setLogo'                 => 'permit_empty|integer',
            'setWhiteLogo'            => 'permit_empty|integer',
            'setMobileLogo'           => 'permit_empty|integer',
            'setMobileWhiteLogo'      => 'permit_empty|integer',
            'headerSocial'            => 'permit_empty|in_list[0,1]',
            'mobileHeaderSocial'      => 'permit_empty|in_list[0,1]',
            'headerLang'              => 'permit_empty|in_list[0,1]',
            'mobileHeaderLang'        => 'permit_empty|in_list[0,1]',
            'setFoterSocial'          => 'permit_empty|in_list[0,1]',
            'setMobileFoterSocial'    => 'permit_empty|in_list[0,1]',
            'setFooterContact'        => 'permit_empty|in_list[0,1]',
            'setMobileFooterContact'  => 'permit_empty|in_list[0,1]',
            'setFooterNewsLetter'     => 'permit_empty|in_list[0,1]',
            'setMobileFooterNewsLetter'=> 'permit_empty|in_list[0,1]',
            'copyright'               => 'permit_empty',
            'openClose'               => 'permit_empty',
            'siteStatus'              => 'permit_empty|in_list[0,1]',
            'siteCloseMessage'        => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Doğrulama hatası')->withInput();
        }

        // Kaydet
        $save = [];
        foreach ($this->keys as $k) {
            // Formda olmayanlar null gelebilir; null kaydetmeyelim:
            if (array_key_exists($k, $post)) {
                $save['theme.' . $k] = $post[$k];
            }
        }

        settings()->setMany($save);

        return redirect()->to(route_to('theme.settings'))
            ->with('message', 'Tema ayarları kaydedildi.');
    }
}
