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
            <a href="<?= site_url('admin/users/new') ?>" class="btn btn-light-success fw-bold">+ Yeni</a>
            <a href="/dashboard" class="btn btn-light-danger">Geri</a>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Kullanıcı Listesi</h3>
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
                        <th class="text-center">Kullanıcı Adı</th>
                        <th class="text-center">E-posta</th>
                        <th class="text-center">Roller</th>
                        <th class="text-center">İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="text-center"><?= esc($u->id) ?></td>
                            <td class="text-center"><?= esc($u->username ?? '') ?></td>
                            <td class="text-center"><?= esc($emails[$u->id] ?? '') ?></td>
                            <?php $assigned = $userRolesMap[$u->id] ?? []; ?>
                            <td class="text-center">
                                <?php if ($assigned): ?>
                                    <?php foreach ($assigned as $r): ?>
                                        <span class="badge text-bg-secondary"><?= esc($r['name']) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-light-success" href="<?= site_url('admin/users/'.$u->id.'/edit') ?>">Düzenle</a>
                                <form action="<?= site_url('admin/users/'.$u->id.'/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Silinsin mi?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-light-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>