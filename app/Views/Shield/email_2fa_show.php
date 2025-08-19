<?php

use CodeIgniter\Shield\Entities\User;

?>

<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
    <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
        <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
            <?php if (session('error')) : ?>
                <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif ?>
            <form class="form w-100" action="<?= url_to('auth-action-handle') ?>" method="post">
                <?= csrf_field() ?>
                <div class="text-center mb-10">
                    <h1 class="text-gray-900 fw-bolder mb-3"><?= lang('Auth.email2FATitle') ?></h1>
                    <div class="text-gray-500 fw-semibold fs-6"><?= lang('Auth.confirmEmailAddress') ?></div>
                </div>
                <div class="fv-row mb-8" style="display: none">
                    <input type="email" class="form-control bg-transparent" name="email"
                           inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>"
                        <?php /** @var User $user */ ?>
                           value="<?= old('email', $user->email) ?>" required>
                </div>
                <div class="d-grid mb-10">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                </div>
            </form>
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
