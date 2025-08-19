<?= $this->extend('Views/admin/base') ?>

<?= $this->section('head') ?>
<link href="<?= adminTheme(); ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= $title['module'] ?? '' ?></h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <?php foreach ($breadcrumb ?? [] as $crumb): ?>
                <?php if (!$crumb['active']): ?>
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary" href="<?= base_url($crumb['route']) ?>"><?= $crumb['title'] ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item text-muted active"><?= $crumb['title'] ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form action="<?= base_url("blogs/update/" . $item['id']) ?>" class="form d-flex flex-column flex-lg-row" method="post">
    <?= csrf_field() ?>
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
            <div class="d-flex flex-column gap-7 gap-lg-10">

                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Proje Adı</label>
                            <input type="text" name="title" class="form-control mb-2 form-control-solid" value="<?= esc($item['title']) ?>" />
                        </div>
                        <div>
                            <label class="form-label">Proje Açıklaması</label>
                            <textarea id="desc" name="description" class="tox-target"><?= esc($item['description']) ?></textarea>
                        </div>
                    </div>

                    <div class="separator"></div>

                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Proje Anahtar Kelimeler</label>
                            <input type="text"
                                   id="exist-values"
                                   name="seoKeywords"
                                   class="form-control form-control-sm form-control-solid"
                                   value='<?= esc($item['seoKeywords'], 'html') ?>'
                                   placeholder="Etiket ekleyin">
                        </div>
                        <div>
                            <label class="form-label">Proje Seo Açıklaması</label>
                            <textarea class="form-control form-control-solid" name="seoDesc" rows="4" maxlength="320"><?= esc($item['seoDesc']) ?></textarea>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex flex-wrap gap-5">
                            <div class="fv-row w-100 flex-md-root">
                                <label class="required form-label">Proje Yeri</label>
                                <input type="text" name="location" class="form-control mb-2 form-control-solid" value="<?= esc($item['location']) ?>" />
                            </div>
                            <div class="fv-row w-100 flex-md-root">
                                <label class="required form-label">Yapım Yılı</label>
                                <input type="text" name="year" class="form-control mb-2 form-control-solid" value="<?= esc($item['year']) ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Proje Ayarları</h2>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex flex-wrap gap-5">
                            <div class="fv-row w-100 flex-md-root">
                                <label class="required form-label">Proje Kategorisi</label>
                                <select class="form-select mb-2 form-control-solid" name="category_id" data-control="select2" data-hide-search="true" data-placeholder="Seçiniz">
                                    <?php foreach (getProjectCategories() as $category): ?>
                                        <option value="<?= $category->id ?>" <?= ($category->id === $item['category_id']) ? 'selected' : '' ?>>
                                            <?= esc($category->title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="fv-row w-100 flex-md-root">
                                <label class="required form-label">Görüntülenecek Fotoğraf Sayısı</label>
                                <select class="form-select mb-2 form-control-solid" name="picturePrice" data-control="select2" data-hide-search="true" data-placeholder="Seçiniz">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($item['picturePrice'] == $i) ? 'selected' : '' ?>><?= $i ?> Fotoğraf</option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url("blogs") ?>" class="btn btn-light me-5">İptal</a>
            <button type="submit" class="btn btn-success">
                <span class="indicator-label">Güncelle</span>
            </button>
        </div>

    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="<?= adminTheme(); ?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#desc'), {
            // Config ayarları
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('exist-values');
        new Tagify(input, {
            dropdown: { enabled: 0 }
        });
    });
</script>

<?= sweetAlert() ?>
<?= $this->endSection() ?>
