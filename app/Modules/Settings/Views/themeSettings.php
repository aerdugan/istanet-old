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
        <form action="<?= route_to('admin.settings.theme.save') ?>" method="post">
        <div class="card-header">
            <h3 class="card-title"><?= esc($title ?? 'Tema Ayarları') ?></h3>
            <div class="card-toolbar">
                <label class="form-check-label" for="isMobile">Mobil Tema Kullan&nbsp;&nbsp;&nbsp;</label>
                <input class="form-check-input" type="checkbox" id="isMobile" name="isMobile" value="1" <?= !empty($isMobile) ? 'checked' : '' ?>>
            </div>
        </div>
        <div class="card-body">
            <?= csrf_field() ?>
            <?php if ($m = session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= esc($m) ?></div>
            <?php endif; ?>
            <?php if ($e = session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc($e) ?></div>
            <?php endif; ?>

            <div class="col-12"><h5>Header / Footer / Slider</h5><hr></div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Renk (colorCode)</label>
                    <input type="text" name="colorCode" class="form-control" value="<?= esc($colorCode ?? '') ?>" placeholder="#000000">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Renk 2 (colorCode2)</label>
                    <input type="text" name="colorCode2" class="form-control" value="<?= esc($colorCode2 ?? '') ?>" placeholder="#ffffff">
                </div>
            </div>
            <br>
            <div class="col-12"><h5>Header / Footer / Slider</h5><hr></div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Header</label>
                    <input type="text" name="setHeader" class="form-control" value="<?= esc($setHeader ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Footer</label>
                    <input type="text" name="setFooter" class="form-control" value="<?= esc($setFooter ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Slider</label>
                    <input type="text" name="setSlider" class="form-control" value="<?= esc($setSlider ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Breadcrumb   <input class="form-check-input" type="checkbox" name="setBreadcrumbStatus" value="1" <?= !empty($setBreadcrumbStatus) ? 'checked' : '' ?>>
                    </label>
                    <input type="text" name="setBreadcrumb" class="form-control" value="<?= esc($setBreadcrumb ?? '') ?>">
                </div>
            </div>
            <hr>
            <div class="col-12 mobile-field"><h5>Mobile Header / Footer / Slider</h5><hr></div>
            <div class="row mobile-field ">
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobile Header</label>
                    <input type="text" name="setMobileHeader" class="form-control" value="<?= esc($setMobileHeader ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobile Footer</label>
                    <input type="text" name="setMobileFooter" class="form-control" value="<?= esc($setMobileFooter ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobile Slider</label>
                    <input type="text" name="setMobileSlider" class="form-control" value="<?= esc($setMobileSlider ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobile Breadcrumb <input class="form-check-input" type="checkbox" name="setMobileBreadcrumbStatus" value="1" <?= !empty($setMobileBreadcrumbStatus) ? 'checked' : '' ?>></label>
                    <input type="text" name="setMobileBreadcrumb" class="form-control" value="<?= esc($setMobileBreadcrumb ?? '') ?>">
                </div>
            </div>
            <div class="col-12"><hr><h5>Tema & Logolar</h5></div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Tema (ID)</label>
                    <input type="number" name="setTheme" class="form-control" value="<?= esc($setTheme ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobil Tema (ID)</label>
                    <input type="number" name="setMobileTheme" class="form-control" value="<?= esc($setMobileTheme ?? '') ?>">
                </div>
                <div class="col-md-3 form-check mt-4">

                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Logo (ID)</label>
                    <input type="number" name="setLogo" class="form-control" value="<?= esc($setLogo ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Beyaz Logo (ID)</label>
                    <input type="number" name="setWhiteLogo" class="form-control" value="<?= esc($setWhiteLogo ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobil Logo (ID)</label>
                    <input type="number" name="setMobileLogo" class="form-control" value="<?= esc($setMobileLogo ?? '') ?>">
                </div>
                <div class="col-md-3 mobile-field">
                    <label class="form-label">Mobil Beyaz Logo (ID)</label>
                    <input type="number" name="setMobileWhiteLogo" class="form-control" value="<?= esc($setMobileWhiteLogo ?? '') ?>">
                </div>

                <!-- Devam eden alanlarda mobile özel olanlara mobile-field sınıfını ekle -->
                <div class="col-md-3 form-check">
                    <input class="form-check-input" type="checkbox" name="headerSocial" value="1" <?= !empty($headerSocial) ? 'checked' : '' ?>>
                    <label class="form-check-label">Header Sosyal</label>
                </div>
                <div class="col-md-3 form-check mobile-field">
                    <input class="form-check-input" type="checkbox" name="mobileHeaderSocial" value="1" <?= !empty($mobileHeaderSocial) ? 'checked' : '' ?>>
                    <label class="form-check-label">Mobile Header Sosyal</label>
                </div>
                <!-- diğer mobile alanlar da aynı şekilde mobile-field sınıfına sahip olacak -->

                <div class="col-12 mt-3">
                    <button class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
        </form>

    </div>


<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isMobileCheckbox = document.getElementById('isMobile');
            const mobileFields = document.querySelectorAll('.mobile-field');

            function toggleMobileFields() {
                mobileFields.forEach(el => {
                    el.style.display = isMobileCheckbox.checked ? '' : 'none';
                });
            }

            isMobileCheckbox.addEventListener('change', toggleMobileFields);
            toggleMobileFields(); // sayfa yüklenince uygula
        });
    </script>
<?= $this->endSection() ?>