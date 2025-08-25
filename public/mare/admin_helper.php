<?php
use App\Models\LanguageModel;
use App\Modules\Users\Models\UserRoleModel;
use App\Modules\Users\Models\RolePermissionModel;
use App\Modules\Users\Models\PermissionModel;

if (! function_exists('user_can')) {
    /**
     * @param string $permission Slug (ör: 'files.files.index')
     * @param bool   $forceRefresh true ise session cache yok sayılır
     */
    function user_can(string $permission, bool $forceRefresh = false): bool
    {
        helper('auth');

        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $userId = (int) $user->id;

        // normalize
        $permission = strtolower(trim($permission));

        // --- Session cache
        $cacheKey = "perm_cache_user_{$userId}";
        if (! $forceRefresh) {
            $cached = session()->get($cacheKey);
            if (is_array($cached)) {
                return in_array($permission, $cached, true);
            }
        }

        // --- Roller
        $urm = new \App\Modules\Users\Models\UserRoleModel();
        $roles = $urm->getRolesByUserId($userId);
        $roleIds = [];
        foreach ($roles as $r) {
            $roleIds[] = (int) (is_array($r) ? $r['role_id'] : $r->role_id);
        }
        if (empty($roleIds)) {
            session()->set($cacheKey, []);
            return false;
        }

        // --- Join'li tek sorgu: isimleri çekelim
        $db = db_connect();
        $names = $db->table('user_roles ur')
            ->select('LOWER(p.name) AS name', false)
            ->join('role_permissions rp', 'rp.role_id = ur.role_id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', $userId)
            ->groupBy('p.name')
            ->get()->getResultArray();

        $list = array_map(fn($x) => $x['name'], $names);

        session()->set($cacheKey, $list);

        return in_array($permission, $list, true);
    }
}
if (! function_exists('can')) {
    function can(string $permission, bool $forceRefresh = false): bool
    {
        return user_can($permission, $forceRefresh);
    }
}

if (!function_exists('translateWithGPT4o')) {
    function translateWithGPT4o(string $text, string $targetLang): ?string
    {
        $defaultLangRow = getDefaultLanguage();
        if (!$defaultLangRow || empty($defaultLangRow['shorten'])) {
            log_message('error', 'Varsayılan dil bulunamadı.');
            return null;
        }
        $sourceLang = $defaultLangRow['shorten']; // ✅ her zaman rank=1 dil

        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'OpenAI API key missing.');
            return null;
        }

        $url = 'https://api.openai.com/v1/chat/completions';

        $userPrompt = <<<EOT
Aşağıdaki HTML içeriği kaynak dilde ({$sourceLang}) yazılmıştır. Hedef dil: {$targetLang}.

❗️ Lütfen sadece kullanıcıya görünen metinleri çevir.
‼️ HTML etiketlerine (<div>, <p>, <span>, <img>, class, id, href, style, script vb.) dokunma.
🔒 Yapıyı, boşlukları ve HTML düzenini koru.

[Çevrilecek içerik aşağıdadır]
$text
EOT;

        $systemPrompt = <<<SYS
You are a professional HTML translator.
Translate only visible human-readable text from {$sourceLang} to {$targetLang}.
Do not alter HTML tags, attributes, CSS, or JavaScript.
Keep formatting and structure untouched.
SYS;

        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => 0.2,
            'max_tokens' => 4000,
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error || $httpStatus !== 200) {
            log_message('error', "OpenAI Error: HTTP $httpStatus - $error - $response");
            return null;
        }

        $responseData = json_decode($response, true);
        $translated = $responseData['choices'][0]['message']['content'] ?? null;

        if ($translated) {
            $translated = preg_replace('/^\[.*?\]\s*/s', '', $translated);
            $translated = preg_replace('/^```html\s*/i', '', $translated);
            $translated = preg_replace('/```$/', '', $translated);
        }

        if ($translated && substr_count($text, '<') !== substr_count($translated, '<')) {
            log_message('error', 'Tag mismatch after translation! Tag sayısı uyuşmuyor.');
        }

        return $translated;
    }
}
if (!function_exists('translateWithGPT4o1')) {
    function translateWithGPT4o1(string $text, string $sourceLang, string $targetLang): ?string
    {
        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'OpenAI API key missing.');
            return null;
        }

        $url = 'https://api.openai.com/v1/chat/completions';

        $userPrompt = <<<EOT
Aşağıdaki HTML içeriğini, kaynak dilde ({$sourceLang}) yazılmıştır. Hedef dil: {$targetLang}.

❗️ Lütfen sadece kullanıcıya görünen metinleri çevir.
‼️ HTML etiketlerine (<div>, <p>, <span>, <img>, class, id, href, style, script vb.) dokunma.
🔒 Yapıyı, boşlukları ve HTML düzenini koru.

[Çevrilecek içerik aşağıdadır]
$text
EOT;

        $systemPrompt = <<<SYS
You are a professional HTML translator.
Translate only visible human-readable text from {$sourceLang} to {$targetLang}.
Do not alter HTML tags, attributes, CSS, or JavaScript.
Keep formatting and structure untouched.
SYS;

        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'temperature' => 0.2,
            'max_tokens' => 4000,
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error || $httpStatus !== 200) {
            log_message('error', "OpenAI Error: HTTP $httpStatus - $error - $response");
            return null;
        }

        $responseData = json_decode($response, true);
        $translated = $responseData['choices'][0]['message']['content'] ?? null;

        if ($translated) {
            // Önce "[Çevrilecek içerik...]" varsa temizle
            $translated = preg_replace('/^\[.*?\]\s*/s', '', $translated);

            // ```html veya ``` gibi blok işaretleri varsa temizle
            $translated = preg_replace('/^```html\s*/i', '', $translated);
            $translated = preg_replace('/```$/', '', $translated);
        }

        if (substr_count($text, '<') !== substr_count($translated, '<')) {
            log_message('error', 'Tag mismatch after translation! Tag sayısı uyuşmuyor.');
        }

        return $translated;
    }
}
if (!function_exists('splitHtmlContent')) {
    function splitHtmlContent(string $html, int $limit = 2500): array
    {
        $chunks = [];
        $buffer = '';
        $length = 0;

        foreach (explode("\n", $html) as $line) {
            $lineLen = mb_strlen($line);
            if ($length + $lineLen > $limit) {
                $chunks[] = $buffer;
                $buffer = $line . "\n";
                $length = $lineLen;
            } else {
                $buffer .= $line . "\n";
                $length += $lineLen;
            }
        }

        if (!empty(trim($buffer))) {
            $chunks[] = $buffer;
        }

        return $chunks;
    }
}
if (!function_exists('translateLongHtmlWithGPT4o2')) {
    function translateLongHtmlWithGPT4o2(string $html, string $targetLang): string
    {
        ini_set('max_execution_time', 240);
        $chunks = splitHtmlContent($html, 2500);
        $translatedChunks = [];

        foreach ($chunks as $i => $chunk) {
            log_message('debug', "Translating chunk #{$i}, length: " . mb_strlen($chunk));
            $result = translateWithGPT4o($chunk, $targetLang);

            if ($result) {
                $translatedChunks[] = $result;
                log_message('debug', "Chunk #{$i} translated successfully, length: " . mb_strlen($result));
            } else {
                log_message('error', "Chunk #{$i} translation failed");
                $translatedChunks[] = $chunk; // fallback
            }

            usleep(1000000); // 1 saniye bekleme
        }

        return implode("\n", $translatedChunks);
    }
}
if (!function_exists('translateLongHtmlWithGPT4o2')) {
    function translateLongHtmlWithGPT4o2(string $html, string $targetLang): string
    {
        ini_set('max_execution_time', 240);

        $sourceLang = getDefaultLanguage(); // ✅ her zaman varsayılan dil
        $chunks = splitHtmlContent($html, 2500);
        $translatedChunks = [];

        foreach ($chunks as $i => $chunk) {
            log_message('debug', "Translating chunk #{$i}, length: " . mb_strlen($chunk));

            $result = translateWithGPT4o($chunk, $sourceLang, $targetLang); // ✅ doğru parametre

            if ($result) {
                $translatedChunks[] = $result;
                log_message('debug', "Chunk #{$i} translated successfully, length: " . mb_strlen($result));
            } else {
                log_message('error', "Chunk #{$i} translation failed");
                $translatedChunks[] = $chunk; // fallback
            }

            usleep(300000); // 0.3 sn bekleme
        }

        return implode("\n", $translatedChunks);
    }
}
function translateLongHtmlWithGPT4o(string $html, string $targetLang): string
{
    ini_set('max_execution_time', 300);

    // 1) Koru
    [$safe, $map] = protectHtmlBlocks($html);

    // 2) Güvenli split
    $chunks = splitHtmlSafely($safe, 3500);

    $out = [];
    foreach ($chunks as $i => $chunk) {
        log_message('debug', "Translating chunk #$i, len: " . mb_strlen($chunk));
        $res = translateWithGPT4o($chunk, $targetLang);
        $out[] = $res ?: $chunk;
        usleep(250000); // 0.25 sn
    }
    $translated = implode('', $out);

    // 3) Geri yükle
    $translated = restoreHtmlBlocks($translated, $map);

    return $translated;
}
if (!function_exists('generateSeoDataFromHtml')) {
    function generateSeoDataFromHtml(string $htmlContent, string $targetLang = 'en'): array
    {
        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'SEO Helper: API key missing');
            return ['seoDesc' => '', 'seoKeywords' => ''];
        }

        $textOnly = strip_tags($htmlContent);
        if (empty(trim($textOnly))) {
            log_message('error', 'SEO Helper: Cleaned content is empty.');
            return ['seoDesc' => '', 'seoKeywords' => ''];
        }

        $url = 'https://api.openai.com/v1/chat/completions';

        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are an SEO expert. Based on the provided text, generate a short SEO meta description (max 160 characters) and 5-8 relevant keywords in {$targetLang}. Respond in JSON format with keys 'seoDesc' and 'seoKeywords'."
                ],
                [
                    'role' => 'user',
                    'content' => $textOnly
                ]
            ],
            'temperature' => 0.2,
            'max_tokens' => 500,
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        log_message('debug', 'SEO Response: ' . json_encode(compact('httpStatus', 'error', 'response')));

        if ($error || $httpStatus !== 200) {
            log_message('error', "OpenAI SEO Generation Error: HTTP $httpStatus - $error - $response");
            return ['seoDesc' => '', 'seoKeywords' => ''];
        }

        $responseData = json_decode($response, true);
        $content = trim($responseData['choices'][0]['message']['content'] ?? '');
        $content = preg_replace('/^```json|```$/m', '', $content);

        $seoData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($seoData['seoDesc'], $seoData['seoKeywords'])) {
            log_message('error', 'Invalid SEO JSON Response: ' . $content);
            return ['seoDesc' => '', 'seoKeywords' => ''];
        }

        return $seoData;
    }
}

if (!function_exists('translateText')) {
    function translateText($text, $lang)
    {
        $apiKey = getenv('OPENAI_API_KEY');
        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => "You are a professional translator. Translate everything into $lang."],
                ['role' => 'user', 'content' => $text],
            ],
            'temperature' => 0.3,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        return $responseData['choices'][0]['message']['content'] ?? $text;
    }
}
if (!function_exists('translateSliderText')) {
    function translateSliderText(string $text, string $targetLang, string $sourceLang = 'tr'): ?string
    {
        if (empty(trim($text))) return '';

        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'OpenAI API key missing.');
            return null;
        }

        $cacheKey = md5("slider_translate_{$sourceLang}_{$targetLang}_" . $text);
        $cached = cache($cacheKey);
        if ($cached) return $cached;

        $systemPrompt = "You are a translation assistant for a multilingual slider system. Translate from {$sourceLang} to {$targetLang} preserving context and clarity.";
        $userPrompt = <<<PROMPT
Translate this text from {$sourceLang} to {$targetLang}:

$text
PROMPT;

        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.3,
            'max_tokens' => 1000,
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        $translated = $result['choices'][0]['message']['content'] ?? null;

        if ($translated) {
            cache()->save($cacheKey, $translated, 604800); // 7 gün
        }

        return $translated ?? null;
    }
}
function getFolderFiles($path, $foldersOnly = false)
{
    $result = [];

    // Klasörü oku
    if (is_dir($path)) {
        $items = array_diff(scandir($path), ['.', '..', 'index.html']);

        foreach ($items as $item) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $item;

            if ($foldersOnly && is_dir($fullPath)) {
                $result[] = $item; // Sadece klasörler
            } elseif (!$foldersOnly && is_file($fullPath)) {
                $result[] = pathinfo($item, PATHINFO_FILENAME); // Dosya adını uzantısız al
            }
        }
    }

    return $result;
}
function getFolderNames($path)
{
    $folders = [];

    // Klasör kontrolü
    if (is_dir($path)) {
        $items = array_diff(scandir($path), ['.', '..']);

        foreach ($items as $item) {
            if (is_dir($path . DIRECTORY_SEPARATOR . $item)) {
                $folders[] = $item; // Sadece klasör isimlerini ekle
            }
        }
    } else {
        throw new Exception("Klasör bulunamadı: $path");
    }

    return $folders;
}

if (! function_exists('getDefaultLanguage')) {
    function getDefaultLanguage(): ?array
    {
        return model(LanguageModel::class)
            ->select('id,title,shorten,rank,isActive')
            ->where('isActive', 1)
            ->where('rank', 1)
            ->first(); // sadece tek bir kayıt döndürür
    }
}
if (! function_exists('getActiveLanguages')) {
    function getActiveLanguages(): array
    {
        return model(LanguageModel::class)
            ->select('id,title,shorten,rank,isActive')
            ->where('isActive', 1)
            ->orderBy('rank', 'ASC')
            ->findAll();
    }
}
if (! function_exists('getLanguageByRank')) {
    function getLanguageByRank(int $rank = 0): ?string
    {
        $row = model(LanguageModel::class)
            ->select('shorten')
            ->where('isActive', 1)
            ->where('rank', $rank)
            ->first();

        return $row['shorten'] ?? null;
    }
}
if (! function_exists('getDefaultSiteLanguage')) {
    // Site varsayılanı: rank=0 (ör: 'tr')
    function getDefaultSiteLanguage(): string
    {
        return getLanguageByRank(0) ?? 'tr';
    }
}

if (! function_exists('setSessionLanguage')) {
    function setSessionLanguage(string $code): void
    {
        $code = strtolower(trim($code));

        $row = model(LanguageModel::class)
            ->select('id, shorten')
            ->where('isActive', 1)
            ->where('shorten', $code)
            ->first();

        // Bulunamazsa varsayılana düş
        if (! $row) {
            $row = model(LanguageModel::class)
                ->select('id, shorten')
                ->where('isActive', 1)
                ->orderBy('rank','ASC')
                ->first();
        }

        if ($row) {
            session()->set([
                'data_lang' => $row['shorten'], // KOD
                'lang'      => $row['shorten'], // Eski kodu kullanan yerler için eşitle
                'lang_id'   => $row['id'],      // ID’ye bakan kodlar için eşitle
            ]);
            service('request')->setLocale($row['shorten']);
        }
    }
}
if (!function_exists('protectHtmlBlocks')) {
    function protectHtmlBlocks(string $html): array {
        $map = [];

        // 1) <script>...</script>
        $html = preg_replace_callback('#<script\b[^>]*>.*?</script>#is',
            function ($m) use (&$map) {
                $key = '__SCRIPT_BLOCK_' . count($map) . '__';
                $map[$key] = $m[0];
                return $key;
            }, $html);

        // 2) <style>...</style>
        $html = preg_replace_callback('#<style\b[^>]*>.*?</style>#is',
            function ($m) use (&$map) {
                $key = '__STYLE_BLOCK_' . count($map) . '__';
                $map[$key] = $m[0];
                return $key;
            }, $html);

        // 3) data-* attribute içerikleri (özellikle data-html)
        $html = preg_replace_callback('#\s(data-[a-z0-9\-]+)\s*=\s*"([^"]*)"#i',
            function ($m) use (&$map) {
                $attr = $m[1];
                $val  = $m[2];
                $key  = '__DATAATTR_' . count($map) . '__';
                $map[$key] = $attr . '="' . $val . '"';
                // attribute’ı placeholder’a çeviriyoruz:
                return ' ' . $key;
            }, $html);

        return [$html, $map];
    }
}
if (!function_exists('restoreHtmlBlocks')) {
    function restoreHtmlBlocks(string $html, array $map): string {
        // data-attr’lar tam attribute olduğu için önce onları, sonra script/style’ı geri yazabiliriz
        foreach ($map as $ph => $original) {
            $html = str_replace($ph, $original, $html);
        }
        return $html;
    }
}
if (!function_exists('splitHtmlSafely')) {
    function splitHtmlSafely(string $html, int $limit = 4000): array {
        // kaba ama güvenli yaklaşım: kapanış div’lerine göre parça
        $parts = preg_split('#(</div>)#i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $chunks = [];
        $buf = '';

        foreach ($parts as $p) {
            // $p bazen kapanış, bazen içerik; hepsini birleştirerek ilerle
            if (mb_strlen($buf . $p) > $limit && $buf !== '') {
                $chunks[] = $buf;
                $buf = '';
            }
            $buf .= $p;
        }
        if (trim($buf) !== '') $chunks[] = $buf;

        // Aşırı ufaksa tek parça bırak
        return $chunks ?: [$html];
    }
}

if (!function_exists('getActiveLanguages')) {
    /**
     * Aktif dilleri döndürür; shorten değerleri önemlidir.
     */
    function getActiveLanguages(): array
    {
        $model = model(\App\Models\LanguageModel::class);
        $rows = $model->where('isActive', 1)->orderBy('rank', 'ASC')->findAll();
        return array_map(static function ($r) {
            return is_array($r) ? $r : (array)$r;
        }, $rows ?? []);
    }
}
if (!function_exists('getSessionLanguageCode')) {
    /**
     * Session'daki hedef dil (data_lang). Boşsa default kısa kodu döndürür.
     */
    function getSessionLanguageCode(): string
    {
        $code = (string)(session('data_lang') ?? '');
        if ($code === '') {
            $def = getDefaultLanguage();
            return $def['shorten'] ?? 'en';
        }
        return $code;
    }
}
if (!function_exists('translateWithGPT4o')) {
    /**
     * Geriye uyumlu imza:
     *   - 2 arg: translateWithGPT4o($text, $targetLang)
     *   - 3 arg: translateWithGPT4o($text, $sourceLang, $targetLang)  // eski çağrıları kırmamak için
     *
     * NOT: Bu projede KAYNAK daima default dil olmalı. 3 arg verilse de kaynak dil YOKSAYMAYIZ,
     *      ancak senin gereksinimine göre burada kaynak dil değerini her zaman default’a sabitliyoruz.
     */
    function translateWithGPT4o(string $text, string $a, ?string $b = null): ?string
    {
        // 1) Sabit: her zaman default dil kaynak
        $default = getDefaultLanguage();
        $sourceLang = $default['shorten'] ?? 'en';

        // 2) Hedef dil: 2 veya 3 parametreye göre belirle
        //    - 3 parametre gelmişse (eski çağrı şekli), $b hedeftir ama biz session data_lang'ı zorunlu kılıyoruz.
        //    - 2 parametre gelmişse $a hedeftir; yine de session’a zorlarız.
        $sessionTarget = getSessionLanguageCode();

        // Session’daki hedef dil default ile aynıysa çeviri yapmaya gerek yok
        if ($sessionTarget === $sourceLang) {
            return $text;
        }

        $targetLang = $sessionTarget;

        // Boş/aynı metin kontrolü
        $trimmed = trim($text);
        if ($trimmed === '' || mb_strtolower($sourceLang) === mb_strtolower($targetLang)) {
            return $text;
        }

        // --- OpenAI HTTP çağrısı (curl) ---
        $apiKey = (string)env('openai.api_key'); // .env: openai.api_key=...
        if ($apiKey === '') {
            log_message('error', 'OpenAI API anahtarı tanımlı değil (openai.api_key).');
            return null;
        }

        // System prompt – yalın metin için (HTML değil)
        $systemPrompt = "You are a professional translator. " .
            "Translate strictly from {$sourceLang} to {$targetLang}. " .
            "Return only the translated text, no extra commentary.";

        $payload = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $text],
            ],
            'temperature' => 0.2,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErr || $httpStatus !== 200) {
            log_message('error', "OpenAI error: HTTP {$httpStatus} {$curlErr} {$response}");
            return null;
        }

        $data = json_decode($response, true);
        $out = $data['choices'][0]['message']['content'] ?? null;

        return is_string($out) ? trim($out) : null;
    }
}
if (!function_exists('translateLongHtmlWithGPT4o')) {
    /**
     * HTML içeriği bozmadan, sadece görünen metni çevir.
     * (Basit sürüm: tek parça gönderiyoruz; gerekiyorsa senin içeriğin çok büyükse chunk’a bölebiliriz.)
     *
     * İmza geriye uyumlu:
     *   - 2 arg: translateLongHtmlWithGPT4o($html, $targetLang)
     *   - 3 arg: translateLongHtmlWithGPT4o($html, $sourceLang, $targetLang) // ama biz kaynak=default’a sabitliyoruz
     */
    function translateLongHtmlWithGPT4o(string $html, string $a, ?string $b = null): ?string
    {
        $default = getDefaultLanguage();
        $sourceLang = $default['shorten'] ?? 'en';
        $targetLang = getSessionLanguageCode();

        if ($targetLang === $sourceLang || trim($html) === '') {
            return $html;
        }

        $apiKey = (string)env('openai.api_key');
        if ($apiKey === '') {
            log_message('error', 'OpenAI API anahtarı tanımlı değil (openai.api_key).');
            return null;
        }

        $systemPrompt = <<<PROMPT
You are a professional HTML translator.
Translate ONLY visible, human-readable text from {$sourceLang} to {$targetLang}.
Do NOT alter or reformat HTML tags, attributes, inline CSS or JavaScript.
Return valid HTML with identical structure.
PROMPT;

        $payload = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $html],
            ],
            'temperature' => 0.2,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErr || $httpStatus !== 200) {
            log_message('error', "OpenAI error (HTML): HTTP {$httpStatus} {$curlErr} {$response}");
            return null;
        }

        $data = json_decode($response, true);
        $out = $data['choices'][0]['message']['content'] ?? null;

        if (!is_string($out)) {
            return null;
        }

        // Olası ```html ... ``` sarmallarını temizle
        $out = preg_replace('/^```html\s*/i', '', $out);
        $out = preg_replace('/```$/', '', $out);
        return trim($out);
    }
}