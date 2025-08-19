<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
    <link href="<?= adminTheme(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/mare/checkbox.css">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <meta name="csrf_token_name" content="<?= csrf_token() ?>">
    <meta name="csrf_token_value" content="<?= csrf_hash() ?>">
    <style>
        .table td, .table th {
            vertical-align: middle !important;
        }
    </style>
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
    action="<?= base_url("references/image_upload/" . $item['id']) ?>"
    data-url="<?= base_url("references/refresh_image_list/" . $item['id']) ?>"
    class="form" method="post" enctype="multipart/form-data">
    <div class="dropzone" id="my_custom_dropzone"></div>

    <div class="mt-5 text-end">
        <button id="btn-upload-original" class="btn btn-light-primary me-2" type="button">Orijinal Yükle</button>
        <button id="btn-upload-webp" class="btn btn-light-info me-2" type="button">WebP Yükle</button>
        <button id="btn-upload-webp-resize" class="btn btn-light-success" type="button">WebP + Resize Yükle</button>
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
                <?= view('App\Modules\References\Views\references\partials\image_list', ['item_images' => $item_images]) ?>
            </div>
        </section>
    </div>
</div>
<br>
<div class="d-flex justify-content-end">
    <a href="<?= base_url("references") ?>" class="btn btn-light-danger me-5">Referanslar</a>
    <a href="<?= base_url("references/updateForm/" . $item['id']) ?>" class="btn btn-light-primary me-5">Referansı Düzenle</a>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
    <script src="<?= adminTheme();?>assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
    <script src="<?= adminTheme();?>assets/plugins/global/plugins.bundle.js"></script>
    <script>
        let dropzone;
        let uploadBaseUrl = "";
        let uploadMode = "original";
        let formEl;
        let csrfName = "";
        let csrfValue = "";

        window.addEventListener("DOMContentLoaded", () => {
            formEl = document.getElementById("kt_ecommerce_add_product_form");
            uploadBaseUrl = formEl.getAttribute("action");
            const refreshUrl = formEl.getAttribute("data-url");

            csrfName = document.querySelector('meta[name="csrf_token_name"]').getAttribute("content");
            csrfValue = document.querySelector('meta[name="csrf_token_value"]').getAttribute("content");

            function updateCsrf(newToken) {
                csrfName = newToken.token;
                csrfValue = newToken.hash;
                document.querySelector('meta[name="csrf_token_name"]').setAttribute("content", csrfName);
                document.querySelector('meta[name="csrf_token_value"]').setAttribute("content", csrfValue);
            }

            function getCsrf() {
                return { name: csrfName, value: csrfValue };
            }

            Dropzone.autoDiscover = false;

            dropzone = new Dropzone("#my_custom_dropzone", {
                url: uploadBaseUrl + "?mode=" + uploadMode,
                method: "post",
                paramName: "file",
                autoProcessQueue: false,
                maxFiles: 10,
                maxFilesize: 5,
                parallelUploads: 10, // ✅ Tüm dosyaları aynı anda yüklemek için
                acceptedFiles: "image/jpeg,image/png,image/gif,image/webp",
                addRemoveLinks: true,
                init: function () {
                    this.on("sending", function (file, xhr, formData) {
                        formData.append(csrfName, csrfValue);
                    });

                    this.on("success", function (file, response) {
                        if (response.csrf) updateCsrf(response.csrf);
                    });

                    this.on("queuecomplete", function () {
                        refreshImageList(); // ✅ Sadece tüm dosyalar yüklendikten sonra listeyi yenile
                        this.removeAllFiles(); // (İsteğe bağlı) Dropzone temizlensin
                    });
                }
            });

            document.getElementById("btn-upload-original")?.addEventListener("click", () => {
                uploadMode = "original";
                dropzone.options.url = uploadBaseUrl + "?mode=" + uploadMode;
                dropzone.processQueue();
            });

            document.getElementById("btn-upload-webp")?.addEventListener("click", () => {
                uploadMode = "webp";
                dropzone.options.url = uploadBaseUrl + "?mode=" + uploadMode;
                dropzone.processQueue();
            });

            document.getElementById("btn-upload-webp-resize")?.addEventListener("click", () => {
                uploadMode = "webp_resize";
                dropzone.options.url = uploadBaseUrl + "?mode=" + uploadMode;
                dropzone.processQueue();
            });

            function refreshImageList() {
                fetch(refreshUrl)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector(".image_list_container").innerHTML = html;
                        attachAllEvents(); // eksikti!
                    })
                    .catch(error => console.error("Liste yenileme hatası:", error));
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
                                        if (data.csrf) updateCsrf(data.csrf);
                                        if (data.status === 'success') {
                                            Swal.fire({ icon: 'success', title: 'Silindi!', showConfirmButton: false, timer: 1000 });
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
                                if (data.csrf) updateCsrf(data.csrf);
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
                                if (data.csrf) updateCsrf(data.csrf);
                                if (data.status === 'success') {
                                    Swal.fire({ icon: 'success', title: 'Durum Değiştirildi!', showConfirmButton: false, timer: 1000 });
                                    refreshImageList();
                                }
                            });
                    });
                });
            }

            const deleteAllBtn = document.getElementById('deleteAllImagesBtn');
            if (deleteAllBtn) {
                deleteAllBtn.addEventListener('click', function () {
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

                            fetch(formEl.getAttribute('data-url').replace('refresh_image_list', 'deleteAllImages'), {
                                method: 'POST',
                                headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                                body: formData
                            })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.csrf) updateCsrf(data.csrf);
                                    if (data.status === 'success') {
                                        Swal.fire({ icon: 'success', title: 'Tüm Resimler Silindi!', showConfirmButton: false, timer: 1000 });
                                        setTimeout(() => { window.location.reload(); }, 1000);
                                    }
                                });
                        }
                    });
                });
            }


            attachAllEvents();
        });


    </script>
<?= $this->endSection() ?>