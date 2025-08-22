<?php $themeSettings = getThemeSettings(); ?>
<?php
// URL normalize
$norm = static function (?string $v): string {
    $v = (string)($v ?? '');
    if ($v === '') return '';
    if (preg_match('#^https?://#i', $v)) return $v;
    return base_url(ltrim($v, '/'));
};

// Firma adı (alt/title)
$companyName = (string) (setting('Site/Company.company_name') ?: setting('Site/Company.companyLongName') ?: '');

// Hangi logo kullanılacak?
$selectedPath = ($themeSettings->setLogo == 2)
    ? (string) setting('Setting/Logo.secondLogo')
    : (string) setting('Setting/Logo.logo');

$logoSrc = $norm($selectedPath);
?>

<?php if ($logoSrc): ?>
    <a href="<?= site_url('/') ?>" title="<?= esc($companyName) ?>">
        <img style="max-height:45px"
             src="<?= esc($logoSrc) ?>"
             class="logo-img"
             alt="<?= esc($companyName) ?>"
             title="<?= esc($companyName) ?>">
    </a>
<?php endif; ?>