<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Sosyal Medya Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Sosyal Medya Ayarları</li>
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
                <a class="nav-link text-active-primary me-6" href="/admin/general">Firma Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/contact">İletişim Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/other">Diğer Ayarlar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/logo">Logo & Favicon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6"  href="/admin/general/seo">Seo Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active" href="/admin/general/socials">Sosyal Medya Ayarları</a>
            </li>
        </ul>
    </div>
</div>
<div class="card mb-5 mb-xl-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><?= esc($title ?? 'Sosyal Bağlantılar') ?></h3>
            <div class="card-toolbar"></div>
        </div>
        <div class="card-body">
            <form action="<?= route_to('backend.settings.socials.save') ?>" method="post" id="socials-form">
                <?= csrf_field() ?>

                <div id="kt_docs_repeater_basic">
                    <div class="form-group">
                        <div data-repeater-list="socials">
                            <?php if (!empty($socials)): ?>
                                <?php foreach ($socials as $row): ?>
                                    <div data-repeater-item class="mt-5">
                                        <div class="form-group row align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label">Social Platform Name</label>
                                                <input type="text" class="form-control mb-2 mb-md-0"
                                                       name="platform" value="<?= esc($row['platform'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Social Platform Icon</label>
                                                <input type="text" class="form-control mb-2 mb-md-0"
                                                       name="icon" value="<?= esc($row['icon'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Social Platform Username</label>
                                                <input type="text" class="form-control mb-2 mb-md-0"
                                                       name="username" value="<?= esc($row['username'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Social Platform URL</label>
                                                <input type="text" class="form-control mb-2 mb-md-0"
                                                       name="url" value="<?= esc($row['url'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete
                                                   class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                    <i class="ki-duotone ki-trash fs-5"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- İlk boş item -->
                                <div data-repeater-item class="mt-7">
                                    <div class="form-group row align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label">Social Platform Name</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="platform" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Social Platform Icon</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="icon" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Social Platform Username</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="username" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Social Platform URL</label>
                                            <input type="text" class="form-control mb-2 mb-md-0" name="url" />
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:;" data-repeater-delete
                                               class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                <i class="ki-duotone ki-trash fs-5"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mt-5">
                        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            Add
                        </a>
                    </div>
                </div>

                <div class="mt-7">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="<?= route_to('backend.dashboard.index') ?>" class="btn btn-light">Vazgeç</a>
                </div>
            </form>

        </div>
    </div>
</div>


<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?=adminTheme()?>/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>

<script>
    // jQuery ve jquery.repeater yüklü olmalı
    $('#kt_docs_repeater_basic').repeater({
        initEmpty: false,
        defaultValues: { },
        show: function () { $(this).slideDown(); },
        hide: function (deleteElement) { $(this).slideUp(deleteElement); }
    });
</script>
<?= $this->endSection() ?>
