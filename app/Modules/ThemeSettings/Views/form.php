<?php /** @var array $data */ ?>
<?= session()->getFlashdata('message') ? '<div class="alert alert-success">'.esc(session()->getFlashdata('message')).'</div>' : '' ?>
<?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.esc(session()->getFlashdata('error')).'</div>' : '' ?>

<form action="<?= route_to('theme.settings.save') ?>" method="post" class="container my-4">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Renk (colorCode)</label>
            <input type="text" name="colorCode" class="form-control" value="<?= esc($colorCode ?? '') ?>" placeholder="#000000">
        </div>
        <div class="col-md-3">
            <label class="form-label">Renk 2 (colorCode2)</label>
            <input type="text" name="colorCode2" class="form-control" value="<?= esc($colorCode2 ?? '') ?>" placeholder="#ffffff">
        </div>

        <div class="col-12"><hr><h5>Header / Footer / Slider</h5></div>
        <div class="col-md-3">
            <label class="form-label">Header</label>
            <input type="text" name="setHeader" class="form-control" value="<?= esc($setHeader ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobile Header</label>
            <input type="text" name="setMobileHeader" class="form-control" value="<?= esc($setMobileHeader ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Footer</label>
            <input type="text" name="setFooter" class="form-control" value="<?= esc($setFooter ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobile Footer</label>
            <input type="text" name="setMobileFooter" class="form-control" value="<?= esc($setMobileFooter ?? '') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Slider</label>
            <input type="text" name="setSlider" class="form-control" value="<?= esc($setSlider ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobile Slider</label>
            <input type="text" name="setMobileSlider" class="form-control" value="<?= esc($setMobileSlider ?? '') ?>">
        </div>

        <div class="col-12"><hr><h5>Breadcrumb</h5></div>
        <div class="col-md-3">
            <label class="form-label">Breadcrumb</label>
            <input type="text" name="setBreadcrumb" class="form-control" value="<?= esc($setBreadcrumb ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobile Breadcrumb</label>
            <input type="text" name="setMobileBreadcrumb" class="form-control" value="<?= esc($setMobileBreadcrumb ?? '') ?>">
        </div>
        <div class="col-md-3 form-check mt-4">
            <input class="form-check-input" type="checkbox" name="setBreadcrumbStatus" value="1" <?= !empty($setBreadcrumbStatus) ? 'checked' : '' ?>>
            <label class="form-check-label">Breadcrumb Açık</label>
        </div>
        <div class="col-md-3 form-check mt-4">
            <input class="form-check-input" type="checkbox" name="setMobileBreadcrumbStatus" value="1" <?= !empty($setMobileBreadcrumbStatus) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Breadcrumb Açık</label>
        </div>

        <div class="col-12"><hr><h5>Tema & Logolar</h5></div>
        <div class="col-md-3">
            <label class="form-label">Tema (ID)</label>
            <input type="number" name="setTheme" class="form-control" value="<?= esc($setTheme ?? '') ?>">
        </div>
        <div class="col-md-3 form-check mt-4">
            <input class="form-check-input" type="checkbox" name="isMobile" value="1" <?= !empty($isMobile) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobil Tema Kullan</label>
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobil Tema (ID)</label>
            <input type="number" name="setMobileTheme" class="form-control" value="<?= esc($setMobileTheme ?? '') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Logo (ID)</label>
            <input type="number" name="setLogo" class="form-control" value="<?= esc($setLogo ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Beyaz Logo (ID)</label>
            <input type="number" name="setWhiteLogo" class="form-control" value="<?= esc($setWhiteLogo ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobil Logo (ID)</label>
            <input type="number" name="setMobileLogo" class="form-control" value="<?= esc($setMobileLogo ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Mobil Beyaz Logo (ID)</label>
            <input type="number" name="setMobileWhiteLogo" class="form-control" value="<?= esc($setMobileWhiteLogo ?? '') ?>">
        </div>

        <div class="col-12"><hr><h5>Header & Footer Seçenekleri</h5></div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="headerSocial" value="1" <?= !empty($headerSocial) ? 'checked' : '' ?>>
            <label class="form-check-label">Header Sosyal</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="mobileHeaderSocial" value="1" <?= !empty($mobileHeaderSocial) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Header Sosyal</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="headerLang" value="1" <?= !empty($headerLang) ? 'checked' : '' ?>>
            <label class="form-check-label">Header Dil</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="mobileHeaderLang" value="1" <?= !empty($mobileHeaderLang) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Header Dil</label>
        </div>

        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setFoterSocial" value="1" <?= !empty($setFoterSocial) ? 'checked' : '' ?>>
            <label class="form-check-label">Footer Sosyal</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setMobileFoterSocial" value="1" <?= !empty($setMobileFoterSocial) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Footer Sosyal</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setFooterContact" value="1" <?= !empty($setFooterContact) ? 'checked' : '' ?>>
            <label class="form-check-label">Footer İletişim</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setMobileFooterContact" value="1" <?= !empty($setMobileFooterContact) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Footer İletişim</label>
        </div>

        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setFooterNewsLetter" value="1" <?= !empty($setFooterNewsLetter) ? 'checked' : '' ?>>
            <label class="form-check-label">Footer Bülten</label>
        </div>
        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="setMobileFooterNewsLetter" value="1" <?= !empty($setMobileFooterNewsLetter) ? 'checked' : '' ?>>
            <label class="form-check-label">Mobile Footer Bülten</label>
        </div>

        <div class="col-12"><hr><h5>Genel</h5></div>
        <div class="col-md-6">
            <label class="form-label">Copyright</label>
            <input type="text" name="copyright" class="form-control" value="<?= esc($copyright ?? '') ?>" placeholder="© 2025 Firma">
        </div>
        <div class="col-md-6">
            <label class="form-label">Open/Close (metin)</label>
            <input type="text" name="openClose" class="form-control" value="<?= esc($openClose ?? '') ?>" placeholder="Örn: 08:00 - 18:00">
        </div>

        <div class="col-md-3 form-check">
            <input class="form-check-input" type="checkbox" name="siteStatus" value="1" <?= !empty($siteStatus) ? 'checked' : '' ?>>
            <label class="form-check-label">Site Açık</label>
        </div>
        <div class="col-12">
            <label class="form-label">Site Kapanış Mesajı</label>
            <textarea name="siteCloseMessage" rows="4" class="form-control"><?= esc($siteCloseMessage ?? '') ?></textarea>
        </div>

        <div class="col-12 mt-3">
            <button class="btn btn-primary">Kaydet</button>
        </div>
    </div>
</form>
