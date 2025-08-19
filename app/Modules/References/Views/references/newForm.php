<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
    <link href="<?= adminTheme(); ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= $title['module']??'' ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <?php foreach ($breadcrumb??[] as $item) : ?>
                    <?php if (!$item['active']) : ?>
                        <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary" href="<?= base_url($item['route']) ?>"><?= $item['title'] ?></a></li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                    <?php else : ?>
                        <li class="breadcrumb-item text-muted active"><?= $item['title'] ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <form action="<?php echo base_url("references/store"); ?>" class="form d-flex flex-column flex-lg-row" method="post">
        <?= csrf_field() ?>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Proje Adı</label>
                                <input type="text" name="title" class="form-control form-control-solid" placeholder="Product name" />
                            </div>
                            <div>
                                <label class="form-label">Proje Açıklaması</label>
                                <textarea id="desc" name="description" class="tox-target"></textarea>

                            </div>
                        </div>
                        <div class="sperator"></div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Proje Anahtar Kelimeler</label>
                                <input type="text"
                                       id="exist-values"
                                       name="seoKeywords"
                                       class="form-control form-control-sm form-control-solid'
                                       placeholder="Etiket ekleyin">
                            </div>
                            <div>
                                <label class="form-label">Proje Seo Açıklaması</label>
                                <textarea class="form-control form-control-solid" name="seoDesc" rows="4" data-plugin-maxlength maxlength="320"></textarea>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-wrap gap-5">
                                <div class="fv-row w-100 flex-md-root">
                                    <label class="required form-label">Proje Yeri</label>
                                    <input type="text" name="location" class="form-control mb-2 form-control-solid" placeholder="İstanbul" />
                                </div>
                                <div class="fv-row w-100 flex-md-root">
                                    <label class="required form-label">Yapım Yılı</label>
                                    <input type="text" name="year" class="form-control mb-2 form-control-solid" placeholder="2023"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Proje Ayarları</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-wrap gap-5">
                                <div class="fv-row w-100 flex-md-root">
                                    <label class="required form-label">Proje Kategorisi</label>
                                    <select class="form-select mb-2" name="category_id" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <?php foreach(getProjectCategories() as $category) { ?>
                                            <option value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fv-row w-100 flex-md-root">
                                    <label class="required form-label">Görüntülenecek Fotoğraf Sayısı</label>
                                    <select class="form-select mb-2" name="picturePrice" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                        <option value="1">1 Fotoğraf</option>
                                        <option value="2">2 Fotoğraf</option>
                                        <option value="3">3 Fotoğraf</option>
                                        <option value="4">4 Fotoğraf</option>
                                        <option value="5">5 Fotoğraf</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?php echo base_url("references") ?>" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">İptal</a>
                <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-success">
                    <span class="indicator-label">Güncelle</span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script src="<?= adminTheme(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?= adminTheme(); ?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>

    <script>
        ClassicEditor
            .create( document.querySelector( '#desc' ), {
                // config ayarları
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('exist-values');
            new Tagify(input, {
                // İstersen buraya ayarlar ekleyebilirsin
                dropdown: {
                    enabled: 0 // otomatik açılmasın
                }
            });
        });
    </script>
<?= sweetAlert() ?>
<?= $this->endSection() ?>