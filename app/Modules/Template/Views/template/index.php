<?= $this->extend('layout/main') ?>
<?= $this->section('pageStyles') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Tasarım Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Tasarım Ayarları</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card mb-5 mb-xxl-8">
    <div class="card-body pt-5 pb-5">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active" href="/admin/template">Genel Ayarları</a>
            </li>
            <li class="nav-item" style="display: <?= ($row['siteStatus'] == "1") ? 'block' : 'none' ?>;">
                <a class="nav-link text-active-primary me-6" href="/admin/template/header">Header Ayarları</a>
            </li>
            <li class="nav-item" style="display: <?= ($row['siteStatus'] == "1") ? 'block' : 'none' ?>;">
                <a class="nav-link text-active-primary me-6" href="/admin/template/footer">Footer Ayarlar</a>
            </li>
            <li class="nav-item" style="display: <?= ($row['siteStatus'] == "1") ? 'block' : 'none' ?>;">
                <a class="nav-link text-active-primary me-6" href="/admin/template/breadCrumb">BreadCrumb Ayarları</a>
            </li>
            <li class="nav-item" style="display: <?= ($row['siteStatus'] == "1") ? 'block' : 'none' ?>;">
                <a class="nav-link text-active-primary me-6"  href="/admin/template/slider">Slider Ayarları</a>
            </li>
        </ul>
    </div>
</div>
<div class="card shadow-sm mb-10" id="siteStatus">
    <div class="card-header">
        <h3 class="card-title">Site Durumu</h3>
        <div class="card-toolbar">
            <select name="siteStatus" id="siteStatusSelect3" class="form-select form-select-solid mb-2">
                <option value="1" <?php echo ($row['siteStatus'] === "1") ? "selected" : ""; ?>>Site Aktif</option>
                <option value="2" <?php echo ($row['siteStatus'] === "2") ? "selected" : ""; ?>>Site Pasif</option>
            </select>
        </div>
    </div>
</div>







<div class="card shadow-sm mb-10" id="siteClosed" style="display: <?= ($row['siteStatus'] == "2") ? 'block' : 'none' ?>;">
    <div class="card-header">
        <h3 class="card-title">Site Yayında Değil</h3>
    </div>
    <div class="card-body">
        <form class="" action="" method="post">
        <div class="row mb-6">
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">Tasarım</div>
            </div>
            <div class="col-xl-3">
                <select name="siteDisableTheme" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                    <option></option>
                    <option value="1" <?php echo ($row['siteDisableTheme'] === "1") ? "selected" : ""; ?>>Style 1</option>
                    <option value="2" <?php echo ($row['siteDisableTheme'] === "2") ? "selected" : ""; ?>>Style 2</option>
                    <option value="3" <?php echo ($row['siteDisableTheme'] === "3") ? "selected" : ""; ?>>Style 3</option>
                    <option value="4" <?php echo ($row['siteDisableTheme'] === "4") ? "selected" : ""; ?>>Style 4</option>
                    <option value="5" <?php echo ($row['siteDisableTheme'] === "5") ? "selected" : ""; ?>>Style 5</option>
                </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-xl-3">
                <div class="fs-6 fw-bold mt-2 mb-3">Site Durum Mesajınız </div>
            </div>
            <div class="col-xl-9">
                <textarea name="siteCloseMessage" id="siteCloseMessage"><?php echo $row['siteCloseMessage'] ;?></textarea>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-10" id="siteOpen" style="display: <?= ($row['siteStatus'] == "1") ? 'block' : 'none' ?>;">
    <div class="card-header">
        <h3 class="card-title">Site Yayında</h3>
    </div>
    <div class="card-body">
        <form class="form" action="/admin/template/templateSettingsUpdate" method="post">
            <?= csrf_field() ?>
            <div class="form-group row align-items-center pb-3">
                <div class="col-6">
                    <style>
                        .form-control-color {width: 4rem !important;}
                    </style
                    <label class="col-form-label" for="exampleColorInput">Theme Renk 1</label>
                    <input type="color" class="form-control form-control-color" name="colorCode" id="exampleColorInput" value="<?= $row['colorCode'] ?>" title="Choose your color">
                </div>
                <div class="col-6">
                    <style>
                        .form-control-color {width: 4rem !important;}
                    </style
                    <label class="col-form-label" for="exampleColorInput">Theme Renk 2</label>
                    <input type="color" class="form-control form-control-color" name="colorCode2" id="exampleColorInput" value="<?= $row['colorCode2'] ?>" title="Choose your color">
                </div>
            </div>
            <div class="card mb-5 mb-xl-8" style="padding: 20px">
                <div class="form-group row align-items-center pb-3">
                    <div class="col-lg-12 col-xl-3">
                        <label class="col-form-label" for="setFooter">Header</label>
                        <select name="setHeader" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <?php foreach ($headerFiles as $file1): ?>
                                <option value="<?= $file1 ?>" <?= ($file1 === $row['setHeader']) ? 'selected' : '' ?>>
                                    <?= $file1 ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-3">
                        <label class="col-form-label">Logo</label>
                        <select name="setLogo" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['setLogo'] === "1") ? "selected" : ""; ?>>1. Logo</option>
                            <option value="2" <?php echo ($row['setLogo'] === "2") ? "selected" : ""; ?>>2. Logo</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-3">
                        <label class="col-form-label">Social</label>
                        <select name="headerSocial" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['headerSocial'] === "1") ? "selected" : ""; ?>>Aktif</option>
                            <option value="2" <?php echo ($row['headerSocial'] === "2") ? "selected" : ""; ?>>Pasif</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-3">
                        <label class="col-form-label">Dil</label>
                        <select name="headerLang" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['headerLang'] === "1") ? "selected" : ""; ?>>Aktif</option>
                            <option value="2" <?php echo ($row['headerLang'] === "2") ? "selected" : ""; ?>>Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8" style="padding: 20px">
                <div class="form-group row align-items-center pb-3">
                    <div class="col-lg-12 col-xl-4">
                        <label class="col-form-label" for="setFooter">Footer</label>
                        <select name="setFooter" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <?php foreach ($footerFiles as $file1): ?>
                                <option value="<?= $file1 ?>" <?= ($file1 === $row['setFooter']) ? 'selected' : '' ?>>
                                    <?= $file1 ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-4">
                        <label class="col-form-label" for="setFooterLogo">Logo</label>
                        <select name="setFooterLogo" class="form-select form-select-solid mb-2" id="setFooterLogo" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['setFooterLogo'] === "1") ? "selected" : ""; ?>>1. Logo</option>
                            <option value="2" <?php echo ($row['setFooterLogo'] === "2") ? "selected" : ""; ?>>2. Logo</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-4">
                        <label class="col-form-label" for="headerSocial">Social</label>
                        <select name="footerSocial" class="form-select form-select-solid mb-2" id="footerSocial" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['footerSocial'] === "1") ? "selected" : ""; ?>>Aktif</option>
                            <option value="2" <?php echo ($row['footerSocial'] === "2") ? "selected" : ""; ?>>Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8" style="padding: 20px">
                <div class="form-group row align-items-center pb-3">
                    <div class="col-lg-12 col-xl-4">
                        <label class="col-form-label" for="setFooter">Slider</label>
                        <select name="setSlider" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <?php foreach ($sliderFolders as $file1): ?>
                                <option value="<?= $file1 ?>" <?= ($file1 === $row['setSlider']) ? 'selected' : '' ?>>
                                    <?= $file1 ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-12 col-xl-4">
                        <label class="col-form-label" for="setFooter">Breadcrumb</label>
                        <select name="setBreadcrumb" class="form-select form-select-solid mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <?php foreach ($breadcrumbFiles as $file1): ?>
                                <option value="<?= $file1 ?>" <?= ($file1 === $row['setBreadcrumb']) ? 'selected' : '' ?>>
                                    <?= $file1 ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-xl-4">
                        <label class="col-form-label" for="setBreadcrumbStatus">Breadcrumb Durum</label>
                        <select name="setBreadcrumbStatus" class="form-select form-select-solid mb-2" id="setBreadcrumbStatus" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                            <option value="1" <?php echo ($row['setBreadcrumbStatus'] === "1") ? "selected" : ""; ?>>Aktif</option>
                            <option value="2" <?php echo ($row['setBreadcrumbStatus'] === "2") ? "selected" : ""; ?>>Pasif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8" style="padding: 20px">
                <div class="form-group row align-items-center pb-3">
                    <div class="col-lg-12">
                        <label class="col-form-label" for="copyright">Copyright</label>
                        <input class="form-control form-control-solid"  type="text" id="copyright" name="copyright" value="<?= $row['copyright'] ?>" />
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary"><?php echo lang("update") ?></button>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?= adminTheme(); ?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const siteStatusSelect = document.getElementById('siteStatusSelect3');

        if (siteStatusSelect) {
            siteStatusSelect.addEventListener('change', function () {
                const siteStatus = this.value;

                // Ajax isteği ile site durumunu kaydet
                fetch('/admin/template/updateSiteStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({ siteStatus })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // SweetAlert ile başarı mesajı göster
                            Swal.fire({
                                title: 'Başarılı!',
                                text: 'Site durumu başarıyla güncellendi.',
                                icon: 'success',
                                confirmButtonText: 'Tamam'
                            }).then(() => {
                                // DOM'u yenile
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: data.message || 'Bir hata oluştu.',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir hata oluştu.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    });
            });
        }
    });

    ClassicEditor
        .create(document.querySelector('#siteCloseMessage'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });

</script>
<?= $this->endSection() ?>
