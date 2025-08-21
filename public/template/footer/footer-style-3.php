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

<footer id="footer" data-footer-style="3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex justify-content-start">
                <div class="d-flex">
                    <div class="d-flex mr-30">
                        <?php if ($themeSettings->setFooterLogo == 1){ ?>
                            <a href="<?php echo base_url(''); ?>" title="<?= esc(setting('site.logo.company_name')) ?>">
                                <img src="<?= esc(setting('site.logo.logo')) ?>" width="80" height="37" alt="<?= esc(setting('site.logo.company_name')) ?>" title="<?= esc(setting('site.logo.company_name')) ?>" />
                            </a>
                        <?php } else if ($themeSettings->setFooterLogo == 2){  ?>
                            <a href="<?php echo base_url(''); ?>" title="<?= esc(setting('site.logo.company_name')) ?>">
                                <img src="<?= esc(setting('site.logo.second_logo')) ?>" width="80" height="37" alt="<?= esc(setting('site.logo.company_name')) ?>" title="<?= esc(setting('site.logo.company_name')) ?>" />
                            </a>
                        <?php } ?>
                    </div>
                    <div class="d-flex">
                        <p class="mb-0">© <?php echo date('Y')?> All rights reserved.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
                <div class="d-flex">
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
        </div>
    </div>
</footer>



