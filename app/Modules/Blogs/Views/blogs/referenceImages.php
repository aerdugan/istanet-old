<?= $this->extend('Views/admin/base') ?>

<?= $this->section('head') ?>
    <link href="<?= adminTheme(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/mare/checkbox.css">
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<meta name="csrf_token_name" content="<?= csrf_token() ?>">
<meta name="csrf_token_value" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= $title['module'] ?? '' ?></h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <?php foreach ($breadcrumb ?? [] as $crumb): ?>
                <?php if (!$crumb['active']) : ?>
                    <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary" href="<?= base_url($crumb['route']) ?>"><?= $crumb['title'] ?></a></li>
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
<form id="kt_ecommerce_add_product_form"
      data-url="<?= base_url("blogs/refresh_image_list/" . $item['id']) ?>"
      action="<?= base_url("blogs/image_upload/" . $item['id']) ?>"
      class="form d-flex flex-column flex-lg-row" enctype="multipart/form-data">
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title"><h2>Media</h2></div>
                </div>
                <div class="card-body pt-0">
                    <div class="fv-row mb-2">
                        <div class="dropzone" id="my_custom_dropzone">
                            <div class="dz-message needsclick">
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                    <span class="fs-7 fw-bold text-gray-400">Upload up to 10 files</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted fs-7">Set the product media gallery.</div>
                </div>
            </div>
        </div>
    </div>
</form>
<br>
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                <h2 class="card-title"><b><?= esc($item['title']) ?></b>&nbspkaydına ait Dosyalar</h2>
                <div class="d-flex mb-4">
                </div>
                <?php if (count($item_images) > 1): ?>
                    <div class="card-toolbar">
                        <button id="deleteAllImagesBtn" class="btn btn-sm btn-light-danger">Tüm Resimleri Sil</button>
                    </div>
                <?php endif; ?>
            </header>
            <div class="card-body image_list_container">
                <?= view('App\Modules\Blogs\Views\blogs\partials\image_list', ['item_images' => $item_images]) ?>
            </div>
        </section>
    </div>
</div>
<br>
<div class="d-flex justify-content-end">
    <a href="<?= base_url("blogs") ?>" class="btn btn-light-danger me-5">Referanslar</a>
    <a href="<?= base_url("blogs/updateForm/" . $item['id']) ?>" class="btn btn-light-primary me-5">Referansı Düzenle</a>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script src="<?= adminTheme(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script>

        let dropzone;
        document.addEventListener('DOMContentLoaded', function () {

            let csrfName = document.querySelector('meta[name="csrf_token_name"]').getAttribute('content');
            let csrfValue = document.querySelector('meta[name="csrf_token_value"]').getAttribute('content');

            function updateCsrf(newToken) {
                csrfName = newToken.token;
                csrfValue = newToken.hash;
                document.querySelector('meta[name="csrf_token_name"]').setAttribute('content', csrfName);
                document.querySelector('meta[name="csrf_token_value"]').setAttribute('content', csrfValue);
            }

            dropzone = new Dropzone("#my_custom_dropzone", {
                url: document.getElementById('kt_ecommerce_add_product_form').getAttribute('action'),
                paramName: "file",
                maxFiles: 10,
                maxFilesize: 5,
                acceptedFiles: 'image/jpeg,image/png,image/gif,image/webp',
                addRemoveLinks: true,
                uploadMultiple: false,
                parallelUploads: 10,
                init: function () {
                    this.on("sending", function (file, xhr, formData) {
                        formData.append(csrfName, csrfValue);
                    });
                    this.on("success", function (file, response) {
                        if (response.csrf) {
                            updateCsrf(response.csrf);
                        }
                        refreshImageList();
                    });
                }
            });

            function getCsrf() {
                return {
                    name: document.querySelector('meta[name="csrf_token_name"]').getAttribute('content'),
                    value: document.querySelector('meta[name="csrf_token_value"]').getAttribute('content')
                };
            }

            function updateCsrf(csrf) {
                document.querySelector('meta[name="csrf_token_name"]').setAttribute('content', csrf.token);
                document.querySelector('meta[name="csrf_token_value"]').setAttribute('content', csrf.hash);
            }

            function refreshImageList() {
                const refreshUrl = document.getElementById('kt_ecommerce_add_product_form').getAttribute('data-url');
                fetch(refreshUrl)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('.image_list_container').innerHTML = html;
                        attachAllEvents();
                    })
                    .catch(error => console.error('Liste yenileme hatası:', error));
            }

            function attachAllEvents() {
                attachDeleteEvents();
                attachCoverEvents();
                attachIsActiveEvents();
            }

            function attachDeleteEvents() {
                document.querySelectorAll('.remove-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const url = this.dataset.url;
                        const csrf = getCsrf();
                        Swal.fire({
                            title: 'Emin misin?',
                            text: 'Bu resmi silmek istiyor musun?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Evet',
                            cancelButtonText: 'İptal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new URLSearchParams();
                                formData.append(csrf.name, csrf.value);

                                fetch(url, {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                                    body: formData
                                })
                                    .then(r => r.json())
                                    .then(data => {
                                        updateCsrf(data.csrf);
                                        if (data.status === 'success') {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Silindi!',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });
                                            refreshImageList();
                                        }
                                    });
                            }
                        });
                    });
                });
            }

            function attachCoverEvents() {
                document.querySelectorAll('.isCover').forEach(input => {
                    input.addEventListener('change', function () {
                        const url = this.dataset.url;
                        const csrf = getCsrf();
                        const formData = new URLSearchParams();
                        formData.append(csrf.name, csrf.value);

                        fetch(url, {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                            body: formData
                        })
                            .then(r => r.json())
                            .then(data => {
                                updateCsrf(data.csrf);
                                if (data.status === 'success') {
                                    Swal.fire('Kapak Resmi Ayarlandı!', '', 'success');
                                    window.location.reload();
                                }
                            });
                    });
                });
            }

            function attachIsActiveEvents() {
                document.querySelectorAll('.isActive').forEach(input => {
                    input.addEventListener('change', function () {
                        const url = this.dataset.url;
                        const csrf = getCsrf();
                        const isChecked = this.checked ? 1 : 0;
                        const formData = new URLSearchParams();
                        formData.append('isActive', isChecked);
                        formData.append(csrf.name, csrf.value);

                        fetch(url, {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                            body: formData
                        })
                            .then(r => r.json())
                            .then(data => {
                                updateCsrf(data.csrf);
                                if (data.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Durum Değiştirildi!',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    refreshImageList();
                                }
                            });
                    });
                });
            }

            document.getElementById('deleteAllImagesBtn').addEventListener('click', function() {
                const csrf = getCsrf();
                Swal.fire({
                    title: 'Tüm resimleri silmek istediğine emin misin?',
                    text: "Bu işlem geri alınamaz!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, Hepsini Sil',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new URLSearchParams();
                        formData.append(csrf.name, csrf.value);

                        fetch('<?= base_url("blogs/deleteAllImages/" . $item['id']) ?>', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                            body: formData
                        })
                            .then(r => r.json())
                            .then(data => {
                                updateCsrf(data.csrf);
                                if (data.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Tüm Resimler Silindi!',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            });
                    }
                });
            });

            attachAllEvents(); // Sayfa ilk açılışta hepsini bağla        });

        });

    </script>
<?= $this->endSection() ?>