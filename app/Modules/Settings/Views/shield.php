<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Shield Ayarları</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Kontrol Paneli</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Ayarlar</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Shield Ayarları</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title fs-3 fw-bold">Shield Ayarları</div>
    </div>
    <form action="<?= route_to('admin.settings.shield.save') ?>" method="post" class="card p-3">
        <?= csrf_field() ?>
        <div class="card-body p-9">
            <?php if (session('message')): ?>
            <div class="alert alert-success"><?= esc(session('message')) ?></div>
            <?php endif; ?>
            <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4 form-check form-switch mt-7">
                    <input class="form-check-input" type="checkbox" id="mfa" name="mfa" value="1" <?= $mfa ? 'checked' : '' ?>>
                    <label class="form-check-label" for="mfa">MFA (İki Aşamalı Doğrulama) Zorunlu</label>
                </div>
                <div class="col-md-4 form-check form-switch mt-7">
                    <input class="form-check-input" type="checkbox" id="magicOnly" name="magicOnly" value="1" <?= $magicOnly ? 'checked' : '' ?>>
                    <label class="form-check-label" for="magicOnly">Sadece Magic Link ile Giriş</label>
                </div>
                <div class="col-md-4 form-check form-switch mt-7">
                    <input class="form-check-input" type="checkbox" id="allowRemembering" name="allowRemembering" value="1" <?= $allowRemembering ? 'checked' : '' ?>>
                    <label class="form-check-label" for="allowRemembering">“Beni Hatırla” Etkin</label>
                </div>
                <div class="col-md-4 form-check form-switch mt-7">
                    <input class="form-check-input" type="checkbox" id="allowRegistration" name="allowRegistration" value="1" <?= $allowRegistration ? 'checked' : '' ?>>
                    <label class="form-check-label" for="allowRegistration">Kayıt (Registration) Açık</label>
                </div>
                <div class="col-md-4 form-check form-switch mt-7">
                    <input class="form-check-input" type="checkbox" id="resetByEmail" name="resetByEmail" value="1" <?= $resetByEmail ? 'checked' : '' ?>>
                    <label class="form-check-label" for="resetByEmail">E‑posta ile Şifre Sıfırlama Açık</label>
                </div>
            </div>
            <div class="row mb-8 mt-6">
                <div class="col-md-3">
                    <label class="form-label">Kimlik Doğrulama Şekli</label>
                    <select class="form-select" name="loginBy">
                        <option value="email"    <?= $loginBy === 'email' ? 'selected' : '' ?>>E‑posta</option>
                        <option value="username" <?= $loginBy === 'username' ? 'selected' : '' ?>>Kullanıcı Adı</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kaba Kuvvet Koruması (Maks. Giriş Denemesi)</label>
                    <input type="number" class="form-control" name="bruteforceMax" min="0" value="<?= esc($bruteforceMax) ?>">
                    <small class="text-muted">0 = kapalı</small>
                </div>
            </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button class="btn btn-light-success me-2">Güncelle</button>
            <a class="btn btn-light-danger" href="/dashboard">İptal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<?= $this->endSection() ?>