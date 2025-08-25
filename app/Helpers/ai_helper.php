<?php
// app/Helpers/ai_helper.php

use CodeIgniter\HTTP\Exceptions\HTTPException;

if (! function_exists('openai_chat')) {
    /**
     * Basit OpenAI Chat (GPT-4o) istemcisi (curl).
     * @param array $messages  [["role"=>"system","content"=>"..."],["role"=>"user","content"=>"..."]]
     * @param array $opts      ["model"=>"gpt-4o-mini","response_format"=>["type"=>"json_object"], "max_tokens"=>1000]
     * @return array           ["ok"=>bool, "content"=>string, "raw"=>array|null, "error"=>string|null]
     */
    function openai_chat(array $messages, array $opts = []): array
    {
        $apiKey = getenv('OPENAI_API_KEY') ?: env('OPENAI_API_KEY');
        if (! $apiKey) {
            return ['ok' => false, 'content' => '', 'raw' => null, 'error' => 'OPENAI_API_KEY missing'];
        }

        $payload = [
            'model'    => $opts['model'] ?? 'gpt-4o-mini',
            'messages' => $messages,
        ];
        if (!empty($opts['response_format'])) {
            $payload['response_format'] = $opts['response_format'];
        }
        if (!empty($opts['max_tokens'])) {
            $payload['max_tokens'] = (int)$opts['max_tokens'];
        }
        if (!empty($opts['temperature'])) {
            $payload['temperature'] = (float)$opts['temperature'];
        }

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT => 90,
        ]);
        $result = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            return ['ok' => false, 'content' => '', 'raw' => null, 'error' => "Curl error: $errstr"];
        }

        $json = json_decode($result, true);
        if (!isset($json['choices'][0]['message']['content'])) {
            return ['ok' => false, 'content' => '', 'raw' => $json, 'error' => 'Invalid OpenAI response'];
        }

        return ['ok' => true, 'content' => $json['choices'][0]['message']['content'], 'raw' => $json, 'error' => null];
    }
}

if (! function_exists('strip_editor_js')) {
    /**
     * ContentBox/Builder gibi editörlerden kalan script/style gibi blokları temizler.
     * - "Required js for editing" vb. imzalı script parçalarını siler.
     */
    function strip_editor_js(string $html): string
    {
        // 1) <script> ... </script> tamamen kaldır
        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);
        // 2) <style> ... </style> kaldır (gerekirse)
        $html = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html);
        // 3) ContentBox/Innovastudio imzalı bloklar
        $html = preg_replace('#<!--\s*Required js for editing.*?-->#is', '', $html);
        // 4) data-edit*, contentbox*, cbox* özel işaretleri (çok agresif olmayalım)
        $html = preg_replace('/\sdata-(edit|cbox|cb)-[a-z0-9\-]+="[^"]*"/i', '', $html);
        return trim($html);
    }
}

if (! function_exists('gpt_generate_seo')) {
    /**
     * İçerikten diline göre SEO desc ve keywords üretir. JSON döndürmeye zorlar.
     * @return array|null ['desc'=>string, 'keywords'=>string[]]
     */
    function gpt_generate_seo(string $content, string $langCode = 'tr'): ?array
    {
        $content = strip_tags($content);
        $content = preg_replace('/\s+/', ' ', $content);
        $content = mb_substr($content, 0, 8000); // güvenli limit

        $sys = "You are an SEO expert. Produce a concise meta description (<=160 chars) and 8-15 SEO keywords.";
        $usr = "LANG={$langCode}\nCONTENT:\n{$content}\n\nReturn STRICT JSON with keys: desc (string), keywords (string[]).";

        $res = openai_chat([
            ['role'=>'system','content'=>$sys],
            ['role'=>'user','content'=>$usr],
        ], [
            'model' => 'gpt-4o-mini',
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 400,
            'temperature' => 0.4,
        ]);

        if (! $res['ok']) {
            log_message('error', 'OpenAI SEO error: ' . $res['error']);
            return null;
        }

        $json = json_decode($res['content'], true);
        if (is_array($json) && isset($json['desc'], $json['keywords']) && is_array($json['keywords'])) {
            // Temizlik
            $json['desc'] = trim((string)$json['desc']);
            $json['keywords'] = array_values(array_filter(array_map('trim', $json['keywords'])));
            return $json;
        }

        // Fallback: gevşek ayrıştırma
        $contentRaw = $res['content'];
        preg_match('/DESC[:：]?(.*?)KEYWORDS[:：]?/is', $contentRaw, $m1);
        preg_match('/KEYWORDS[:：]?(.*)/is', $contentRaw, $m2);
        $desc = trim($m1[1] ?? '');
        $kwRaw = trim($m2[1] ?? '');
        $keywords = array_values(array_filter(array_map('trim', preg_split('/[,;|]+/u', $kwRaw))));
        if ($desc && $keywords) {
            return ['desc'=>$desc, 'keywords'=>$keywords];
        }

        log_message('error', '❌ gpt_generate_seo parse failed. Raw: ' . $res['content']);
        return null;
    }
}

if (! function_exists('gpt_translate_text')) {
    /**
     * Kısa düz metin çevirisi (title, breadcrumbTitle, seoDesc gibi).
     */
    function gpt_translate_text(string $text, string $sourceLang, string $targetLang): string
    {
        $prompt = "Translate the following text from {$sourceLang} to {$targetLang}. Keep meaning, tone, and proper casing. Only return the translated text.\n\nTEXT:\n".$text;
        $res = openai_chat([
            ['role'=>'system','content'=>'You are a professional translator.'],
            ['role'=>'user','content'=>$prompt],
        ], [
            'model' => 'gpt-4o-mini',
            'max_tokens' => 1000,
            'temperature' => 0.2,
        ]);
        if (! $res['ok']) {
            log_message('error', 'OpenAI translate_text error: ' . $res['error']);
            return $text; // orijinali koru
        }
        return trim($res['content']);
    }
}

if (! function_exists('gpt_translate_html')) {
    /**
     * HTML çevirisi: script/style/inline js dokunma; yalnız görünür metin çevir.
     * ContentBox/CB artıkları gitsin, HTML yapısı kalsın.
     */
    function gpt_translate_html(string $html, string $sourceLang, string $targetLang): string
    {
        $clean = strip_editor_js($html);
        $clean = mb_substr($clean, 0, 16000); // güvenli limit

        $sys = 'You are a professional HTML-aware translator.';
        $usr = <<<TXT
Translate visible text from {$sourceLang} to {$targetLang} while preserving HTML structure and attributes.
Do NOT translate URLs, file names, class names, ids, aria attributes.
Remove no tags. Do NOT include <script> or <style> blocks; if present ignore them.
Return HTML only.
HTML:
{$clean}
TXT;

        $res = openai_chat([
            ['role' => 'system', 'content' => $sys],
            ['role' => 'user', 'content' => $usr],
        ], [
            'model' => 'gpt-4o-mini',
            'max_tokens' => 3000,
            'temperature' => 0.2,
        ]);

        if (! $res['ok']) {
            log_message('error', 'OpenAI translate_html error: ' . $res['error']);
            return $html; // orijinali koru
        }
        $out = trim($res['content']);
        return $out !== '' ? $out : $html;
    }
}