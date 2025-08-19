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
    <form action="<?= site_url('admin/roles/create') ?>" method="post" class="form row g-3">
        <?= csrf_field() ?>
        <div class="card-body p-9">
            <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Rol Adı</div>
                </div>
                <div class="col-xl-4 fv-row">
                    <input type="text" class="form-control form-control-solid" name="name" placeholder="Süper Admin" />
                </div>
            </div>
            <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Açıklama</div>
                </div>
                <div class="col-xl-4 fv-row">
                    <input type="text" class="form-control form-control-solid" name="description" placeholder="Administrator" />
                </div>

                <h1 class="h4 mb-3">Kullanıcı Düzenle</h1>

                <form action="/admin/roles/permissions/<?= $role['id'] ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3 d-flex gap-2">
                        <button type="button" id="selectAll" class="btn btn-outline-primary btn-sm">Tümünü Seç</button>
                        <button type="button" id="deselectAll" class="btn btn-outline-secondary btn-sm">Tümünü Kaldır</button>
                        <a href="/admin/roles" class="btn btn-sm btn-secondary ms-auto">← Geri</a>
                    </div>

                    <?php
                    // 🔁 Yetkileri modüle göre gruplandır
                    $groupedPermissions = [];

                    foreach ($permissions as $perm) {
                        $slug = $perm['name'];
                        $parts = explode('.', $slug);
                        $group = $parts[0] ?? 'Diğer';
                        $groupedPermissions[$group][] = $perm;
                    }
                    ?>

                    <?php foreach ($groupedPermissions as $group => $groupPermissions): ?>
                        <div class="card mb-3 border-secondary">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                <strong><?= strtoupper($group) ?> Modülü</strong>
                                <div class="d-none d-md-block">
                                    <button type="button" class="btn btn-sm btn-light group-select" data-group="<?= $group ?>">Seç</button>
                                    <button type="button" class="btn btn-sm btn-light group-deselect" data-group="<?= $group ?>">Kaldır</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($groupPermissions as $perm): ?>
                                        <?php
                                        $permName = $perm['name'];
                                        $permId   = $perm['id'];
                                        $checked  = in_array($permId, $assignedPermissions) ? 'checked' : '';
                                        $suffix   = strtolower(pathinfo($permName, PATHINFO_EXTENSION) ?? '');

                                        // 🎨 Yetki türüne göre renk
                                        $badgeClass = match(true) {
                                            str_contains($permName, 'create') => 'success',
                                            str_contains($permName, 'edit')   => 'warning',
                                            str_contains($permName, 'delete') => 'danger',
                                            str_contains($permName, 'access') => 'info',
                                            default                           => 'secondary',
                                        };
                                        ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check border p-2 rounded bg-light">
                                                <input
                                                        class="form-check-input perm-check group-<?= $group ?>"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="<?= $permId ?>"
                                                        id="perm_<?= $permId ?>"
                                                    <?= $checked ?>
                                                >
                                                <label class="form-check-label" for="perm_<?= $permId ?>">
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= esc($permName) ?>
                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </div>
                </form>

                <script>
                    // Global tümünü seç / kaldır
                    document.getElementById('selectAll').addEventListener('click', () => {
                        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = true);
                    });

                    document.getElementById('deselectAll').addEventListener('click', () => {
                        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
                    });

                    // Grup bazlı seç / kaldır
                    document.querySelectorAll('.group-select').forEach(btn => {
                        btn.addEventListener('click', function () {
                            let group = this.dataset.group;
                            document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = true);
                        });
                    });

                    document.querySelectorAll('.group-deselect').forEach(btn => {
                        btn.addEventListener('click', function () {
                            let group = this.dataset.group;
                            document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = false);
                        });
                    });
                </script>

            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-light-success me-2">Discard</button>
            <a class="btn btn-light-danger" href="<?= site_url('admin/roles') ?>">İptal</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
