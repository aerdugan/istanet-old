<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
    <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
        <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
            <form class="form w-100" action="<?= url_to('magic-link') ?>" method="post">
                <?= csrf_field() ?>
                <div class="text-center mb-10">
                    <h1 class="text-gray-900 fw-bolder mb-3"><?= lang('Auth.useMagicLink') ?></h1>
                    <div class="text-gray-500 fw-semibold fs-6">
                        <?php if (session('error') !== null) : ?>
                            <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
                        <?php elseif (session('errors') !== null) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php if (is_array(session('errors'))) : ?>
                                    <?php foreach (session('errors') as $error) : ?>
                                        <?= esc($error) ?>
                                        <br>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <?= esc(session('errors')) ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                <div class="fv-row mb-8">
                    <input type="email" class="form-control bg-transparent" id="floatingEmailInput" name="email"
                           autocomplete="email" placeholder="<?= lang('Auth.email') ?>"
                           value="<?= old('email', auth()->user()->email ?? null) ?>" required>
                </div>
                <div class="d-flex flex-wrap justify-content-center pb-lg-0 gap-2">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.send') ?></button>
                    <a class="btn btn-light" href="<?= url_to('login') ?>"><?= lang('Auth.backToLogin') ?></a>
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
