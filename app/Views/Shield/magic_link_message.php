<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
    <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
        <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
            <div class="text-center mb-10">
                <h1 class="text-gray-900 fw-bolder mb-3"><?= lang('Auth.useMagicLink') ?></h1>
                <div class="text-gray-500 fw-semibold fs-6"><b><?= lang('Auth.checkYourEmail') ?></b></div>
            </div>
            <div class="fv-row mb-8">
                <p class="text-gray-500 fw-semibold fs-6 text-center"><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
            </div>
            <div class="d-flex flex-wrap justify-content-center pb-lg-0"></div>
        </div>
        <div class="d-flex flex-stack px-lg-10">
            <div class="me-0"></div>
            <div class="d-flex fw-semibold text-primary fs-base gap-5">
                <a href="pages/team.html" target="_blank">Terms</a>
                <a href="pages/pricing/column.html" target="_blank">Plans</a>
                <a href="pages/contact.html" target="_blank">Contact Us</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>