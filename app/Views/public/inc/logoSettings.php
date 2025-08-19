<?php $themeSettings = getThemeSettings(); ?>

<?php if ($themeSettings->setLogo == 1){ ?>
    <a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.Company_name'); ?>">
        <img style="max-height: 65px !important;" src="<?php echo setting('site.logo.logo');; ?>" alt="<?php echo setting('site.logo.Company_name');; ?>" title="<?php echo setting('site.logo.company_name');; ?>" />
    </a>
<?php } else if ($themeSettings->setLogo == 2){  ?>
    <a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.company_name');; ?>">
        <img style="max-height: 65px !important;"  src="<?php echo setting('site.logo.company_name');; ?>" class="logo-img" alt="<?php echo setting('site.logo.company_name'); ?>" title="<?php echo setting('site.logo.company_name');; ?>" />
    </a>
<?php } ?>
