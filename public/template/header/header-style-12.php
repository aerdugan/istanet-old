<header id="header" class="site-header" data-header-style="12">
    <div class="kl-main-header">
        <div class="site-header-main-wrapper">
            <div class="siteheader-container container">
                <div class="site-header-row site-header-main d-flex justify-content-center align-items-center">
                    <div class="logo-container hasInfoCard logosize--yes d-flex">
                        <h1 class="site-logo logo align-self-center" id="logo">
                            <?php echo view('public/inc/logoSettings.php'); ?>
                        </h1>
                    </div>
                </div>
                <div class="separator"></div>
            </div>
        </div>
        <div class="site-header-bottom-wrapper">
            <div class="siteheader-container container">
                <div class="site-header-row site-header-bottom d-flex flex-row justify-content-center">
                    <div class="main-menu-wrapper col">
                        <div id="zn-res-menuwrapper">
                            <a href="#" class="zn-res-trigger zn-header-icon"></a>
                        </div>
                        <?php echo view('public/inc/headerMenu.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>