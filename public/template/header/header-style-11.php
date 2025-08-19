<?php $themeSettings = getThemeSettings(); ?>
<header id="header" class="site-header cta_button" data-header-style="11">
    <div class="site-header-wrapper">
        <div class="site-header-main-wrapper d-flex">
            <div class="siteheader-container container-fluid">
                <div class="site-header-row site-header-main d-flex flex-row justify-content-between">
                    <div class="site-header-main-left d-flex justify-content-start align-items-center align-self-center">
                        <div class="logo-container logosize--yes">
                            <h1 class="site-logo logo" id="logo">
							<?php if ($themeSettings->setLogo == 1){ ?>
								<a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.company_name');; ?>">
									<img style="max-height: 45px !important;" src="<?php echo setting('site.logo.logo');; ?>" alt="<?php echo setting('site.logo.company_name');; ?>" title="<?php echo setting('site.logo.company_name');; ?>" />
								</a>
							<?php } else if ($themeSettings->setLogo == 2){  ?>
								<a href="<?php echo base_url(''); ?>" title="<?php echo setting('site.logo.company_name');; ?>">
									<img style="max-height: 45px !important;"  src="<?php echo setting('site.logo.logo');; ?>" class="logo-img" alt="<?php echo setting('site.logo.company_name');; ?>" title="<?php echo setting('site.logo.company_name');; ?>" />
								</a>
							<?php } ?>
							</h1>
                        </div>
                        <div class="separator visible-xxs"></div>
                    </div>
                    <div class="site-header-main-right d-flex justify-content-center align-items-center">
                        <div class="main-menu-wrapper">
                            <div id="zn-res-menuwrapper">
                                <a href="#" class="zn-res-trigger "></a>
                            </div>
                            <?php echo view('public/inc/headerMenu.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
