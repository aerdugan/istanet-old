<div class="kl-slideshow iosslider-slideshow uh_light_gray maskcontainer--shadow_ud iosslider--custom-height fullscreen scrollme">
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
                    <div class="kl-slide-overlay" style="background-color:rgba(6,111,217,0.3)"></div>
                    <div class="container kl-iosslide-caption kl-ioscaption--style4 fromright klios-alignright kl-caption-posv-middle">
                        <div class="animateme" data-when="span" data-from="0" data-to="0.75" data-opacity="0.1" data-easing="linear">
                            <?php if(!empty($slide->title)){ ?>
                                <h2 class="main_title has_titlebig "><span><strong><?php echo $slide->title ?></strong></span></h2>
                            <?php } ?>
                            <?php if(!empty($slide->button_url)){ ?>
                                <h3 class="title_big">&nbsp;</h3>
                                <a href="<?php echo $slide->button_url; ?>" target="_self" class="more" title="<?php echo $slide->title ?>"><?php echo $slide->button_caption; ?></a>
                            <?php } ?>
                            <?php if(!empty($slide->desc1)){ ?>
                                <h4 class="title_small"><?php echo $slide->desc1; ?></h4>
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
                <div class="item iosslider__bull-item first"></div>
            <?php } ?>
        </div>
    </div>
    <a class="tonext-btn js-tonext-btn" href="#" data-endof=".kl-slideshow-inner"><span class="mouse-anim-icon"></span></a>
    <div class="scrollbarContainer"></div>
</div>