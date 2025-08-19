<?= $this->extend('Views/public/base') ?>
<?= $this->section('head') ?>
    <meta name="theme-color" content="#<?= setThemeFirstColor() ?>"> <!-- Mobil tarayıcıda üst çubuğun rengini belirler -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= setCompanyName() ?>">
    <meta name="description" content="Bu sayfanın kısa ve etkileyici bir açıklaması. (Maksimum 160 karakter)">
    <meta name="keywords" content="anahtar kelime1, anahtar kelime2, anahtar kelime3">
    <meta property="og:title" content="Sayfanın Başlığı">
    <meta property="og:description" content="Sosyal medyada paylaşılırken görünen açıklama. (Maksimum 200 karakter)">
    <meta property="og:image" content="https://www.ornek.com/image.jpg"> <!-- Önerilen boyut: 1200x630 px -->
    <meta property="og:url" content="https://www.ornek.com"> <!-- Sayfanın URL’si -->
    <meta name="twitter:card" content="summary_large_image"> <!-- Önerilen kart türü -->
    <meta name="twitter:title" content="Sayfanın Başlığı">
    <meta name="twitter:description" content="Twitter'da paylaşıldığında görünen açıklama">
    <meta name="twitter:image" content="https://www.ornek.com/image.jpg">
    <meta name="twitter:site" content="@kullaniciadi"> <!-- Resmi Twitter hesabı -->
    <link rel="amphtml" href="https://www.ornek.com/amp-sayfa.html">
    <link rel="alternate" type="application/rss+xml" title="RSS Akışı" href="https://www.ornek.com/rss.xml">
    <link rel="canonical" href="https://www.ornek.com"> <!-- Kopya içerik sorunlarını önler -->
    <title><?php echo   setCompanyName() .' / '.$items->title ?></title>
<?= $this->endSection() ?>
<?= $this->section('style') ?>
	<link rel="stylesheet" href="<?= publicTheme() ?>css/dp.css" type="text/css" media="all">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<?php if (isset($items) && $items->rank > 1 && isset($items->breadcrumbStatus) && $items->breadcrumbStatus == 1): ?>
    <?php if (isset($themeSettings) && isset($themeSettings->setBreadcrumb)): ?>
        <?php
        $breadcrumbPath = "template/breadCrumb/" . $themeSettings->setBreadcrumb;
        echo $this->include($breadcrumbPath);
        ?>
    <?php endif; ?>
<?php endif; ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="kl-slideshow static-content__slideshow sc--showroom-carousel uh_neutral_color">
    <div class="bgback"></div>
    <div class="kl-slideshow-inner static-content__wrapper static-content--fullscreen">
        <div class="static-content__source">
            <div class="kl-bg-source">
                <div class="kl-bg-source__bgimage" style="background-image: url(<?= base_url()."uploads/references/".getReferenceCoverImage($items->id) ?>); background-repeat: no-repeat; background-attachment: scroll; background-position-x: center; background-position-y: center; background-size: cover;">
                </div>
            </div>
            <div class="th-sparkles"></div>
        </div>
        <div class="static-content__inner container" style="top: 65% !important;left: -10% !important;">
            <div class="kl-slideshow-safepadding sc__container ">
                <div class="static-content sc--showroomcrs-style">
                    <div class="sc__textcontent">
                        <h2 class="montserrat-900" style="font-size: 80px;  text-shadow: 2px 2px 5px black;"><?= $items->title ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <a class="tonext-btn js-tonext-btn" href="#" data-endof=".kl-slideshow">
            <span class="mouse-anim-icon"></span>
        </a>
    </div>
</div>
<br>
<?php if (!empty($items->description)){ ?>
<section class="hg_section" style="padding-bottom: 1px !important;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 calibri-text text-center" style="font-size: 1.41176rem;">
                <?php echo $items->description; ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<section class="hg_section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="gridPhotoGallery-container">
                    <div class="gridPhotoGallery mfp-gallery misc gridPhotoGallery--ratio-square gridPhotoGallery--cols-<?php echo $items->picturePrice; ?>" data-cols="<?php echo $items->picturePrice; ?>">
                        <div class="gridPhotoGallery__item gridPhotoGallery__item--sizer">
                        </div>
                        <?php
                        $images = getReferenceImages($items->id);
                        foreach ($images as $img) { ?>
                        <div class="gridPhotoGallery__item gridPhotoGalleryItem--w1 text-center">
                            <a title="" class="gridPhotoGalleryItem--h1 gridPhotoGallery__link" data-lightbox="mfp" data-mfp="image" href="<?php echo base_url("uploads/references/".$img->img_url); ?>">
                                <div class="gridPhotoGallery__img" style="background-image: url(<?php echo base_url("uploads/references/".$img->img_url); ?>);">
                                </div>
                                <i class="kl-icon fas fa-search circled-icon ci-large"></i>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
    <script type="text/javascript" src="<?= publicTheme() ?>js/plugins/jquery.isotope.min.js"></script>
    <script type="text/javascript" src="<?= publicTheme() ?>js/trigger/kl-portfolio-sortable.js"></script>
	<script type="text/javascript" src="<?= publicTheme() ?>js/dp.js"></script>
<?= $this->endSection() ?>