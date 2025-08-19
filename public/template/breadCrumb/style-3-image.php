<div id="page_header" class="page-subheader site-subheader-cst maskcontainer--mask4">
    <div class="bgback"></div>
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
    <div class="kl-bottommask kl-bottommask--mask4">
        <svg width="5000px" height="27px" class="svgmask " viewBox="0 0 5000 27" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-mask4">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetInner1"></feOffset>
                    <feGaussianBlur stdDeviation="1.5" in="shadowOffsetInner1" result="shadowBlurInner1"></feGaussianBlur>
                    <feComposite in="shadowBlurInner1" in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" result="shadowInnerInner1"></feComposite>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.35 0" in="shadowInnerInner1" type="matrix" result="shadowMatrixInner1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                        <feMergeNode in="shadowMatrixInner1"></feMergeNode>
                    </feMerge>
                </filter>
            </defs>
            <path d="M3.63975516e-12,-0.007 L2418,-0.007 L2434,-0.007 C2434,-0.007 2441.89,0.742 2448,2.976 C2454.11,5.21 2479,15 2479,15 L2492,21 C2492,21 2495.121,23.038 2500,23 C2505.267,23.03 2508,21 2508,21 L2521,15 C2521,15 2545.89,5.21 2552,2.976 C2558.11,0.742 2566,-0.007 2566,-0.007 L2582,-0.007 L5000,-0.007 L5000,27 L2500,27 L3.63975516e-12,27 L3.63975516e-12,-0.007 Z" class="bmask-bgfill" filter="url(#filter-mask4)" fill="#fbfbfb"></path>
        </svg>
    </div>
</div>