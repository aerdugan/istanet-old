<div id="page_header" class="page-subheader site-subheader-cst">
    <div class="kl-bg-source">
        <?php if(!empty($items->logo)) { ?>
        <div class="kl-bg-source__bgimage" style="background-image: url(<?php echo $items->logo ;?>); background-repeat: no-repeat; background-attachment: scroll; background-position-x: center; background-position-y: center; background-size: cover;"></div>
        <?php } else { ?>
            <div class="kl-bg-source__overlay" style="background-color: <?php echo setThemeFirstColor() ?>;"></div>
        <?php } ?>
    </div>
    <div class="th-sparkles"></div>
    <div class="ph-content-wrap d-flex">
        <div class="container align-self-center">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <ul class="breadcrumbs fixclear">
                        <?php foreach ($pagesBreadcrumbs as $breadcrumb) { ?>
                            <li class="<?= (empty($breadcrumb['url'])) ? 'active' : '' ?>">
                                <?php if (empty($breadcrumb['url'])) { ?>
                                    <?= $breadcrumb['title'] ?>
                                <?php } else { ?>
                                    <a href="<?= site_url("".$breadcrumb['url']) ?>"><?= $breadcrumb['title'] ?></a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <span id="current-date" class="subheader-currentdate" style="display: none">Jan 01, 2018</span>
                    <div class="clearfix"></div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="subheader-titles">
                        <?php if(!empty($items->breadcrumbTitle)) { ?>
                            <h2 class="subheader-maintitle istShadow"><?= $items->breadcrumbTitle ?></h2>
                        <?php } else { ?>
                            <h2 class="subheader-maintitle istShadow"><?= $items->title ?></h2>
                        <?php } ?>
                        <?php if(!empty($items->breadcrumbSlogan)) { ?>
                            <h2 class="subheader-subtitle istShadow"><?= $items->breadcrumbSlogan ?></h2>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>