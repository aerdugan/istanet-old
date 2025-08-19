<header id="header" class="site-header cta_button" data-header-style="2">
    <div class="kl-main-header">
        <div class="site-header-top-wrapper">
            <div class="siteheader-container container">
                <div class="site-header-row site-header-top d-flex justify-content-between">
                    <div class="site-header-top-left d-flex align-self-center">
                        <div class="logo-container logosize--yes d-flex">
                            <h1 class="site-logo logo align-self-center" id="logo">
                                <?php echo view('public/inc/logoSettings.php'); ?>
                            </h1>
                        </div>
                    </div>
                    <div class="site-header-top-center justify-content-center align-self-center d-none d-md-flex">
                        <!-- Header search --><!--/ Header search -->
                    </div>
                    <div class="site-header-top-right">
                        <div class="d-flex justify-content-end">
                            <div class="align-self-center mr-xl-30">
                                <ul class="topnav social-icons sc--clean">
                                    <?php echo view('public/inc/socialSettings.php'); ?>
                                </ul>
                            </div>
                            <div class="topnav topnav--lang align-self-center">
                                <div class="languages drop">
                                    <a href="#" class="topnav-item">
                                        <span class="fas fa-globe xs-icon"></span>
                                        <span class="topnav-item--text">LANGUAGES</span>
                                    </a>
                                    <div class="pPanel">
                                        <ul class="inner">
                                            <li class="toplang-item active">
                                                <a href="#">
                                                    <img src="images/en.svg" alt="English" class="toplang-flag "> English
                                                </a>
                                            </li>
                                            <li class="toplang-item">
                                                <a href="#">
                                                    <img src="images/fr.svg" alt="Francais" class="toplang-flag "> Francais
                                                </a>
                                            </li>
                                            <li class="toplang-item">
                                                <a href="#">
                                                    <img src="images/es.svg" alt="Espanol" class="toplang-flag "> Espanol
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="topnav support--panel align-self-center">
                                <label for="support_p" class="topnav-item spanel-label">
                                    <i class="fas fa-info-circle support-info"></i>
                                    <span class="topnav-item--text">SUPPORT</span>
                                </label>
                            </div>
                            <div class="topnav login--panel align-self-center">
                                <a class="topnav-item popup-with-form" href="#login_panel">
                                    <i class="login-icon fas fa-sign-in-alt visible-xs xs-icon"></i>
                                    <span class="topnav-item--text">LOGIN</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-header-main-wrapper">
            <div class="siteheader-container container">
                <div class="site-header-row site-header-main d-flex flex-row justify-content-between">
                    <div class="site-header-main-left d-flex justify-content-start align-items-center">
                        <div class="main-menu-wrapper">
                            <div id="zn-res-menuwrapper">
                                <a href="#" class="zn-res-trigger zn-header-icon"></a>
                            </div>
                            <?php echo view('public/inc/headerMenu.php'); ?>
                        </div>
                    </div>
                    <div class="site-header-main-right d-flex justify-content-end align-items-center">
                        <div class="quote-ribbon"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>