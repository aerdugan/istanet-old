<?php
$s = service('settings');
$raw = $s->get('Site.socials');   // setting('Site.socials') da olur
$socials = [];

// normalize (array / php-serialize / json)
if (is_array($raw)) {
    $socials = $raw;
} elseif (is_string($raw) && $raw !== '') {
    $un = @unserialize($raw);
    if ($un !== false || $raw === 'b:0;') {
        $socials = $un;
    } else {
        $js = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($js)) {
            $socials = $js;
        }
    }
}
if (!is_array($socials)) $socials = [];
?>

<footer id="footer" data-footer-style="5">
    <div class="main-footer">
        <div class="container pt-80 mb-60">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3 mb-30">
                    <div class="mb-30">
                        <?php echo view('public/inc/footerLogoSettings.php'); ?>
                        <p>Over the years we developed a philosophy, to provide gorgeous items that are built only to be useful, focusing objectively on features that are multi-purpose that simply enrich our buyers imagination.</p>
                    </div>
                    <div class="clearfix">
                        <ul class="social-icons sc--clean clearfix">
                            <li class="title">GET SOCIAL</li>
                            <?php foreach ($socials as $row):
                                $platform = trim((string)($row['platform'] ?? ''));
                                $icon     = trim((string)($row['icon'] ?? '')); // örn: "fab fa-facebook-f"
                                $url      = trim((string)($row['url'] ?? '#'));
                                if ($url === '' && $platform === '') continue;
                                ?>
                                <li>
                                    <a href="<?= esc($url) ?>" target="_blank" title="<?= esc($platform) ?>">
                                        <i class="<?= esc($icon) ?>"></i>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 mb-30">
                    <div class="mr-20">
                        <h4 data-role="title" class="simple-title">RECENT POSTS</h4>
                        <ul class="menu">
                            <li><a href="#">How to build a website with Kallyas</a></li>
                            <li><a href="#">Top 30 Amazing Code Snippets</a></li>
                            <li><a href="#">10 Templates To Build e-Shop</a></li>
                            <li><a href="#">Hotel Template with Kallyas</a></li>
                            <li><a href="#">Membership Template</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-2 mb-30">
                    <div class="">
                        <h4 data-role="title" class="simple-title">ABOUT US</h4>
                        <ul class="menu">
                            <li><a href="#">Our Workshop</a></li>
                            <li><a href="#">The Basement</a></li>
                            <li><a href="#">Inhouse Production</a></li>
                            <li><a href="#">Future Plamns</a></li>
                            <li><a href="#">Amazign Discoveries</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 mb-30">
                    <div class="ml-40 ml-xs-0">
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="container pt-30 pb-10">
            <div class="row">
                <div class="col-sm-6">
                    <p class="fs-xsmall uppercase text-center-xs">
                        © 2018 All rights reserved. Buy <a href="http://themeforest.net/item/kallyas-responsive-multipurpose-template/3583938">Kallyas Template</a>.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ul class="menu fs-xsmall text-right fw-semibold mb-xs-20 text-center-xs">
                        <li><a href="index.html">HOME</a></li>
                        <li><a href="blog.html">BLOG</a></li>
                        <li><a href="sortable-portfolio.html">PORTFOLIO</a></li>
                        <li><a href="contact-us.html">CONTACT</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>