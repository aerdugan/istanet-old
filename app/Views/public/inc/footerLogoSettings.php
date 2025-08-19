<?php $themeSettings = getThemeSettings(); ?>

<?php if ($themeSettings->setFooterLogo == 1){ ?>
    <a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.company_name'); ?>">
        <img src="<?php echo setting('site.logo.logo'); ?>" width="150" height="37" alt="<?php echo setting('site.logo.company_name'); ?>" title="<?php echo setting('site.logo.company_name'); ?>" />
    </a>
<?php } else if ($themeSettings->setFooterLogo == 2){  ?>
    <a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.company_name');; ?>">
        <img src="<?php echo setting('site.logo.second_logo'); ?>" width="115" height="37" alt="<?php echo setting('site.logo.company_name'); ?>" title="<?php echo setting('site.logo.company_name'); ?>" />
    </a>
<?php } ?>