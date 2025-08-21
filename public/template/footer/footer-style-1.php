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

<footer id="footer" data-footer-style="1">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-5 mb-30">
                <h3 class="title m_title">
                    HOGASH STUDIO
                </h3>
                <div class="sbs">
                    <ul class="menu">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about-us.html">About us</a></li>
                        <li><a href="our-team.html">Our team</a></li>
                        <li><a href="faq.html">F.A.Q</a></li>
                        <li><a href="styles-typography.html">Template styles</a></li>
                        <li><a href="blog.html">Blog</a></li>
                        <li><a href="careers.html">Career</a></li>
                        <li><a href="process.html">Jobs</a></li>
                        <li><a href="contact-us.html">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 mb-30">
                <div class="newsletter-signup">
                    <h3 class="title m_title">NEWSLETTER SIGNUP</h3>
                    <p>By subscribing to our mailing list you will always be update with the latest news from us.</p>
                    <form action="http://YOUR_USERNAME.DATASERVER.list-manage.com/subscribe/post-json?u=YOUR_API_KEY&amp;id=LIST_ID&c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <input type="email" value="" name="EMAIL" class="nl-email form-control" id="mce-EMAIL" placeholder="your.address@email.com" required>
                        <input type="submit" name="subscribe" class="nl-submit" id="mc-embedded-subscribe" value="JOIN US">
                        <div style="position: absolute; left: -5000px;">
                            <input type="text" name="b_xxxxxxxxxxxxxxxxxxxCUSTOMxxxxxxxxx" value="">
                        </div>
                    </form>
                    <div id="notification_container"></div>
                    <p>We never spam!</p>
                </div><!-- end newsletter-signup -->
            </div>
            <div class="col-sm-12 col-md-3 mb-30">
                <h3 class="title m_title">GET IN TOUCH</h3>
                <div class="contact-details">
                    <p>
                        <strong>T (212) 555 55 00</strong><br>
                        Email: <a href="#">sales@yourwebsite.com</a>
                    </p>

                    <p>
                        Your Company LTD<br>
                        Street nr 100, 4536534, Chicago, US
                    </p>

                    <p>
                        <a href="http://goo.gl/maps/1OhOu" target="_blank">
                            <i class="icon-map-marker white-icon"></i>
                            Open in Google Maps
                        </a>
                    </p>
                </div>
                <!--/ .contact-details -->
            </div>
            <!--/ col-sm-12 col-md-3 mb-30 -->
        </div>
        <!--/ row -->

        <div class="row">
            <div class="col-sm-12 col-md-6"></div>
            <div class="col-sm-12 col-md-6 mb-30">
                <div class="payments-links d-flex">
                    <ul class="ml-auto mt-20">
                        <li>
                            <a href="#" class="fab fa-cc-paypal"></a>
                        </li>
                        <li>
                            <a href="#" class="fab fa-cc-visa"></a>
                        </li>
                        <li>
                            <a href="#" class="fab fa-cc-mastercard"></a>
                        </li>
                        <li>
                            <a href="#" class="fab fa-cc-amex"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="bottom clearfix">
                    <ul class="social-icons sc--clean clearfix">
                        <li class="title">GET SOCIAL</li>
                        <?php foreach ($socials as $row):
                            $platform = trim((string)($row['platform'] ?? ''));
                            $icon     = trim((string)($row['icon'] ?? '')); // örn: "fab fa-facebook-f"
                            $url      = trim((string)($row['url'] ?? '#'));
                            if ($url === '' && $platform === '') continue;
                            ?>
                            <li>
                                <a href="<?= esc($url) ?>"
                                   target="_blank"
                                   class="<?= esc($icon) ?>"
                                   title="<?= esc($platform) ?>"></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="copyright">
                        <?php echo view('public/inc/footerLogoSettings.php'); ?>
                        <p>© 2018 All rights reserved. Buy <a href="http://themeforest.net/item/kallyas-responsive-multipurpose-template/3583938">Kallyas Template</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>