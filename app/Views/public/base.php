<?php
helper('admin');

$themeSettings = getThemeSettings();
$settings      = session()->get('settings');

$activeLanguages   = getActiveLanguages();                    // [['shorten'=>'tr','title'=>...], ...]
$availableLangCodes = array_map(fn($l) => strtolower($l['shorten']), $activeLanguages);

$currentLang = session('data_lang') ?: getDefaultSiteLanguage();
if (! in_array($currentLang, $availableLangCodes, true)) {
    $currentLang = getDefaultSiteLanguage();
    session()->set('data_lang', $currentLang);
    service('request')->setLocale($currentLang);
}

// Mevcut path'ten ilk segmenti (dil ise) ayıkla
$uri         = service('uri');
$firstSeg    = strtolower($uri->getSegment(1) ?? '');
$currentPath = ltrim($uri->getPath(), '/');                   // ör: "tr/about-us"
$pathAfterLang = $currentPath;

if ($firstSeg && in_array($firstSeg, $availableLangCodes, true)) {
    // "tr/..." -> "..."
    $pathAfterLang = ltrim(substr($currentPath, strlen($firstSeg)), '/'); // "about-us"
}

// hreflang URL üretici
$hreflangUrl = function (string $code) use ($pathAfterLang) {
    $suffix = $pathAfterLang !== '' ? '/' . $pathAfterLang : '';
    return base_url($code . $suffix);
};
?>
<!doctype html>
<html lang="<?= esc($currentLang) ?>" dir="ltr" class="no-js">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= esc(setting('site.logo.favicon')) ?>" sizes="32x32">
    <link rel="apple-touch-icon-precomposed" href="<?= esc(setting('site.logo.favicon')) ?>" sizes="72x72" />
    <link rel="apple-touch-icon-precomposed" href="<?= esc(setting('site.logo.favicon')) ?>" sizes="114x114" />
    <link rel="apple-touch-icon-precomposed" href="<?= esc(setting('site.logo.favicon')) ?>" sizes="144x144" />
    <link rel="apple-touch-icon-precomposed" href="<?= esc(setting('site.logo.favicon')) ?>" />

    <!-- hreflang (aktif dillerden dinamik) -->
    <?php foreach ($availableLangCodes as $code): ?>
        <link rel="alternate" hreflang="<?= esc($code) ?>" href="<?= esc($hreflangUrl($code)) ?>" />
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= esc($hreflangUrl(getDefaultSiteLanguage())) ?>" />

    <?= $this->renderSection('head') ?>

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400italic,400,600,600italic,700,800,800italic" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.istanet.com/public/Kallyas/css/bootstrap.css" media="all">
    <link rel="stylesheet" href="https://cdn.istanet.com/public/Kallyas/fonts/font-awesome/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="<?= site_url('template/public/') ?>template.min.css" media="all">
    <link rel="stylesheet" href="https://cdn.istanet.com/public/Kallyas/css/responsive.min.css" media="all">
    <link rel="stylesheet" href="https://cdn.istanet.com/public/Kallyas/css/base-sizing.min.css" media="all">

    <?= $this->renderSection('style') ?>

    <link rel="stylesheet" href="<?= site_url('template/public/') ?>custom.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Calibri&display=swap');
        body { font-family: Calibri, 'Segoe UI', sans-serif !important; }
    </style>

    <!-- JS (head) -->
    <script src="https://cdn.istanet.com/public/Kallyas/js/modernizr.min.js"></script>
    <script src="https://cdn.istanet.com/public/Kallyas/js/jquery.js"></script>
</head>
<body>
<div id="page_wrapper" class="sticky-header">

    <?php
    // HEADER include
    if (!empty($themeSettings?->setHeader)) {
        $headerPath = FCPATH . 'template/header/' . basename($themeSettings->setHeader) . '.php';
        if (is_file($headerPath)) {
            include $headerPath;
        }
    }
    ?>

    <?= $this->renderSection('breadcrumb') ?>
    <?= $this->renderSection('content') ?>

    <?php
    // FOOTER include
    if (!empty($themeSettings?->setFooter)) {
        $footerPath = FCPATH . 'template/footer/' . basename($themeSettings->setFooter) . '.php';
        if (is_file($footerPath)) {
            include $footerPath;
        }
    }
    ?>

</div>

<a href="#" id="totop">TOP</a>

<!-- JS (footer) -->
<script src="https://cdn.istanet.com/public/Kallyas/js/bootstrap.min.js"></script>
<script src="https://cdn.istanet.com/public/Kallyas/js/kl-plugins.js"></script>
<?= $this->renderSection('script') ?>
<script src="<?= site_url('template/public/') ?>kl-scripts.min.js"></script>
<script src="<?= site_url('template/public/') ?>kl-custom.js"></script>

</body>
</html>