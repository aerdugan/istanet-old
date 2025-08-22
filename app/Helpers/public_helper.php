<?php
use CodeIgniter\Settings\Settings;
use App\Models\CommonModel;

if (!function_exists('getThemeSettings')) {
    function getThemeSettings()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('themeSettings');
        return $builder->get()->getRow();
    }
}
if (!function_exists('getMobileThemeSettings')) {
    function getMobileThemeSettings()
    {
        // Service loader
        $db = \Config\Database::connect();
        $builder = $db->table('mobileThemeSettings');

        // Veriyi sorgula
        return $builder->get()->getRow();
    }
}

if (!function_exists('getCompanyDefaultAddress')) {
    function getCompanyDefaultAddress()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->address;
    }
}
if (!function_exists('getCompanyDefaultLocation')) {
    function getCompanyDefaultLocation()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->address_location;
    }
}
if (!function_exists('getCompanyDefaultPhone')) {
    function getCompanyDefaultPhone()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->phone_1;
    }
}
if (!function_exists('getCompanyDefaultEmail')) {
    function getCompanyDefaultEmail()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->email;
    }
}
if (!function_exists('setThemeFirstColor')) {
    function setThemeFirstColor()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('themeSettings');
        $theme_settings = $builder->get()->getRow();
        echo $theme_settings->colorCode;
    }
}
if (!function_exists('setThemeSecondColor')) {
    function setThemeSecondColor()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('themeSettings');
        $theme_settings = $builder->get()->getRow();
        echo $theme_settings->colorCode2;
    }
}

if (!function_exists('setCompanyName')) {
    function setCompanyName()
    {
        setting('site.logo.company_name');
    }
}

if (!function_exists('setContentBox')) {
    function setContentBox(){
        echo "https://cdn.istanet.com/cBox/cBox5830/";
    }
}

if (!function_exists('setCompanyName')) {
    function setCompanyName()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('logoSettings');
        $logo_settings = $builder->get()->getRow();
        echo $logo_settings->company_name;
    }
}
if (!function_exists('companyLongName')) {
    function companyLongName()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('logoSettings');
        $logo_settings = $builder->get()->getRow();
        echo $logo_settings->companyLongName;
    }
}
if (!function_exists('getCompanyDefaultAddress')) {
    function getCompanyDefaultAddress()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->address;
    }
}
if (!function_exists('getCompanyDefaultLocation')) {
    function getCompanyDefaultLocation()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->address_location;
    }
}
if (!function_exists('getCompanyDefaultPhone')) {
    function getCompanyDefaultPhone()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->phone_1;
    }
}
if (!function_exists('ol_treeKallyas')) {
    function ol_treeKallyas($child = [])
    {
        if (!empty($child)) {
            $lang = session('lang') ?? 'tr'; // Aktif dil
            echo '<ul class="sub-menu clearfix">';
            foreach ($child as $item) {
                echo '<li>';
                if (!empty($item->children)) {
                    if ($item->url != '#') {
                        echo '<a href="' . base_url($lang . '/' . $item->url) . '">';
                        echo esc($item->title);
                        echo '</a>';
                    } else {
                        echo '<a href="#">' . esc($item->title) . '</a>';
                    }
                    ol_treeKallyas($item->children); // recursive
                } else {
                    if ($item->url != '#') {
                        echo '<a href="' . base_url($lang . '/' . $item->url) . '">';
                        echo esc($item->title);
                        echo '</a>';
                    } else {
                        echo '<a href="#">' . esc($item->title) . '</a>';
                    }
                }
                echo '</li>';
            }
            echo '</ul>';
        }
        return;
    }
}
if (!function_exists('getCompanyDefaultEmail')) {
    function getCompanyDefaultEmail()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('contactSettings');
        $contact_settings = $builder->where('rank', 0)->get()->getRow();
        echo $contact_settings->email;
    }
}

if (!function_exists('getAllPages')) {
    function getAllPages() {
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $lang = $session->get('data_lang');

        $builder = $db->table('pages'); // Specify the table 'pages'

        $query = $builder->where('data_lang', $lang)
            ->where('isActive', 1)
            ->where('isHeader', 1)
            ->orderBy('rank', 'ASC')  // Optional: Order by 'rank'
            ->get();

        return $query->getResult();
    }
}

if (! function_exists('getFrontEndLanguageMenuItem')) {
    function getFrontEndLanguageMenuItem(): string
    {
        helper('common');

        $langs = getActiveLanguages();
        if (empty($langs)) return '';

        $current = strtolower(session('data_lang') ?? getDefaultSiteLanguage());

        $request = service('request');
        $qs      = (string) $request->getServer('QUERY_STRING');

        // Aktif dil başlık
        $activeLabel = strtoupper($current);
        foreach ($langs as $L) {
            $code  = is_array($L) ? strtolower($L['shorten'] ?? '') : strtolower($L->shorten ?? '');
            $title = is_array($L) ? ($L['title'] ?? '')             : ($L->title ?? '');
            if ($code === $current && $title) { $activeLabel = $title; break; }
        }

        // Alt menü linkleri
        $items = '';
        foreach ($langs as $L) {
            $code  = is_array($L) ? strtolower($L['shorten'] ?? '') : strtolower($L->shorten ?? '');
            $title = is_array($L) ? ($L['title'] ?? strtoupper($code)) : ($L->title ?? strtoupper($code));
            if (! $code) continue;

            // ⚠️ Burada reference mapping var.
            // Tablo alanın 'reference_id' ise bırak; 'referenceID' kullanıyorsan 3. parametreyi değiştir.
            $path = resolveLocalizedPath($code, 'pages', 'referenceID'); // veya 'referenceID'
            $href = base_url($path) . ($qs ? ('?' . $qs) : '');

            $active = $code === $current ? ' active' : '';
            $items .= '<li class="toplang-item' . $active . '">'
                .   '<a href="' . esc($href) . '">'
                .       '<img src="' . base_url('images/'.$code.'.svg') . '" alt="' . esc($title) . '" class="toplang-flag"> '
                .       esc($title)
                .   '</a>'
                . '</li>';
        }

        return '<li class="menu-item-has-children no-bg languages-li">'
            .     '<a href="#" class="topnav-item">'
            .         '<span class="fas fa-globe xs-icon">&nbsp;&nbsp;&nbsp;</span>'
            .         '<span class="topnav-item--text">' . esc($activeLabel) . '</span>'
            .     '</a>'
            .     '<ul class="sub-menu inner">' . $items . '</ul>'
            . '</li>';
    }
}



if (!function_exists('resolveLocalizedPath')) {
    /**
     * Geçerli path'i hedef dile çevirir.
     * - /tr/slug -> /en/<mapped-slug> (aynı reference_id)
     * - mapping yoksa VEYA current sayfada reference_id yoksa -> /en (hedef dilin anasayfası)
     * - slug hiç yoksa -> /en
     *
     * @param string $targetLang Hedef dil (tr|en|de ...)
     * @param string $table Tablo adı (varsayılan: 'pages')
     * @param string $refField Referans alanı adı ('reference_id' veya 'referenceID')
     * @return string             'en/home' veya 'en' gibi (BAŞINDA / YOK)
     */
    function resolveLocalizedPath(string $targetLang, string $table = 'pages', string $refField = 'referenceID'): string
    {
        $targetLang = strtolower(trim($targetLang));

        // Aktif diller (ilk segmenti temizlemek için)
        $activeCodes = array_map(
            fn($L) => strtolower(is_array($L) ? ($L['shorten'] ?? '') : ($L->shorten ?? '')),
            getActiveLanguages()
        );

        $uri = service('uri');
        $firstSeg = strtolower($uri->getSegment(1) ?? '');
        $secondSeg = $uri->getSegment(2) ?? '';
        $currentLang = session('data_lang') ?: getDefaultSiteLanguage();

        // Mevcut slug (yalnızca /{lang}/{slug} tek seviyeli kabul)
        $slug = '';
        if ($firstSeg && in_array($firstSeg, $activeCodes, true)) {
            $slug = $secondSeg;          // /tr/slug
        } else {
            $slug = $firstSeg;           // /slug (dilsiz prefix ihtimali)
        }

        // Slug yoksa doğrudan hedef dilin kökü
        if (!$slug) {
            return $targetLang;          // → /en
        }

        $cm = new CommonModel();

        // 1) Mevcut slug'ın reference_id'sini bul
        $current = $cm->selectOne([
            'url' => $slug,
            'data_lang' => $currentLang,
        ], $table, 'id, url, ' . $refField);

        // reference_id yoksa → hedef dilin anasayfası
        if (!$current || empty($current->{$refField})) {
            return $targetLang;          // → /en
        }

        // 2) Hedef dilde aynı reference_id'li kaydı bul
        $mapped = $cm->selectOne([
            $refField => $current->{$refField},
            'data_lang' => $targetLang,
        ], $table, 'id, url');

        // bulunduysa mapped slug'a, yoksa anasayfaya
        return $mapped && !empty($mapped->url)
            ? ($targetLang . '/' . ltrim($mapped->url, '/'))
            : $targetLang;               // → /en
    }
}
if (!function_exists('getAllSlides')) {
    function getAllSlides() {
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $lang = session('data_lang') ?: getDefaultSiteLanguage();

        $builder = $db->table('sliders'); // Specify the table 'pages'

        $query = $builder->where('data_lang', $lang)
            ->where('isActive', 1)
            ->orderBy('rank', 'ASC')  // Optional: Order by 'rank'
            ->get();

        return $query->getResult();
    }
}


if (! function_exists('setting')) {
    /**
     * Provides a convenience interface to the Settings service.
     *
     * @param mixed $value
     *
     * @return array|bool|float|int|object|Settings|string|void|null
     * @phpstan-return ($key is null ? Settings : ($value is null ? array|bool|float|int|object|string|null : void))
     */
    function setting(?string $key = null, $value = null)
    {
        /** @var Settings $setting */
        $setting = service('settings');

        if (empty($key)) {
            return $setting;
        }

        // Getting the value?
        if (count(func_get_args()) === 1) {
            return $setting->get($key);
        }

        // Setting the value
        $setting->set($key, $value);
    }
}
