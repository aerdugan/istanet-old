<?= $this->extend('Views/admin/base') ?>

<?= $this->section('head') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Seo Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="<?= site_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Seo Ayarları</li>
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
                <a class="nav-link text-active-primary me-6" href="/general">Firma Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/general/contact">İletişim Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/general/other">Diğer Ayarlar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/general/logo">Logo & Favicon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active"  href="/general/seo">Seo Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 " href="/general/socials">Sosyal Medya Ayarları</a>
            </li>
        </ul>
    </div>
</div>

<div class="card mb-5 mb-xl-5">
    <form action="<?= site_url('general/seoSave') ?>" class="form" method="post">
        <?= csrf_field() ?>
        <div class="card-body border-top p-9">
            <div class="row mb-6">
                <div class="col-xl-3">
                    <div class="fs-6 fw-bold mt-2 mb-3 text-muted">Site Genel Açıklama</div>
                </div>
                <div class="col-xl-9">
                    <textarea class="form-control form-control-solid" name="siteDesc" rows="4" data-plugin-maxlength maxlength="320"><?= esc($item->siteDesc ?? '') ?></textarea>
                </div>
            </div>

            <div class="row mb-6">
                <div class="col-xl-3">
                    <div class="fs-6 fw-bold mt-2 mb-3 text-muted">Site Genel Anahtar Kelimeleri</div>
                </div>
                <div class="col-xl-9 mb-10">
                    <input type="text"
                           id="kt_tagify_1"
                           name="siteKeywords"
                           class="form-control form-control-sm form-control-solid"
                           value='<?= esc($item->siteKeywords, 'html') ?>'
                           placeholder="Etiket ekleyin">

                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var input1 = document.querySelector("#kt_tagify_1");
        if (input1) {
            new Tagify(input1);
        }

        <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            icon: '<?= session()->getFlashdata('alert-type') ?>', // success, error, warning, info
            title: '<?= session()->getFlashdata('message') ?>',
            showConfirmButton: false,
            timer: 3000
        });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>
