<div style="height:140px"></div>Ahmettttt
<div class="container">
	<div class="row">
		<div class="col-sm-6">
            <?php if(!empty($items->breadcrumbTitle)) { ?>
                <h3 class="tbk__title kl-font-alt fw-semibold tcolor">
                    <?= $items->breadcrumbTitle ?>
                </h3>
            <?php } else { ?>
                <h3 class="tbk__title kl-font-alt fw-semibold tcolor">
                <?= $items->title ?>
                </h3>
            <?php } ?>
		</div>
		<div class="col-sm-6">
			<ul class="breadcrumbs2 text-right kl-font-alt" style="opacity: 1;">
				<?php foreach ($pagesBreadcrumbs as $breadcrumb) { ?>
				<li class=" <?= (empty($breadcrumb['url'])) ? 'active' : '' ?>">
					<?php if (empty($breadcrumb['url'])) { ?>
					<strong><?= $breadcrumb['title'] ?></strong>
					<?php } else { ?>
					<a class="" href="<?= site_url("".$breadcrumb['url']) ?>"><?= $breadcrumb['title'] ?></a>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
    <div class="tbk__symbol">
        <span></span>
    </div>
</div>