<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\{RequestInterface, ResponseInterface};

class LanguageInitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Eğer session’da dil zaten varsa sadece Request locale'i ayarla
        if ($session->has('data_lang') && $session->get('data_lang')) {
            service('request')->setLocale($session->get('data_lang'));
            return;
        }

        // Yardımcı fonksiyonlar
        helper('admin');

        $uri = service('uri');
        $firstSeg = strtolower($uri->getSegment(1) ?? '');

        // 1) Admin alanı: rank=1 dili kullan
        if ($firstSeg === 'admin') {
            $adminLang = \getDefaultAdminLanguage();   // rank=1
            \setSessionLanguage($adminLang);
            return;
        }

        // 2) Site alanı: URL’nin ilk segmenti dil koduysa onu al
        // Örn: /tr, /en
        $active = array_column(\getActiveLanguages(), 'shorten');
        if ($firstSeg && in_array($firstSeg, $active, true)) {
            \setSessionLanguage($firstSeg);
            return;
        }

        // 3) İlk seg. dil değilse, site varsayılan dil (rank=0) session’a
        $defaultSiteLang = \getDefaultSiteLanguage(); // rank=0
        \setSessionLanguage($defaultSiteLang);
        // Not: Yönlendirme işi Routes’ta yapılacak
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}