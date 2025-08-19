<div id="page_header" class="page-subheader site-subheader-cst uh_hg_def_header_style maskcontainer--mask6">
    <div class="bgback"></div>
    <div class="kl-bg-source">
        <?php if(!empty($items->logo)) { ?>
            <div class="kl-bg-source__bgimage" style="background-image: url(<?php echo $items->logo ;?>); background-repeat: no-repeat; background-attachment: scroll; background-position-x: center; background-position-y: center; background-size: cover;"></div>
        <?php } else { ?>
            <div class="kl-bg-source__overlay" style="background-color: <?php echo setThemeFirstColor() ?>;"></div>
        <?php } ?>
    </div>
    <div class="th-sparkles"></div>
    <div class="ph-content-wrap d-flex h-600">
        <div class="container align-self-center">
            <div class="row">
                <div class="col-sm-12 col-md-6 offset-md-6 col-lg-6">
                    <div class="subheader-titles right">

                        <?php if(!empty($items->breadcrumbTitle)) { ?>
                            <h2 class="subheader-maintitle fs-xxxxl fs-sm-xxl fw-extrabold reset-line-height mb-0 mt-xs-50 istShadow"><?= $items->breadcrumbTitle ?></h2>
                        <?php } else { ?>
                            <h2 class="subheader-maintitle fs-xxxxl fs-sm-xxl fw-extrabold reset-line-height mb-0 mt-xs-50 istShadow"><?= $items->title ?></h2>
                        <?php } ?>
                        <?php if(!empty($items->breadcrumbSlogan)) { ?>
                            <h4 class="fs-m white opacity6 mb-0 istShadow "><?= $items->breadcrumbSlogan ?></h4>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kl-bottommask kl-bottommask--mask7">
        <svg width="767px" height="60px" class="kl-bottommask--mask7 screffect" viewBox="0 0 767 60" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
            <polygon fill="<?php echo setThemeSecondColor() ?>" points="767 0 767 60 0 60 "></polygon>
        </svg>
        <svg width="767px" height="50px" class="kl-bottommask--mask7 mask-over" viewBox="0 0 767 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
            <polygon fill="#fbfbfb" points="767 0 767 50 0 50 "></polygon>
        </svg>
    </div>
    <div class="container mb-40">
        <div class="row">
            <div class="col-sm-12">
                
                <ul class="breadcrumbs2 white text-right kl-font-alt" style="opacity: 1;">
                    <?php foreach ($pagesBreadcrumbs as $breadcrumb) { ?>
                        <li class="istShadow <?= (empty($breadcrumb['url'])) ? 'active' : '' ?>">
                            <?php if (empty($breadcrumb['url'])) { ?>
                                <?= $breadcrumb['title'] ?>
                            <?php } else { ?>
                                <a class="istShadow" href="<?= site_url("".$breadcrumb['url']) ?>"><?= $breadcrumb['title'] ?></a>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>