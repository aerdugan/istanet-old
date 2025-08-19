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
            <h3 class="card-title">"<?= esc($role['name']) ?>" Rolü için Yetki Atama</h3>
            <div class="card-toolbar gap-2">
                <button type="button" id="selectAll" class="btn btn-sm btn-light-success">Tümünü Seç</button>
                <button type="button" id="deselectAll" class="btn btn-sm btn-light-danger">Tümünü Kaldır</button>
                <a href="/admin/roles" class="btn btn-sm btn-light-info">← Geri</a>
            </div>
        </div>
        <div class="card-body">

            <form action="/admin/roles/permissions/<?= $role['id'] ?>" method="post">
                <?= csrf_field() ?>


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
                <?php
                // Grup slug helper (sadece bu görünüm dosyasında)
                $slug = static function(string $s): string {
                    return strtolower(preg_replace('/[^a-z0-9]+/i', '-', $s));
                };
                ?>

                <?php foreach ($groupedPermissions as $group => $groupPermissions): ?>
                    <?php $groupSlug = $slug($group); ?>
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title"><?= strtoupper($group) ?> Modülü</h3>
                            <div class="card-toolbar gap-2">
                                <!-- ÖNEMLİ: class ve data-group eklendi -->
                                <button type="button"
                                        class="btn btn-sm btn-light-success group-select"
                                        data-group="<?= esc($groupSlug) ?>">Seç</button>
                                <button type="button"
                                        class="btn btn-sm btn-light-danger group-deselect"
                                        data-group="<?= esc($groupSlug) ?>">Kaldır</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($groupPermissions as $perm): ?>
                                    <?php
                                    $permName = $perm['name'];
                                    $permId   = $perm['id'];
                                    $checked  = in_array($permId, $assignedPermissions ?? [], true) ? 'checked' : '';
                                    $badgeClass = match(true) {
                                        str_contains($permName, 'create'),str_contains($permName, 'save') => 'success',
                                        str_contains($permName, 'edit'), str_contains($permName, 'update') => 'warning',
                                        str_contains($permName, 'delete'), str_contains($permName, 'destroy') => 'danger',
                                        str_contains($permName, 'access'), str_contains($permName, 'index'), str_contains($permName, 'show') => 'info',
                                        default => 'secondary',
                                    };
                                    ?>
                                    <div class="col-3 mt-3">
                                        <div class="form-check">
                                            <input
                                                    class="form-check-input perm-check group-<?= esc($groupSlug) ?>"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="<?= esc($permId) ?>"
                                                    id="perm_<?= esc($permId) ?>"
                                                <?= $checked ?>
                                            >
                                            <label class="form-check-label" for="perm_<?= esc($permId) ?>">
                                                <span class="badge badge-light-<?= esc($badgeClass) ?>"><?= esc($permName) ?></span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <br>
                <?php endforeach ?>
                <div class="d-flex justify-content-end pt-7">
                    <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2" data-kt-search-element="preferences-dismiss">Cancel</button>
                    <button type="submit" class="btn btn-success">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Global tümünü seç/kaldır
            const selectAllBtn = document.getElementById('selectAll');
            const deselectAllBtn = document.getElementById('deselectAll');

            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', () => {
                    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = true);
                });
            }
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', () => {
                    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
                });
            }

            // Grup bazlı seç/kaldır (class + data-group kesin eşleşiyor)
            document.querySelectorAll('.group-select').forEach(btn => {
                btn.addEventListener('click', function () {
                    const group = this.dataset.group;
                    document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = true);
                });
            });
            document.querySelectorAll('.group-deselect').forEach(btn => {
                btn.addEventListener('click', function () {
                    const group = this.dataset.group;
                    document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = false);
                });
            });
        });
    </script>
<?= $this->endSection() ?>