<header id="header" class="site-header cta_button" data-header-style="1">
    <div class="kl-header-bg"></div>
    <div class="site-header-wrapper">
        <div class="site-header-top-wrapper">
            <div class="siteheader-container container">
                <div class="site-header-row site-header-top d-flex justify-content-between">
                    <div class="site-header-top-left d-flex">
                        <ul class="topnav social-icons sc--clean align-self-center">
                            <?php echo view('public/inc/socialSettings.php'); ?>
                        </ul>
                        <div class="clearfix visible-xxs"></div>
                        <div class="kl-header-toptext align-self-center">
                            <span class="topnav-item--text">QUESTIONS? CALL: </span>
                            <a href="tel:0900 800 900" class="fw-bold"><?php getCompanyDefaultPhone() ?></a>
                            <i class="phone-header fas fa-phone ml-5 visible-xs visible-sm visible-md"></i>
                        </div>
                    </div>
                    <div class="site-header-top-right d-flex">
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
                                                <img src="images/tr.svg" alt="Türkçe" class="toplang-flag "> Türkçe
                                            </a>
                                        </li>
                                        <li class="toplang-item">
                                            <a href="#">
                                                <img src="images/en.svg" alt="English" class="toplang-flag "> English
                                            </a>
                                        </li>
                                        <li class="toplang-item">
                                            <a href="#">
                                                <img src="images/de.svg" alt="German" class="toplang-flag "> German
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="topnav support--panel align-self-center">
                            <label for="support_p" class="topnav-item spanel-label">
                                <i class="fas fa-info-circle support-info closed"></i>
                                <i class="far fa-times-circle support-info opened"></i>
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
                <div class="separator site-header-separator"></div>
            </div>
        </div>
        <div class="site-header-main-wrapper d-flex">
            <div class="siteheader-container container align-self-center">
                <div class="site-header-row site-header-main d-flex flex-row justify-content-between">
                    <div class="site-header-main-left d-flex justify-content-start align-items-center">
                        <div class="logo-container logosize--yes">
                            <h1 class="site-logo logo" id="logo">
                                <?php echo view('public/inc/logoSettings.php'); ?>
                            </h1>
                        </div>
                        <div class="separator visible-xxs"></div>
                    </div>
                    <div class="site-header-main-center d-flex justify-content-center align-items-center">
                        <div class="main-menu-wrapper">
                            <div id="zn-res-menuwrapper">
                                <a href="#" class="zn-res-trigger "></a>
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