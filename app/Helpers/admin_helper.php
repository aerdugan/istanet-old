<?php

if (!function_exists('getDefaultPages')) {
    /**
     * Varsayılan dildeki sayfaları döndürür.
     * @return array
     */
    function getDefaultPages(): array
    {
        $defaultLang = getDefaultLanguage(); // Örn: "en" veya "tr"
        if (!$defaultLang) {
            return [];
        }

        $db      = \Config\Database::connect();
        $builder = $db->table('pages');

        $query = $builder->where('data_lang', $defaultLang)
            ->orderBy('rank', 'ASC')
            ->get();

        return $query->getResult();
    }
}

if (!function_exists('translateWithGPT4o')) {
    function translateWithGPT4o(string $text, string $sourceLang, string $targetLang): ?string
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
