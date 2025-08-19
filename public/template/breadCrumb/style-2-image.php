<div id="page_header" class="page-subheader site-subheader-cst">
    <div class="bgback"></div>
    <div class="th-sparkles"></div>
    <div class="kl-bg-source">
        <?php if(!empty($items->logo)) { ?>
            <div class="kl-bg-source__bgimage" style="background-image: url(<?php echo $items->logo ;?>); background-repeat: no-repeat; background-attachment: scroll; background-position-x: center; background-position-y: center; background-size: cover;"></div>
        <?php } else { ?>
            <div class="kl-bg-source__overlay" style="background-color: <?php echo setThemeFirstColor() ?>;"></div>
        <?php } ?>
    </div>
    <div class="ph-content-wrap d-flex">
        <div class="container align-self-center">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <br><br>
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
                        <?php } else { ?>
                            <h2 class="subheader-subtitle istShadow"></h2>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kl-bottommask kl-bottommask--mask3">
        <svg width="5000px" height="57px" class="svgmask " viewBox="0 0 5000 57" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-mask3">
                    <feOffset dx="0" dy="3" in="SourceAlpha" result="shadowOffsetInner1"></feOffset>
                    <feGaussianBlur stdDeviation="2" in="shadowOffsetInner1" result="shadowBlurInner1"></feGaussianBlur>
                    <feComposite in="shadowBlurInner1" in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" result="shadowInnerInner1"></feComposite>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.4 0" in="shadowInnerInner1" type="matrix" result="shadowMatrixInner1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                        <feMergeNode in="shadowMatrixInner1"></feMergeNode>
                    </feMerge>
                </filter>
            </defs>
            <path d="M9.09383679e-13,57.0005249 L9.09383679e-13,34.0075249 L2418,34.0075249 L2434,34.0075249 C2434,34.0075249 2441.89,33.2585249 2448,31.0245249 C2454.11,28.7905249 2479,11.0005249 2479,11.0005249 L2492,2.00052487 C2492,2.00052487 2495.121,-0.0374751261 2500,0.000524873861 C2505.267,-0.0294751261 2508,2.00052487 2508,2.00052487 L2521,11.0005249 C2521,11.0005249 2545.89,28.7905249 2552,31.0245249 C2558.11,33.2585249 2566,34.0075249 2566,34.0075249 L2582,34.0075249 L5000,34.0075249 L5000,57.0005249 L2500,57.0005249 L1148,57.0005249 L9.09383679e-13,57.0005249 Z" class="bmask-bgfill" filter="url(#filter-mask3)" fill="#fbfbfb"></path>
        </svg>
        <i class="fas fa-angle-down"></i>
    </div>
</div>