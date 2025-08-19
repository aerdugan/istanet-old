<div class="kl-slideshow static-content__slideshow uh_hg_def_header_style maskcontainer--mask6">
			<div class="bgback"></div>
			<div class="kl-slideshow-inner static-content__wrapper static-content--height">
				<div class="static-content__source">
					<div class="kl-bg-source">

                        <?php if(!empty($items->logo)) { ?>
                            <div class="kl-bg-source__bgimage" style="background-image: url(<?php echo $items->logo ;?>); background-repeat: no-repeat; background-attachment: scroll; background-position-x: center; background-position-y: center; background-size: cover;"></div>
                        <?php } else { ?>
                            <div class="kl-bg-source__overlay" style="background-color: <?php echo setThemeFirstColor() ?>;"></div>
                        <?php } ?>
					</div>
					<div class="th-sparkles"></div>
				</div>
				<div class="static-content__inner container">
					<div class="kl-slideshow-safepadding sc__container">
						<div class="static-content event-style">
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12">
                                    <?php if(!empty($items->breadcrumbTitle)) { ?>
                                        <h2 class="static-content__title istShadow" style="font-size: 40px"><?= $items->breadcrumbTitle ?></h2>
                                    <?php } else { ?>
                                        <h2 class="static-content__title istShadow" style="font-size: 40px"><?= $items->title ?></h2>
                                    <?php } ?>
                                    <?php if(!empty($items->breadcrumbSlogan)) { ?>
                                        <h4 class="static-content__title istShadow" style="font-size: 20px;margin-top:0px"><?= $items->breadcrumbSlogan ?></h4>
                                    <?php } ?>
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
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="kl-bottommask kl-bottommask--mask6">
				<svg width="2700px" height="57px" class="svgmask" viewBox="0 0 2700 57" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					<defs>
						<filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-mask6">
							<feOffset dx="0" dy="-2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
							<feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
							<feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1"></feColorMatrix>
							<feMerge>
								<feMergeNode in="shadowMatrixOuter1"></feMergeNode>
								<feMergeNode in="SourceGraphic"></feMergeNode>
							</feMerge>
						</filter>
					</defs>
					<g transform="translate(-1.000000, 10.000000)">
						<path d="M0.455078125,18.5 L1,47 L392,47 L1577,35 L392,17 L0.455078125,18.5 Z" fill="#000000"></path>
						<path d="M2701,0.313493752 L2701,47.2349598 L2312,47 L391,47 L2312,0 L2701,0.313493752 Z" fill="#ffffff" class="bmask-bgfill" filter="url(#filter-mask6)"></path>
						<path d="M2702,3 L2702,19 L2312,19 L1127,33 L2312,3 L2702,3 Z" fill="<?php echo setThemeSecondColor() ?>" class="bmask-customfill"></path>
					</g>
				</svg>
			</div>
		</div>