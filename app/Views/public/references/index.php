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
<title><?= setCompanyName() . ' / Projects' ?></title>
<?= $this->endSection() ?>
<?= $this->section('style') ?>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
    <div class="image-containerX">
        <img src="/uploads/pages/projeler.jpg" alt="Arka Plan" class="responsive-imageX" />
        <div class="text-overlayX"></div>
        <div class="kl-bottommask kl-bottommask--mask3">
            <svg width="5000px" height="57px" class="svgmask " viewBox="0 0 5000 57" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                    <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-mask3">
                        <feOffset dx="0" dy="3" in="SourceAlpha" result="shadowOffsetInner1"></feOffset>
                        <feGaussianBlur stdDeviation="2" in="shadowOffsetInner1" result="shadowBlurInner1"></feGaussianBlur>
                        <feComposite in="shadowBlurInner1" in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" result="shadowInnerInner1"></feComposite>
                        <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.4 0" in="shadowInnerInner1" type="matrix" result="shadowMatrixInner1"></feColorMatrix>
                        <feMerge>
                            <feMergeNode in="SourceGraphic"></feMergeNode>
                            <feMergeNode in="shadowMatrixInner1"></feMergeNode>
                        </feMerge>
                    </filter>
                </defs>
                <path d="M9.09383679e-13,57.0005249 L9.09383679e-13,34.0075249 L2418,34.0075249 L2434,34.0075249 C2434,34.0075249 2441.89,33.2585249 2448,31.0245249 C2454.11,28.7905249 2479,11.0005249 2479,11.0005249 L2492,2.00052487 C2492,2.00052487 2495.121,-0.0374751261 2500,0.000524873861 C2505.267,-0.0294751261 2508,2.00052487 2508,2.00052487 L2521,11.0005249 C2521,11.0005249 2545.89,28.7905249 2552,31.0245249 C2558.11,33.2585249 2566,34.0075249 2566,34.0075249 L2582,34.0075249 L5000,34.0075249 L5000,57.0005249 L2500,57.0005249 L1148,57.0005249 L9.09383679e-13,57.0005249 Z" class="bmask-bgfill" filter="url(#filter-mask3)" fill="#fbfbfb"></path>
            </svg>
            <i class="fas fa-angle-down"></i>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <section class="hg_section pt-80 pb-80 pl-50 pr-50 pl-sm-15 pr-sm-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="hg-portfolio-sortable">
                        <ul id="portfolio-nav" class="fixclear">
                            <li class="current"><a href="#" data-filter="*"><?php echo getReferenceSlug()[session('lang')]['all'] ?></a></li>
                            <?php
                            $referenceCategories = getAllReferenceCategories();
                            if (isset($referenceCategories) && count($referenceCategories) > 0) {
                                foreach ($referenceCategories as $referenceCategory) {
                                    ?>
                                    <li class=""><a href="#" data-filter=".<?= $referenceCategory->url ?>_sort"><?= $referenceCategory->title ?></a></li>
                                    <?php
                                }
                            } ?>
                        </ul>
                        <div class="clear"></div>
                        <ul id="thumbs" class="fixclear" data-columns="3">
                            <?php
                            $references = getAllReferences();
                            if (isset($references) && count($references) > 0) {
                                foreach ($references as $reference) {
                                    ?>
                                    <li class="item kl-has-overlay miscellaneous_sort <?= getReferenceCategoryUrl($reference->category_id) ?>">
                                        <div class="inner-item">
                                            <div class="img-intro">
                                                <a href="<?= base_url(session('lang').'/'.getReferenceSlug()[session('lang')]['referenceDetail'].'/'.$reference->url) ?>"></a>
                                                <img src="<?= "/uploads/references/".getReferenceCoverImage($reference->id) ?>" alt="portfolio" title="portfolio" class="img-fluid imgbox_image cover-fit-img" style="aspect-ratio: 16 / 9; width: 100%; object-fit: cover;">
                                                <div class="overlay">
                                                    <div class="overlay-inner">
                                                        <span class="far fa-image"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="title">
                                                <a href="<?= base_url(session('lang').'/'.getReferenceSlug()[session('lang')]['referenceDetail'].'/'.$reference->url) ?>">
												<span class="name">
													<?= $reference->title ?>
												</span>
                                                </a>
                                            </h4>
                                            <div class="clear"></div>
                                        </div>
                                    </li>

                                    <?php
                                }
                            } ?>
                        </ul>
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

    <!-- Required js trigger for Portfolio sortable element -->
    <script type="text/javascript" src="<?= publicTheme() ?>js/trigger/kl-portfolio-sortable.js"></script>
<?= $this->endSection() ?>