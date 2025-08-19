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
        <a href="<?= site_url('admin/roles/new') ?>" class="btn btn-light-success fw-bold">+ Yeni</a>
        <form action="<?= route_to('admin.roles.scanperms') ?>" method="post" onsubmit="return confirm('Scan + (opsiyonel) prune çalıştırılsın mı?')">
            <?= csrf_field() ?>
            <input type="hidden" name="prune" value="1"> <!-- prune açık -->
            <button class="btn btn-light-info" type="submit" title="Scan & Prune">Scan Permissions</button>
        </form>
        <a href="/dashboard" class="btn btn-light-danger">Geri</a>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Kullanıcı Rolleri</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-hover table-rounded table-striped border gy-7 gs-7">
                <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                    <th class="text-center">#</th>
                    <th class="text-center">Ad</th>
                    <th class="text-center">Açıklama</th>
                    <th class="text-center">İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($roles as $r): ?>
                    <tr>
                        <td class="text-center"><?= esc($r['id']) ?></td>
                        <td class="text-center"><?= esc($r['name']) ?></td>
                        <td class="text-center"><?= esc($r['description'] ?? '') ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-light-warning" href="<?= site_url('admin/roles/'.$r['id'].'/edit') ?>">Düzenle</a>
                            <form action="<?= site_url('admin/roles/'.$r['id'].'/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-light-danger">Sil</button>
                            </form>
                            <a href="/admin/roles/permissions/<?= $r['id'] ?>" class="btn btn-sm btn-info">Yetkiler</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
