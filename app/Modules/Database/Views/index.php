<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= lang("App.download_sql")?></h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary"><?= lang("App.home") ?></a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted"><?= lang("App.download_sql")?></li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-flush">
    <div class="align-items-center py-5 gap-2 gap-md-5"></div>

    <?php if (!empty($successMessage)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '<?= lang('App.success') ?>',
                text: '<?= $successMessage ?>',
                confirmButtonText: '<?= lang('App.confirm') ?>'
            });
        </script>
    <?php endif; ?>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
            <thead>
            <tr>
                <th><?= lang('App.table_name') ?></th>
                <th><?= lang('App.row_count') ?></th>
                <th><?= lang('App.download') ?></th>
                <th><?= lang('App.truncate') ?></th>
                <th><?= lang('App.upload') ?></th>
            </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600" id="sortable-list">
            <?php foreach ($tables as $table): ?>
                <tr>
                    <td><?= $table['table_name'] ?></td>
                    <td><?= $table['row_count'] ?></td>
                    <td><a href="/admin/database/downloadSQL/<?= $table['table_name'] ?>"><?= lang('App.download_sql') ?></a></td>
                    <td><a href="/admin//database/truncate/<?= $table['table_name'] ?>"><?= lang('App.truncate_sql') ?></a></td>
                    <td>
                        <form action="/admin//database/uploadSQL" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="tableName" value="<?= $table['table_name'] ?>">
                            <input type="file" name="sqlFile">
                            <button type="submit" class="btn btn-sm btn-bg-primary text-white"><?= lang('App.upload_sql') ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<?= $this->endSection() ?>
