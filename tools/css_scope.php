<?php
/**
 * PHP CSS Scoper
 * Kullanım:
 *   php tools/css_scope.php input.css output.scoped.css ":is(#container-builder, #isContentBox)"
 *
 * Notlar:
 * - @keyframes blokları korunur, içi prefix'lenmez (yüzdeler/from/to bozulmasın diye)
 * - @media/@supports/@layer içindeki normal kurallar prefix'lenir
 * - Normal kurallarda selector listesi virgülle bölünür ve her biri prefix'lenir
 */

if ($argc < 3) {
    fwrite(STDERR, "Kullanım: php {$argv[0]} <input.css> <output.css> [prefix]\n");
    exit(1);
}

$inFile  = $argv[1];
$outFile = $argv[2];
$prefix  = $argv[3] ?? ':is(#container-builder, #isContentBox)';

if (!is_file($inFile)) {
    fwrite(STDERR, "Hata: Girdi dosyası bulunamadı: {$inFile}\n");
    exit(1);
}

$css = file_get_contents($inFile);
if ($css === false) {
    fwrite(STDERR, "Hata: Dosya okunamadı: {$inFile}\n");
    exit(1);
}

/**
 * 1) @keyframes bloklarını ayıkla ve yer tutucu ile değiştir
 */
$keyframes = [];
$placeholder = "\n/*__KEYFRAMES_BLOCK_%d__*/\n";

$css = preg_replace_callback(
// @keyframes foo { ... }  |  @-webkit-keyframes foo { ... }
    '/@(-?\w*keyframes)\s+[^{]+\{(?:[^{}]+|\{[^{}]*\}|(?R))*\}/i',
    function ($m) use (&$keyframes, $placeholder) {
        $idx = count($keyframes);
        $keyframes[$idx] = $m[0];
        return sprintf($placeholder, $idx);
    },
    $css
);

/**
 * 2) Normal kuralları prefix'le (keyframes’ler artık yok)
 *    - @media/@supports içinde de çalışır (çünkü aynı regex tüm içeriğe uygulanıyor)
 */
$css = preg_replace_callback(
// Bir kuralın başındaki selector listesi:  ... }  SELECTOR {  ...
    '/(^|})\s*([^@}{]+?)\s*\{/m',
    function ($m) use ($prefix) {
        $before   = $m[1];          // "}" ya da satır başı
        $selectors= trim($m[2]);

        // Yalın bloksa ya da boşsa dokunma
        if ($selectors === '' || strpos($selectors, '{') !== false) {
            return $m[0];
        }

        // Virgülle ayrılmış selector listesi
        $parts = array_map('trim', explode(',', $selectors));
        $scoped = [];

        foreach ($parts as $sel) {
            if ($sel === '') continue;

            // Zaten prefix'lenmişse tekrar etme
            if (strpos($sel, $prefix) === 0 || strpos($sel, $prefix.' ') === 0) {
                $scoped[] = $sel;
                continue;
            }

            // @-kuralları (örn. @page) burada normalde yakalanmaz ama güvenlik için
            if ($sel[0] === '@') {
                $scoped[] = $sel;
                continue;
            }

            // Selector'ı prefix'le:  :is(#container-builder, #isContentBox) <selector>
            $scoped[] = $prefix . ' ' . $sel;
        }

        return $before . ' ' . implode(', ', $scoped) . ' {';
    },
    $css
);

/**
 * 3) @keyframes bloklarını geri koy
 */
$css = preg_replace_callback(
    '/\/\*__KEYFRAMES_BLOCK_(\d+)__\*\/\s*/',
    function ($m) use ($keyframes) {
        $idx = (int)$m[1];
        return $keyframes[$idx] ?? '';
    },
    $css
);

// Çıkışı yaz
if (false === file_put_contents($outFile, $css)) {
    fwrite(STDERR, "Hata: Çıkış dosyası yazılamadı: {$outFile}\n");
    exit(1);
}

fwrite(STDOUT, "✓ Scoped CSS yazıldı: {$outFile}\n");