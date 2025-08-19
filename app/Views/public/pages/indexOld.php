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
    if(!empty($items->cBoxSectionCss)) { echo $items->cBoxSectionCss; }  ?>
<?php } ?>
<?php
if ($items->rank == 1) {
    $footerPath = FCPATH . 'themes/slider/' . $themeSettings->setSlider . 'styles.php';
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
        $breadcrumbPath = "template/breadCrumb/" . $themeSettings->setBreadcrumb;
        echo $this->include($breadcrumbPath);
        ?>
    <?php endif; ?>
<?php endif; ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?php
if ($items->rank == 1) {
    $footerPath = FCPATH . 'template/slider/' . $themeSettings->setSlider . 'index.php';
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

<content-box>
<?php if (isset($items) && $items->isWebEditor == 1) { ?>
    <div id="container-builder" class="container-builder"></div>
<?php } elseif (isset($items) && $items->isWebEditor == 0) { ?>
    <div id="isContentBox" class="is-wrapper" style="opacity: 1 !important;"></div>
<?php } ?>
</content-box>


<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
    <script>
        class ContentBox extends HTMLElement {
            constructor() {
                super();
                this.attachShadow({ mode: 'open' });
            }

            connectedCallback() {
                const root = this.shadowRoot;

                // ---- Styles (yalnızca shadow içinde geçerli) ----
                const style1 = document.createElement('link');
                style1.rel = 'stylesheet';
                style1.href = '<?= addslashes((function(){ob_start(); setContentBox(); return ob_get_clean();})()); ?>assets/minimalist-blocks/content.css';

                const style2 = document.createElement('link');
                style2.rel = 'stylesheet';
                style2.href = '<?= addslashes((function(){ob_start(); setContentBox(); return ob_get_clean();})()); ?>contentbuilder/contentbuilder.css';

                // ---- Wrapper + Slot ----
                const wrap = document.createElement('div');
                wrap.id = 'content-box-root';

                const slot = document.createElement('slot');
                wrap.appendChild(slot);

                root.append(style1, style2, wrap);

                // ---- Scripts (sadece shadow içinde yüklenecek) ----
                const scriptUrls = [
                    '<?= addslashes((function(){ob_start(); setContentBox(); return ob_get_clean();})()); ?>assets/scripts/glide/glide.js',
                    '<?= addslashes((function(){ob_start(); setContentBox(); return ob_get_clean();})()); ?>assets/scripts/navbar/navbar.min.js',
                    '<?= addslashes((function(){ob_start(); setContentBox(); return ob_get_clean();})()); ?>box/box-flex.min.js'
                ];

                // Script yükleme yardımcıları
                const loadScript = (src) => new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = resolve;
                    s.onerror = reject;
                    // ÖNEMLİ: Script shadowRoot'a ekleniyor (globali etkilemesin)
                    root.appendChild(s);
                });

                const loadInline = (code) => {
                    const s = document.createElement('script');
                    s.textContent = code;
                    root.appendChild(s);
                };

                // SmoothScroll kütüphanesini TÜM sayfaya değil, sadece shadow içinde anchor'larda uygula
                loadInline(`
      (function(){
        const sr = document.currentScript.getRootNode();
        sr.addEventListener('click', function(e){
          const a = e.target.closest('a[href^="#"]');
          if(!a) return;
          const id = a.getAttribute('href').slice(1);
          const t = sr.getElementById(id) || sr.querySelector('#'+CSS.escape(id));
          if(!t) return;
          e.preventDefault();
          t.scrollIntoView({behavior:'smooth', block:'start'});
        });
      })();
    `);

                // Sırasıyla scriptleri yükle ve içeride init et
                (async () => {
                    try {
                        for (const u of scriptUrls) { await loadScript(u); }

                        // Glide: yalnızca shadow içindeki .glide elemanları
                        if (root.defaultView && root.defaultView.Glide) {
                            root.querySelectorAll('.glide').forEach(el => {
                                try { new root.defaultView.Glide(el, {}).mount(); } catch(e){}
                            });
                        } else if (window.Glide) {
                            // Bazı kütüphaneler window'a yazabilir; yine de sadece root içinde seçiyoruz
                            root.querySelectorAll('.glide').forEach(el => {
                                try { new window.Glide(el, {}).mount(); } catch(e){}
                            });
                        }

                        // Navbar / Box gibi kütüphaneler global bekliyorsa:
                        // Seçimleri sadece root içinde yapalım ki dışarı etkilenmesin.
                        // Örnek (uygunsa):
                        // root.querySelectorAll('.navbar').forEach(initNavbarSomething);

                    } catch (err) {
                        console.error('ContentBox script load error:', err);
                    }
                })();
            }
        }
        customElements.define('content-box', ContentBox);
    </script>

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
    $footerPath = FCPATH . 'template/slider/' . $themeSettings->setSlider . 'scripts.php';
    if (is_file($footerPath)) {
        include $footerPath;
    }
}
?>
<?= $this->endSection() ?>