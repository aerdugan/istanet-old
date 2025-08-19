<?php

use App\Models\LanguageModel;
use App\Models\CommonModel;

if (! function_exists('setSessionLanguage')) {
    /**
     * Dil koduna göre session'ı SENKRONİZE eder:
     * - data_lang (kod)
     * - lang      (kod; eski kodu kullanan yerler için)
     * - lang_id   (ID; menü gibi yerler buna bakıyorsa)
     */
    function setSessionLanguage(string $code): void
    {
        $code = strtolower(trim($code));

        $row = model(LanguageModel::class)
            ->select('id, shorten')
            ->where('isActive', 1)
            ->where('shorten', $code)
            ->first();

        if (! $row) {
            // Bulunamazsa rank=0 (site varsayılanı)
            $row = model(LanguageModel::class)
                ->select('id, shorten')
                ->where('isActive', 1)
                ->orderBy('rank', 'ASC')
                ->first();
        }

        if ($row) {
            session()->set([
                'data_lang' => $row['shorten'],
                'lang'      => $row['shorten'],
                'lang_id'   => $row['id'],
            ]);
            service('request')->setLocale($row['shorten']);
        }
    }
}

if (! function_exists('buildTree')) {
    /**
     * Düz listeyi parent_id ilişkisine göre ağaç yapısına dönüştürür.
     * Hem object hem array öğeleri destekler.
     */
    function buildTree(array $elements, $parentId = 0, int $depth = 0, int $maxDepth = 100): array
    {
        if ($depth > $maxDepth) {
            return [];
        }

        $branch = [];

        foreach ($elements as $element) {
            $pid = is_object($element) ? ($element->parent_id ?? null) : ($element['parent_id'] ?? null);
            $id  = is_object($element) ? ($element->id ?? null)        : ($element['id'] ?? null);

            if ($pid == $parentId) {
                $children = buildTree($elements, $id, $depth + 1, $maxDepth);

                if (! empty($children)) {
                    if (is_object($element)) {
                        $element->children = $children;
                    } else {
                        $element['children'] = $children;
                    }
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }
}



if (!function_exists('resolveLocalizedPath')) {
    /**
     * Bulunduğun URL'i hedef dile dönüştürür:
     * - /tr/slug → /en/mapped-slug (aynı referenceID'li sayfa)
     * - mapping bulunamazsa: /en/slug (fallback)
     * - slug yoksa: /en
     *
     * @param string $targetLang Hedef dil (tr|en|de ...)
     * @param string $table Sayfa tablosu (varsayılan: 'pages')
     * @param string $refField Referans alanı (DB'de 'referenceID' veya 'reference_id')
     * @return string             Dönüş path'i (leading slash YOK: 'en/homepages' gibi)
     */
    function resolveLocalizedPath(string $targetLang, string $table = 'pages', string $refField = 'referenceID'): string
    {
        $targetLang = strtolower($targetLang);
        $uri = service('uri');

        // Aktif diller
        $activeCodes = array_map(
            fn($L) => strtolower(is_array($L) ? ($L['shorten'] ?? '') : ($L->shorten ?? '')),
            getActiveLanguages()
        );

        // Mevcut path: "tr/anasayfa" ya da "anasayfa"
        $currentPath = ltrim($uri->getPath(), '/');
        $firstSeg = strtolower($uri->getSegment(1) ?? '');
        $slug = '';

        if ($firstSeg && in_array($firstSeg, $activeCodes, true)) {
            // Dil segmentli ise ikinci segment slug (tek seviyeli kabul)
            $slug = $uri->getSegment(2) ?? '';
        } else {
            // Dil segmenti yoksa birinci segment slug
            $slug = $firstSeg;
        }

        // Slug yoksa /{lang}
        if ($slug === '' || $slug === false) {
            return $targetLang;
        }

        // Mevcut dil
        $currentLang = session('data_lang') ?: getDefaultSiteLanguage();

        // CommonModel ile mapping
        $cm = new CommonModel();

        // 1) Mevcut slug'ın referenceID'sini bul
        //    Not: ref alan adı 'referenceID' değilse ikinci parametre ile değiştir.
        $current = $cm->selectOne([
            'url' => $slug,
            'data_lang' => $currentLang,
        ], $table, 'id, url, ' . $refField);

        if ($current && !empty($current->{$refField})) {
            // 2) Hedef dilde aynı referenceID'li kaydı bul
            $mapped = $cm->selectOne([
                $refField => $current->{$refField},
                'data_lang' => $targetLang,
            ], $table, 'id, url');

            if ($mapped && !empty($mapped->url)) {
                return $targetLang . '/' . ltrim($mapped->url, '/');
            }
        }

        // Fallback: dil değişsin ama slug aynı kalsın
        return $targetLang . '/' . ltrim($slug, '/');
    }
}