<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>
<?= $this->section('pageStyles') ?>
<style>
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    input[type=number]:focus { outline: none; }
</style>
<?= $this->endSection() ?>


<?= $this->section('main') ?>
<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
    <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
        <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif ?>
            <form class="form w-100 mb-13" id="otpForm" action="<?= url_to('auth-action-verify') ?>" method="post">
                <?= csrf_field() ?>
                <div class="text-center mb-10">
                    <img alt="Logo" class="mh-125px" src="<?= adminTheme()?>assets/media/svg/misc/smartphone-2.svg" />
                </div>
                <div class="text-center mb-10">
                    <h1 class="text-gray-900 mb-3"><?= lang('Auth.emailEnterCode') ?></h1>
                    <div class="text-muted fw-semibold fs-5 mb-5"><?= lang('Auth.emailConfirmCode') ?></div>
                </div>
                <div class="mb-10">
                    <div class="fw-bold text-start text-gray-900 fs-6 mb-1 ms-1">Type your 6 digit security code</div>
                    <div class="d-flex justify-content-center" id="otpGroup">
                        <input type="number" name="code_1" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" autocomplete="one-time-code" />
                        <input type="number" name="code_2" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" />
                        <input type="number" name="code_3" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" />
                        <input type="number" name="code_4" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" />
                        <input type="number" name="code_5" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" />
                        <input type="number" name="code_6" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" inputmode="numeric" />
                    </div>
                    <input type="hidden" name="token" id="token">
                </div>
                <div class="d-grid mb-10">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login') ?></button>
                </div>
            </form>
            <div class="text-center fw-semibold fs-5">
                <span class="text-muted me-1">Didn’t get the code ?</span>
                <a href="#" class="link-primary fs-5 me-1">Resend</a>
                <span class="text-muted me-1">or</span>
                <a href="#" class="link-primary fs-5">Call Us</a>
            </div>
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
<?= $this->section('pageScripts') ?>
<script>
    (function () {
        const group  = document.getElementById('otpGroup');
        const inputs = Array.from(group.querySelectorAll('input[type="number"]'));
        const hidden = document.getElementById('token');

        // Mouse wheel ile değerin değişmesini engelle (özellikle number inputlarda olur)
        inputs.forEach(inp => {
            inp.addEventListener('wheel', e => e.preventDefault(), { passive:false });
        });

        inputs.forEach((input, i) => {
            // Sadece tek rakama izin ver
            input.addEventListener('beforeinput', (e) => {
                if (e.data && !/^\d$/.test(e.data)) e.preventDefault();
            });

            input.addEventListener('input', (e) => {
                // Bazı tarayıcılar maxlength'i number'da ciddiye almaz; elle kıs
                e.target.value = (e.target.value + '').replace(/\D/g, '').slice(0, 1);

                // Dolduysa sonraki inputa geç
                if (e.target.value && i < inputs.length - 1) {
                    inputs[i + 1].focus();
                    inputs[i + 1].select?.();
                }
                updateHidden();
            });

            // Backspace boşken öncekine dön
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && i > 0) {
                    inputs[i - 1].focus();
                    inputs[i - 1].select?.();
                    e.preventDefault();
                }
                // Ok tuşlarıyla gezinme (sol/sağ)
                if (e.key === 'ArrowLeft' && i > 0) {
                    inputs[i - 1].focus(); e.preventDefault();
                }
                if (e.key === 'ArrowRight' && i < inputs.length - 1) {
                    inputs[i + 1].focus(); e.preventDefault();
                }
            });

            // İlk kutuya 6 hane yapıştırıldığında dağıt
            if (i === 0) {
                input.addEventListener('paste', (e) => {
                    const text = (e.clipboardData || window.clipboardData).getData('text') || '';
                    const digits = text.replace(/\D/g, '').slice(0, inputs.length).split('');
                    if (!digits.length) return;
                    e.preventDefault();
                    inputs.forEach((inp, idx) => inp.value = digits[idx] || '');
                    (inputs[digits.length] || inputs[inputs.length - 1]).focus();
                    updateHidden();
                });
            }
        });

        function updateHidden() {
            hidden.value = inputs.map(inp => inp.value || '').join('');
        }

        // Submit öncesi 6 hane kontrol
        document.getElementById('otpForm').addEventListener('submit', (e) => {
            if (hidden.value.length !== inputs.length) {
                e.preventDefault();
                alert('Lütfen 6 haneli doğrulama kodunu girin.');
                (inputs.find(inp => !inp.value) || inputs[0]).focus();
            }
        });
    })();
</script>
<?= $this->endSection() ?>
