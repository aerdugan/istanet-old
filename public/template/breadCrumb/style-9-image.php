<div id="page_header" class="page-subheader site-subheader-cst">
    <div class="bgback"></div>
    <div class="th-sparkles"></div>

    <div class="ph-content-wrap d-flex min-500">
        <div class="container align-self-center">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <?php if(!empty($items->breadcrumbTitle)) { ?>
                        <h1 class="fs-xxxl fw-extrabold white istShadow"><?= $items->breadcrumbTitle ?></h1>
                    <?php } else { ?>
                        <h1 class="fs-xxxl fw-extrabold white istShadow"><?= $items->title ?></h1>
                    <?php } ?>
                    <?php if(!empty($items->breadcrumbSlogan)) { ?>
                        <h4 class="fs-m white opacity6 mb-0istShadow "><?= $items->breadcrumbSlogan ?></h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="kl-bottommask kl-bottommask--mask7">
        <svg width="767px" height="60px" class="kl-bottommask--mask7 screffect " viewBox="0 0 767 60" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
            <polygon fill="<?php echo setThemeSecondColor() ?>" points="767 0 767 60 0 60 "></polygon>
        </svg>
        <svg width="767px" height="50px" class="kl-bottommask--mask7 mask-over" viewBox="0 0 767 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
            <polygon fill="#fff" points="767 0 767 50 0 50 "></polygon>
        </svg>
    </div>
</div>