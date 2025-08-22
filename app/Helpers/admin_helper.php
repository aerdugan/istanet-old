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

if (!function_exists('adminTheme')) {
    function adminTheme(){
        echo "https://cdn.istanet.com/admin/metronic_39_831/";
    }
}

function seoFriendly($string)
{
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = preg_replace('/[^A-Za-z0-9\s\-]/', '', $string);
    $string = preg_replace('/\s+/', ' ', $string);
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);
    return $string;
}

if (!function_exists('sweetAlert')) {
    function sweetAlert()
    {
        try {
            $session = session();
            $alert = $session->getFlashdata('sweet');

            if (is_array($alert) && count($alert) === 2) {
                // Basit bilgi veya uyarı bildirimi
                return "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: 'center',
                            icon: '".htmlspecialchars($alert[0], ENT_QUOTES)."',
                            title: '".htmlspecialchars($alert[1], ENT_QUOTES)."',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    });
                </script>";
            }

            if (is_array($alert) && count($alert) === 4) {
                // Onaylı silme işlemi gibi durumlar
                return "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '".htmlspecialchars($alert[1], ENT_QUOTES)."',
                            text: '".htmlspecialchars($alert[2], ENT_QUOTES)."',
                            icon: '".htmlspecialchars($alert[0], ENT_QUOTES)."',
                            showCancelButton: true,
                            confirmButtonColor: '#f34141',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Evet, sil!',
                            cancelButtonText: 'Vazgeç',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '".htmlspecialchars($alert[3], ENT_QUOTES)."';
                            }
                        });
                    });
                </script>";
            }

        } catch (Exception $ex) {
            // Hataları sessizce geç
        }

        return ''; // alert yoksa boş dön
    }
}
function sweetAlert2()
{
    if (session()->has('swal')) {
        $swal = session('swal');
        echo "<script>
            Swal.fire({
                icon: '{$swal['icon']}',
                title: '{$swal['title']}',
                text: '{$swal['text']}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>";
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
if (!function_exists('translateLongHtmlWithGPT4o')) {
    function translateLongHtmlWithGPT4o(string $html, string $targetLang): string
    {
        ini_set('max_execution_time', 240);

        $chunks = splitHtmlContent($html, 2500);
        $translatedChunks = [];

        foreach ($chunks as $i => $chunk) {
            log_message('debug', "Translating chunk #{$i}, length: " . mb_strlen($chunk));

            // ✅ sadece hedef dili gönderiyoruz
            $result = translateWithGPT4o($chunk, $targetLang);

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
if (!function_exists('generateSeoFromContent')) {
    function generateSeoFromContent(string $html, string $lang): ?array
    {
        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) return null;

        // Sistem mesajı: GPT'yi SEO asistanı gibi düşünmesini sağla
        $systemPrompt = "You are an SEO assistant. Based on user-visible HTML content, generate a JSON object with a short SEO description and relevant keywords in {$lang}.";

        // Kullanıcı mesajı: HTML içeriğe göre JSON üretmesini istiyoruz
        $userPrompt = <<<PROMPT
Below is a block of HTML content. Ignore all HTML tags and JavaScript.

Your job is to:
1. Extract the user-visible text.
2. Generate:
   - A short SEO page description (max 160 characters)
   - 6 to 10 relevant keywords

⚠️ IMPORTANT: Return ONLY a valid JSON object. No markdown, no explanations.

Format:
{
  "desc": "...",
  "keywords": ["...", "..."]
}

HTML Content:
$html
PROMPT;

        $postData = [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.4,
            'max_tokens' => 600,
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
        $content = $result['choices'][0]['message']['content'] ?? '';

        // Log orijinal GPT yanıtı
        log_message('debug', "💬 GPT ORİJİNAL YANIT:\n" . $content);

        // ✅ Markdown blokları varsa temizle
        $content = trim($content);
        if (str_starts_with($content, '```json')) {
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/```$/', '', $content);
        }

        // ✅ JSON formatını ayrıştır
        $json = json_decode($content, true);
        if (is_array($json) && isset($json['desc'], $json['keywords'])) {
            return $json;
        }

        // ❌ JSON olarak parse edilemezse hata yaz
        log_message('error', "❌ GPT yanıtı parse edilemedi:\n" . $content);
        return null;
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

if (! function_exists('getDefaultAdminLanguage')) {
    // Admin varsayılanı: rank=1
    function getDefaultAdminLanguage(): string
    {
        return getLanguageByRank(1) ?? (getLanguageByRank(0) ?? 'tr');
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


if (! function_exists('getSessionLanguageTitle')) {
    /**
     * Session'daki data_lang (shorten) için languages.title döndürür.
     * Yoksa uygun fallback döner.
     *
     * @param string|null $default Fallback başlık (varsayılan "Türkçe")
     */
    function getSessionLanguageTitle(?string $default = 'Türkçe'): string
    {
        static $cache = []; // ['tr' => 'Türkçe', 'en' => 'İngilizce', ...]

        $code = getSessionLanguageCode();
        if ($code === null) {
            return $default ?? 'Türkçe';
        }

        if (isset($cache[$code])) {
            return $cache[$code];
        }

        // DB'den çek
        $m = model(LanguageModel::class);

        // Önce exact match (aktif/pasif fark etmeksizin)
        $row = $m->select('title')->where('shorten', $code)->first();
        if (! $row) {
            // Fallback: aktif en düşük ranklı dil
            $row = $m->select('title')->where('isActive', 1)->orderBy('rank', 'ASC')->first();
        }

        $title = $row['title'] ?? ($default ?? 'Türkçe');
        // Basit cache
        $cache[$code] = $title;

        return $title;
    }
}

if (! function_exists('getSessionLanguageCode')) {
    /**
     * Session'daki dil kodunu döndürür (ör: 'tr').
     * Yoksa null döner.
     */
    function getSessionLanguageCode(): ?string
    {
        $code = session('data_lang');
        return is_string($code) && $code !== '' ? $code : null;
    }
}