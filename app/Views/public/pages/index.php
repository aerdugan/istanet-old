<?= $this->extend('Views/public/base') ?>
<?= $this->section('head') ?>
    <meta name="theme-color" content="#<?= setThemeFirstColor() ?>"> <!-- Mobil tarayıcıda üst çubuğun rengini belirler -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= setCompanyName() ?>">
    <meta name="description" content="Bu sayfanın kısa ve etkileyici bir açıklaması. (Maksimum 160 karakter)">
    <meta name="keywords" content="anahtar kelime1, anahtar kelime2, anahtar kelime3">
    <meta property="og:title" content="<?php echo $items->title ?>">
    <meta property="og:description" content="Sosyal medyada paylaşılırken görünen açıklama. (Maksimum 200 karakter)">
    <meta property="og:image" content="https://www.ornek.com/image.jpg"> <!-- Önerilen boyut: 1200x630 px -->
    <meta property="og:url" content="<?php echo base_url($items->url) ?>"> <!-- Sayfanın URL’si -->
    <meta name="twitter:card" content="summary_large_image"> <!-- Önerilen kart türü -->
    <meta name="twitter:title" content="<?php echo $items->title ?>">
    <meta name="twitter:description" content="Twitter'da paylaşıldığında görünen açıklama">
    <meta name="twitter:image" content="https://www.ornek.com/image.jpg">
    <link rel="amphtml" href="<?php echo base_url($items->url) ?>">
    <link rel="alternate" type="application/rss+xml" title="RSS Akışı" href="https://www.ornek.com/rss.xml">
    <link rel="canonical" href="<?php echo base_url() ?>"> <!-- Kopya içerik sorunlarını önler -->
    <title><?php echo   setCompanyName() .' / '.$items->title ?></title>
<?= $this->endSection() ?>
<?= $this->section('style') ?>
<?php if (isset($items) && $items->isWebEditor == 1) { ?>
    <link href="<?php setContentBox(); ?>assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php setContentBox(); ?>contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
<?php } elseif (isset($items) && $items->isWebEditor == 0) { ?>
    <style>.is-container > div {flex-wrap: nowrap;}</style>
    <link href="<?php setContentBox(); ?>assets/minimalist-blocks/content-cBox.css" rel="stylesheet">
    <link href="<?php setContentBox(); ?>box/box-flex.css" rel="stylesheet">
    <link href="<?php setContentBox(); ?>assets/scripts/glide/css/glide.core.css" rel="stylesheet">
    <link href="<?php setContentBox(); ?>assets/scripts/" rel="stylesheet">
    <link href="<?php setContentBox(); ?>assets/scripts/navbar/navbar.css" rel="stylesheet">
    <?php  if(!empty($items->cBoxMainCss)) { echo $items->cBoxMainCss; }
    if(!empty($items->cBoxSectionCss)) { echo $items->cBoxSectionCss; } ?>

<?php } ?>
<?php
if ($items->rank == 1) {
    $footerPath = '/template/slider/' . $themeSettings->setSlider . '/styles.php';
    if (is_file($footerPath)) {
        include $footerPath;
    }
}
?>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<?php if (isset($items) && $items->rank > 1 && isset($items->breadcrumbStatus) && $items->breadcrumbStatus == 1): ?>
    <?php if (isset($themeSettings) && isset($themeSettings->setBreadcrumb)): ?>
        <?php
        $breadcrumbPath = "/template/breadCrumb/" . $themeSettings->setBreadcrumb;
        echo $this->include($breadcrumbPath);
        ?>
    <?php endif; ?>
<?php endif; ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php
if ($items->rank == 1) {
    $footerPath = '/template/slider/' . $themeSettings->setSlider . '/index.php';
    if (is_file($footerPath)) {
        include $footerPath;
    }
}
?>

<?php if (isset($items) && $items->isWebEditor == 1) { ?>
    <div id="container-builder" class="container-builder" <?php echo ($items->rank == 1) ? '' : 'style="padding-top:50px"'; ?>>
        <?php echo $items->inpHtml; ?>
    </div>
<?php } elseif (isset($items) && $items->isWebEditor == 0) { ?>
    <div id="isContentBox" class="is-wrapper" style="opacity: 1 !important;">
        <?php echo $items->cBoxContent; ?>
    </div>
<?php } ?>

<?php if (isset($items) && $items->isWebEditor == 1) { ?>
    <div id="container-builder" class="container-builder" <?php echo ($items->rank == 1) ? '' : 'style="padding-top:50px"'; ?>>
        <?php echo $items->inpHtml; ?>
    </div>
<?php } elseif (isset($items) && $items->isWebEditor == 0) { ?>
    <div id="isContentBox" class="is-wrapper" style="opacity: 1 !important;">
        <?php echo $items->cBoxContent; ?>
    </div>
<?php } ?>


<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<?php if (isset($items) && $items->isWebEditor == 0) { ?>
    <script src="<?php setContentBox(); ?>assets/scripts/glide/glide.js"></script>
    <script src="<?php setContentBox(); ?>assets/scripts/navbar/navbar.min.js"></script>
    <script src="<?php setContentBox(); ?>box/box-flex.min.js"></script> <!-- Box Framework js include -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"></script>
    <script>
        SmoothScroll({
            frameRate: 150,
            animationTime: 800,
            stepSize: 120,
            pulseAlgorithm: 1,
            pulseScale: 4,
            pulseNormalize: 1,
            accelerationDelta: 300,
            accelerationMax: 2,
            keyboardSupport: 1,
            arrowScroll: 50,
            fixedBackground: 0
        });
    </script>
<?php } ?>
<?php
if ($items->rank == 1) {
    $footerPath = '/template/slider/' . $themeSettings->setSlider . '/script.php';
    if (is_file($footerPath)) {
        include $footerPath;
    }
}
?>
<?= $this->endSection() ?>