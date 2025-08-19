<div id="page_header" class="page-subheader site-subheader-cst maskcontainer--mask5">
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
                    <span id="current-date" class="subheader-currentdate">Jan 01, 2018</span>
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
    <div class="kl-bottommask kl-bottommask--mask5">
        <svg width="2700px" height="64px" class="svgmask" viewBox="0 0 2700 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-mask5">
                    <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetInner1"></feOffset>
                    <feGaussianBlur stdDeviation="1.5" in="shadowOffsetInner1" result="shadowBlurInner1"></feGaussianBlur>
                    <feComposite in="shadowBlurInner1" in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" result="shadowInnerInner1"></feComposite>
                    <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.45 0" in="shadowInnerInner1" type="matrix" result="shadowMatrixInner1"></feColorMatrix>
                    <feMerge>
                        <feMergeNode in="SourceGraphic"></feMergeNode>
                        <feMergeNode in="shadowMatrixInner1"></feMergeNode>
                    </feMerge>
                </filter>
            </defs>
            <path d="M1892,0 L2119,44.993 L2701,45 L2701.133,63.993 L-0.16,63.993 L1.73847048e-12,45 L909,44.993 L1892,0 Z" class="bmask-bgfill" fill="#fbfbfb" filter="url(#filter-mask5)"></path>
            <path d="M2216,44.993 L2093,55 L1882,6 L995,62 L966,42 L1892,0 L2118,44.993 L2216,44.993 L2216,44.993 Z" fill="<?php echo setThemeSecondColor() ?>" class="bmask-customfill" filter="url(#filter-mask5)"></path>
        </svg>
    </div>
</div>