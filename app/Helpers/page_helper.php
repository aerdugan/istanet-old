<?php
// app/Helpers/page_helper.php

if (! function_exists('getDefaultAdminLanguage')) {
    // Projene göre ayarla: setting('site.defaultAdminLang') gibi okuyorsan onu kullan.
    function getDefaultAdminLanguage(): string
    {
        return setting('site.defaultAdminLang') ?? 'tr';
    }
}

if (! function_exists('getSessionDataLang')) {
    function getSessionDataLang(): string
    {
        // Öncelik: data_lang → yoksa lang → yoksa default
        return session()->get('data_lang')
            ?? session()->get('lang')
            ?? getDefaultAdminLanguage();
    }
}


/**
 * Alan bazlı çeviri: başlıklar düz metin, içerikler HTML.
 * $source => varsayılan dil sayfası (kaynak)
 * $target => çeviri yapılacak sayfa (hedef)
 */
if (! function_exists('translate_page_fields_from_source')) {
    function translate_page_fields_from_source(array $source, array $target, string $sourceLang, string $targetLang): array
    {
        // Düz metin alanları
        $fieldsText = ['title', 'seoDesc', 'breadcrumbTitle', 'breadcrumbSlogan'];
        foreach ($fieldsText as $f) {
            if (!empty($source[$f])) {
                $target[$f] = gpt_translate_text((string)$source[$f], $sourceLang, $targetLang);
            }
        }

        // SEO keywords: virgül/JSON karışık olabilir → normalize et ve çevirme (genelde çeviri gereksiz)
        if (!empty($source['seoKeywords'])) {
            // İstersen çevir: genelde keywordleri aynen bırakmak daha güvenli.
            // $target['seoKeywords'] = gpt_translate_text((string)$source['seoKeywords'], $sourceLang, $targetLang);
            $target['seoKeywords'] = (string)$source['seoKeywords'];
        }

        // HTML alanları
        $htmlFields = ['inpHtml', 'mobileHtml', 'cBoxContent', 'cBoxMobileContent'];
        foreach ($htmlFields as $f) {
            $src = (string)($source[$f] ?? '');
            if ($src !== '') {
                $target[$f] = gpt_translate_html($src, $sourceLang, $targetLang);
            }
        }

        // URL'i otomatik çevirmeyelim; slug kuralları farklı.
        // $target['url'] = $target['url'] ?? $source['url'];

        return $target;
    }
}
