<header id="header" class="site-header cta_button" data-header-style="5">
    <div class="kl-main-header">
        <div class="siteheader-container container d-flex">
            <div class="site-header-left-wrapper">
                <div class="logo-container logosize--yes d-flex align-items-center justify-content-center">
                    <h1 class="site-logo logo" id="logo">
                        <?php echo view('public/inc/logoSettings.php'); ?>
                    </h1>
                </div>
            </div>
            <div class="site-header-right-wrapper col align-self-center">
                <div class="site-header-row site-header-top d-flex flex-row justify-content-end justify-content-sm-end">
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="topnav support--panel">
                            <label for="support_p" class="topnav-item spanel-label">
                                <i class="fas fa-info-circle support-info"></i>
                                <span class="topnav-item--text">SUPPORT</span>
                            </label>
                        </div>
                        <div class="topnav login--panel">
                            <a class="topnav-item popup-with-form" href="#login_panel">
                                <i class="login-icon fas fa-sign-in-alt visible-xs xs-icon"></i>
                                <span class="topnav-item--text">LOGIN</span>
                            </a>
                        </div>
                        <div class="topnav topnav--lang">
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
                        <div class="d-none d-md-block align-self-center mr-20">
                            <ul class="topnav social-icons sc--clean">
                                <?php echo view('public/inc/socialSettings.php'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="site-header-row site-header-bottom d-flex flex-row justify-content-between">
                    <div class="main-menu-wrapper col d-flex justify-content-end align-self-center">
                        <div id="zn-res-menuwrapper">
                            <a href="#" class="zn-res-trigger zn-header-icon"></a>
                        </div>
                        <?php echo view('public/inc/headerMenu.php'); ?>
                    </div>
                    <div class="quote-ribbon"></div>
                </div>
            </div>
        </div>
    </div>
</header>