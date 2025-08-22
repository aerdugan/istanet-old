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

<footer id="footer" data-footer-style="4">
    <div class="container">
        <div class="row">
            <div class="row w-100 d-flex justify-content-between text-center">
                <div class="col-sm-4">
                    <a href="https://themeforest.net/item/kallyas-gigantic-premium-template/3583938" target="_blank" class="footer-links">
                        <i class="glyphicon glyphicon-flag icon-size-xxs"></i>
                        <span>Envato Pty Ltd, Melbourne, Australia</span>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="https://themeforest.net/item/kallyas-gigantic-premium-template/3583938" target="_blank" class="footer-links">
                        <i class="glyphicon glyphicon-user icon-size-xxs"></i>
                        <span>hello@kallyas.com</span>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="https://themeforest.net/item/kallyas-gigantic-premium-template/3583938" target="_blank" class="footer-links">
                        <i class="glyphicon glyphicon-earphone icon-size-xxs"></i>
                        <span>+12 345 67890</span>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 d-flex justify-content-between">
                <div class="elm-socialicons m-auto">
                    <ul class="elm-social-icons sc--colored sh--rounded clearfix">
						<li class="title">GET SOCIAL</li>
                        <?php echo view('public/inc/socialSettings.php'); ?>
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
            <!--/ Left side Copyright -->

            <!-- Right side -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <!-- Footer navigation -->
                <ul class="topnav footer_nav d-flex justify-content-end">
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
                <!--/ Footer navigation -->
            </div>
            <!--/ Right side -->
        </div>
        <!--/ row -->
    </div>
    <!--/ container -->
</footer>
<!--/ Footer style 4 -->