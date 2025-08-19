<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Kullanıcı Rolleri</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Anasayfa</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Kullanıcı İşlemleri</li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Kullanıcı Rolleri</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="/admin/roles/" class="btn btn-light-danger">Geri</a>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title fs-3 fw-bold">Project Settings</div>
        </div>
        <form action="<?= site_url('admin/users/create') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="card-body p-9">
                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Rol Adı</div>
                    </div>
                    <div class="col-xl-4 fv-row">
                        <input type="email" name="email" class="form-control form-control-solid" required placeholder="mail@domain.com">
                    </div>
                </div>
                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Açıklama</div>
                    </div>
                    <div class="col-xl-4 fv-row">
                        <input type="text" name="username" class="form-control form-control-solid" placeholder="Username">
                    </div>
                </div>
                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Roller</div>
                    </div>
                    <div class="col-xl-8 fv-row">
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach ($roles as $r): ?>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?= esc($r['id']) ?>">
                                    <span class="form-check-label"><?= esc($r['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-light-success me-2">Discard</button>
                <a class="btn btn-light-danger" href="<?= site_url('admin/users') ?>">İptal</a>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>