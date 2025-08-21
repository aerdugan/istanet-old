<?php
namespace App\Controllers;

use App\Models\CommonModel;

class Home extends BaseController
{
    /** @var CommonModel */
    protected $common_model;

    /** @var mixed|null  İstersen bir Referans modeli kullanırsın; yoksa null kalsın */
    protected $referenceModel = null;

    public function __construct()
    {
        helper(['public', 'admin', 'general_helper', 'common','setting']);

        $this->common_model = new CommonModel();

        // Eğer daha önce dil set edilmemişse site varsayılanıyla başlat (rank=0)
        if (! session()->has('data_lang')) {
            setSessionLanguage(getDefaultSiteLanguage());
        }
    }

    /**
     * URI’nin ilk segmenti bir dil koduysa (tr/en/de...), session dilini günceller.
     * Her action'ın başında çağırıyoruz.
     */
    private function syncLocaleFromUri(): void
    {
        $locale = strtolower(service('uri')->getSegment(1) ?? '');
        if ($locale) {
            setSessionLanguage($locale); // data_lang, lang, lang_id hepsi senkron olur
        }
    }

    /** Demo amaçlı bırakıldı */
    public function index3(): string
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        return view('welcome_message', [
            'currentLang' => $lang,
        ]);
    }

    public function index(): string
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        $data['themeSettings']       = getThemeSettings();
        $data['mobileThemeSettings'] = getMobileThemeSettings(); // önce yorumluydu; şimdi gerçekten kullanılıyor
        $agent = $this->request->getUserAgent();

        $data['items'] = $this->common_model->selectOne([
            'data_lang' => $lang,
            'isActive'  => 1,
            'rank'      => 1
        ], 'pages');

        // Sliderlar
        $data['sliders'] = $this->common_model->lists([
            'data_lang' => $lang
        ], 'sliders', '*', 'rank ASC');

        // Tema durumuna göre view seçimi
        if (!empty($data['themeSettings']->siteStatus) && (int)$data['themeSettings']->siteStatus === 1) {
            if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
                return view('mobile/pages/index', $data);
            }
            return view('public/pages/index', $data);
        }

        if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
            return view('underConstruction/mobile/index', $data);
        }
        return view('underConstruction/index', $data);
    }

    /**
     * Çok dilli sayfa gösterimi: /{locale}/{url}
     */
    public function publicPages(string $url)
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        $data['themeSettings']       = getThemeSettings();
        $data['mobileThemeSettings'] = getMobileThemeSettings();
        $agent = $this->request->getUserAgent();

        // Tüm sayfalar (menüler vs. için)
        $data['allPages'] = $this->common_model->lists([
            'data_lang' => $lang
        ], 'pages', '*', 'rank ASC');

        // İstenen sayfa
        $data['items'] = $this->common_model->selectOne([
            'url'       => $url,
            'data_lang' => $lang
        ], 'pages');

        if (! $data['items']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sayfa bulunamadı: {$url}");
        }

        $data['pagesBreadcrumbs'] = $this->getPagesBreadcrumbs($data['items']->id);

        if (!empty($data['themeSettings']->siteStatus) && (int)$data['themeSettings']->siteStatus === 1) {
            if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
                return view('mobile/pages/index', $data);
            }
            return view('public/pages/index', $data);
        }

        if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
            return view('underConstruction/mobile/index', $data);
        }
        return view('underConstruction/index', $data);
    }

    public function submenu($parentId)
    {
        $data['pages']  = getSubMenuPages($parentId);
        $data['parent'] = getPageById($parentId);
        return view('mobile/pages/submenu', $data);
    }

    protected function bubbleSort(array &$arr): array
    {
        $n = count($arr);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($arr[$j]['rank'] > $arr[$j + 1]['rank']) {
                    $tmp        = $arr[$j];
                    $arr[$j]    = $arr[$j + 1];
                    $arr[$j + 1]= $tmp;
                }
            }
        }
        return $arr;
    }

    protected function getPagesBreadcrumbs($page_id): array
    {
        $lang = session('data_lang');

        $current_page = $this->common_model->selectOne(['id' => $page_id, 'data_lang' => $lang], 'pages');
        $homepage     = $this->common_model->selectOne(['data_lang' => $lang, 'rank' => 1], 'pages');

        if (! $current_page || ! $homepage) {
            return [];
        }

        $breadcrumbs = [];

        if ((int)$current_page->rank === 1) {
            return $breadcrumbs;
        }

        // Anasayfa breadcrumb başa
        array_unshift($breadcrumbs, [
            'title' => $homepage->title,
            'url'   => $homepage->url,
            'rank'  => 1
        ]);

        $tmpCurrentPage = $current_page;
        while (!empty($tmpCurrentPage->parent_id)) {
            $parent_page = $this->common_model->selectOne([
                'id'     => $tmpCurrentPage->parent_id,
                'rank!=' => 1
            ], 'pages');

            if (! $parent_page) {
                break;
            }

            $breadcrumbs[] = [
                'title' => $parent_page->title,
                'url'   => $parent_page->url,
                'rank'  => $parent_page->rank
            ];

            $tmpCurrentPage = $parent_page;
        }

        // Son olarak mevcut sayfa
        $breadcrumbs[] = [
            'title' => $current_page->title,
            'url'   => '',
            'rank'  => 99999
        ];

        return $this->bubbleSort($breadcrumbs);
    }

    public function references()
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        $data['themeSettings']       = getThemeSettings();
        $data['mobileThemeSettings'] = getMobileThemeSettings();
        $agent = $this->request->getUserAgent();

        $data['items'] = $this->common_model->selectOne([
            'data_lang' => $lang,
            'isActive'  => 1,
            'rank'      => 1
        ], 'references', '*', 'rank ASC');

        if (!empty($data['themeSettings']->siteStatus) && (int)$data['themeSettings']->siteStatus === 1) {
            if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
                return view('mobile/'.lang('references').'/index', $data);
            }
            return view('public/'.lang('references').'/index', $data);
        }

        if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
            return view('underConstruction/mobile/index', $data);
        }
        return view('underConstruction/index', $data);
    }

    public function referenceDetail1(string $slug)
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        $data['themeSettings']       = getThemeSettings();
        $data['mobileThemeSettings'] = getMobileThemeSettings();
        $agent = $this->request->getUserAgent();

        $data['items'] = $this->common_model->selectOne([
            'url'       => $slug,
            'data_lang' => $lang
        ], 'references');

        if (! $data['items']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sayfa bulunamadı: {$slug}");
        }

        logVisitorOncePerHour($data['items']->title);
        $data['pagesBreadcrumbs'] = $this->getPagesBreadcrumbs($data['items']->id);

        if (!empty($data['themeSettings']->siteStatus) && (int)$data['themeSettings']->siteStatus === 1) {
            if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
                return view('mobile/references/detail', $data);
            }
            return view('public/references/detail', $data);
        }

        if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
            return view('underConstruction/mobile/index', $data);
        }
        return view('underConstruction/index', $data);
    }

    public function referenceDetail(string $slug)
    {
        $this->syncLocaleFromUri();
        $lang = session('data_lang');

        $data['themeSettings']       = getThemeSettings();
        $data['mobileThemeSettings'] = getMobileThemeSettings();
        $agent = $this->request->getUserAgent();

        $data['items'] = $this->common_model->selectOne([
            'url'       => $slug,
            'data_lang' => $lang
        ], 'references');

        if (! $data['items']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sayfa bulunamadı: {$slug}");
        }

        // Not: Dump'ta tablo adı "referenceImages" yerine "reference_images" olabilir.
        // Şemanı kontrol edip buna göre düzelt:
        $data['referenceImages'] = $this->common_model->lists(
            [
                'reference_id' => $data['items']->id,
                'isActive'     => 1
            ],
            'referenceImages', // gerekiyorsa 'reference_images'
            '*',
            'rank ASC'
        );

        logVisitorOncePerHour($data['items']->title);
        $data['pagesBreadcrumbs'] = $this->getPagesBreadcrumbs($data['items']->id);

        if (!empty($data['themeSettings']->siteStatus) && (int)$data['themeSettings']->siteStatus === 1) {
            if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
                return view('mobile/references/detail', $data);
            }
            return view('public/references/detail', $data);
        }

        if ($agent->isMobile() && !empty($data['mobileThemeSettings']->isMobile)) {
            return view('underConstruction/mobile/index', $data);
        }
        return view('underConstruction/index', $data);
    }

    // Mobile
    public function menuMain()  { return view('mobile/inc/menuMain'); }
    public function menuFooter(){ return view('mobile/inc/menuFooter'); }
    public function menuColors(){ return view('mobile/inc/menuColors'); }
    public function menuShare() { return view('mobile/inc/menuShare'); }
}