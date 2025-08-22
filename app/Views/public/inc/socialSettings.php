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

