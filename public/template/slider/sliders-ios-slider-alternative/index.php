<div class="kl-slideshow iosslider-slideshow uh_light_gray  iosslider--custom-height scrollme">
    <div class="kl-loader">
        <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewbox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
					<path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
            <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z" transform="rotate(98.3774 20 20)">
                <animatetransform attributetype="xml" attributename="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatcount="indefinite"></animatetransform>
            </path>
        </svg>
    </div>
    <div class="bgback"></div>
    <div class="th-sparkles"></div>
    <div class="iosSlider kl-slideshow-inner animateme" data-trans="6000" data-autoplay="1" data-infinite="true" data-when="span" data-from="0" data-to="0.75" data-translatey="300" data-easing="linear">
        <div class="kl-iosslider hideControls">
            <?php foreach (getAllSlides() as $slide){ ?>
            <div class="item iosslider__item">
                <div class="slide-item-bg" style="background-image:url(<?php echo $slide->imgUrl; ?>);"></div>
                <div class="kl-slide-overlay">
                </div>
                <div class="container kl-iosslide-caption kl-ioscaption--style3 s3ext fromleft klios-alignleft kl-caption-posv-middle">
                    <div class="animateme" data-when="span" data-from="0" data-to="0.75" data-opacity="0.1" data-easing="linear">
                        <?php if(!empty($slide->title)){ ?>
                        <h2 class="main_title has_titlebig" style="text-shadow: 5px 5px 10px black;font-size: 50px">
                            <span><strong><?php echo $slide->title; ?></strong></span>
                        </h2>
                        <?php } ?>
                        <?php if(!empty($slide->desc1)){ ?>
                        <h4 class="title_small" style="text-shadow: 5px 5px 10px black;"><?php echo $slide->desc1; ?></h4>
                        <?php } ?>
                        <?php if(!empty($slide->button_url)){ ?>

                        <div class="more">
                            <br><br><br>
                            <a href="<?php echo $slide->button_url; ?>" target="_self" class="btn btn-fullcolor" title="Kallyas collection"><?php echo $slide->button_caption; ?></a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="kl-iosslider-prev">
            <span class="thin-arrows ta__prev"></span>
            <div class="btn-label">PREV</div>
        </div>
        <div class="kl-iosslider-next">
            <span class="thin-arrows ta__next"></span>
            <div class="btn-label">NEXT</div>
        </div>
    </div>
    <div class="kl-ios-selectors-block bullets2">
        <div class="selectors">
            <?php foreach (getAllSlides() as $slide){ ?>
            <div class="item iosslider__bull-item"></div>
            <?php } ?>
        </div>
    </div>
    <div class="scrollbarContainer"></div>
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
            <path d="M9.09383679e-13,57.0005249 L9.09383679e-13,34.0075249 L2418,34.0075249 L2434,34.0075249 C2434,34.0075249 2441.89,33.2585249 2448,31.0245249 C2454.11,28.7905249 2479,11.0005249 2479,11.0005249 L2492,2.00052487 C2492,2.00052487 2495.121,-0.0374751261 2500,0.000524873861 C2505.267,-0.0294751261 2508,2.00052487 2508,2.00052487 L2521,11.0005249 C2521,11.0005249 2545.89,28.7905249 2552,31.0245249 C2558.11,33.2585249 2566,34.0075249 2566,34.0075249 L2582,34.0075249 L5000,34.0075249 L5000,57.0005249 L2500,57.0005249 L1148,57.0005249 L9.09383679e-13,57.0005249 Z" class="bmask-bgfill" filter="url(#filter-mask3)" fill="#ffffff"></path>
        </svg>
        <i class="fas fa-angle-down"></i>
    </div>
</div>