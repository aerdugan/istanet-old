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

<footer id="footer" data-footer-style="2">
    <div class="container">
        <div class="row">
            <div class="offset-lg-2 offset-md-2 col-sm-12 col-md-8 col-lg-8">
                <div class="kl-title-block clearfix text-center">
                    <h3 class="tbk__title montserrat fw-bold white">STAY TUNED!</h3>
                    <h4 class="tbk__subtitle white fw-thin">We’ll do our best to deliver valuable updates and lots of great resources without invading your mailbox.</h4>
                </div>
                <div class="newsletter-signup">
                    <form action="http://YOUR_USERNAME.DATASERVER.list-manage.com/subscribe/post-json?u=YOUR_API_KEY&amp;id=LIST_ID&c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
                        <input type="email" value="" name="EMAIL" class="nl-email form-control" id="mce-EMAIL" placeholder="your.address@email.com" required="">
                        <input type="submit" name="subscribe" class="nl-submit" id="mc-embedded-subscribe" value="JOIN US">
                        <div style="position: absolute; left: -5000px;">
                            <input type="text" name="b_xxxxxxxxxxxxxxxxxxxCUSTOMxxxxxxxxx" value="">
                        </div>
                    </form>
                </div>
                <div class="elm-socialicons text-center">
                    <ul class="elm-social-icons sc--colored sh--rounded clearfix">
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
        </div>
        <div class="row">
            <div class="col-sm-12 footer-area--bottom"></div>
            <div class="col-sm-12 col-md-6 col-lg-6 d-flex justify-content-start">
                <div class="d-flex mr-30">
                    <?php echo view('public/inc/footerLogoSettings.php'); ?>
                </div>
                <div class="d-flex">
                    <p>© 2018 All rights reserved. Buy <a href="#" target="_self" title="Kallyas">Kallyas Template</a>.</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <ul class="topnav footer_nav kl-font-alt d-flex justify-content-end">
                    <li class="menu-item">
                        <a href="#" target="_self" title="DISCLAIMER">DISCLAIMER</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" target="_self" title="SUPPORT POLICY">SUPPORT POLICY</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" target="_self" title="LEGAL">LEGAL</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>