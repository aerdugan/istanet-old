<?= $this->extend('layout/main') ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="<?= site_url('mare/checkbox.css') ?>">
    <style>
        .modal-files {
            width: 1530px; height: 850px; display: none; position: fixed;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: #fff; border-radius: 6px; box-shadow: 0 15px 30px rgba(95,95,95,.39); z-index: 99999;
        }
        .modal-files iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:none; }
        .preview img { max-width:300px; max-height:100px; }
        /* checkbox'u görünmez yap ama tıklanabilir kalsın */
        .toggle-isActive{ position:absolute; opacity:0; width:0; height:0; }
    </style>
<?= $this->endSection() ?>

<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                <?= lang("App.menu_quality_certificates")?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= site_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= lang("App.menu_quality_certificates")?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <?= lang("App.quality_certificates_add")?>
            </a>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- CSRF meta: her zaman mevcut olsun -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <div class="card mb-5 mb-xl-4">
        <?php if (!empty($quality_certificates)): ?>
            <div class="card card-flush">
                <div class="align-items-center py-5 gap-2 gap-md-5"></div>
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                        <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th><?= lang("App.title")?></th>
                            <th><?= lang("App.created_user")?></th>
                            <th><?= lang("App.status")?></th>
                            <th><?= lang("App.actions")?></th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600" id="sortable-list">
                        <?php foreach($quality_certificates as $row): ?>
                            <tr data-id="<?= $row->id; ?>">
                                <td>#</td>
                                <td><?= esc($row->title); ?></td>
                                <td><?= esc($row->createdUser); ?></td>
                                <td>
                                    <label class="el-switch">
                                        <input type="checkbox"
                                               class="toggle-isActive"
                                               data-id="<?= $row->id;?>"
                                            <?= $row->isActive ? 'checked' : '' ?>>
                                        <span class="el-switch-style"></span>
                                    </label>
                                </td>
                                <td>
                                    <a href="#"
                                       class="btn btn-primary btn-sm btn-edit"
                                       data-id="<?= $row->id;?>"
                                       data-title="<?= esc($row->title);?>"
                                       data-img-url="<?= esc($row->imgUrl);?>"
                                       data-icon-url="<?= esc($row->iconUrl);?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="<?= $row->id;?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="card-px text-center pt-15 pb-15">
                        <h2 class="fs-2x fw-bold mb-0">Veri Bulunamadı.</h2>
                        <p class="text-gray-500 fs-4 fw-semibold py-7">
                            Şu anda yüklü bir eklenti bulunmamaktadır.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <?= lang("App.quality_certificates_add")?>
                        </a>
                    </div>
                    <div class="text-center pb-15 px-5">
                        <img src="<?= adminTheme(); ?>assets/media/illustrations/sketchy-1/17.png"
                             alt=""
                             class="mw-100 h-200px h-sm-325px"/>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?= lang("App.quality_certificates_name")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <form action="<?= site_url('/admin/qualityCertificates/store') ?>" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("App.quality_certificates_title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("App.quality_certificates_title")?>"/>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">
                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid iconUrl" type="text" name="iconUrl" placeholder="icon Url"><br>
                                        <button type="button"
                                                class="btn-selectfile btn btn-light-success"
                                                data-input="iconUrl"
                                                data-preview="preview-icon">
                                            <?= lang("App.quality_certificates_select_file")?>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview preview-icon"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">
                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid imgUrl" type="text" name="imgUrl" placeholder="img Url"><br>
                                        <button type="button"
                                                class="btn-selectfile btn btn-light-success"
                                                data-input="imgUrl"
                                                data-preview="preview-img">
                                            <?= lang("App.quality_certificates_select_file")?>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview preview-img"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"  data-bs-dismiss="modal"><?= lang("App.close")?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("App.save")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?= lang("App.quality_certificates_update")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <!-- ÖNEMLİ: id ile seçelim -->
                    <form id="editForm" action="<?= site_url('/admin/qualityCertificates/edit') ?>" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("App.quality_certificates_title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("App.quality_certificates_title")?>"/>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">
                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid iconUrl" type="text" name="iconUrl" placeholder="icon Url"><br>
                                        <button type="button"
                                                class="btn-selectfile btn btn-light-success"
                                                data-input="iconUrl"
                                                data-preview="preview-icon">
                                            <?= lang("App.quality_certificates_select_file")?>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="previewIcon preview preview-icon"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">
                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid imgUrl" type="text" name="imgUrl" placeholder="img Url"><br>
                                        <button type="button"
                                                class="btn-selectfile btn btn-light-success"
                                                data-input="imgUrl"
                                                data-preview="preview-img">
                                            <?= lang("App.quality_certificates_select_file")?>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="previewImg preview preview-img"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" class="id">
                            <button class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("App.close")?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("App.save")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- File Manager Modal -->
    <div class="modal-files">
        <iframe src="<?= site_url("admin/files/getModals") ?>"></iframe>
    </div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/mare/jqueryui/js/jquery-ui.min.js"></script>

    <script>
        $(function () {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            const csrfName = '<?= csrf_token() ?>';
            let originalOrder = [];
            let originalData = {};
            let isEditing = false;

            // 1) Styles (opsiyonel ama iyi olur)
            $('<style>')
                .text('.row-placeholder{height:48px;background:#f0f6ff;border:1px dashed #5e8dff;}')
                .appendTo(document.head);

// 2) Yardımcı: kolon genişliklerini sabitle (tablo sıçramasın)
            function fixHelper(e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }

            // 1) SIRALAMA
            $('#sortable-list').sortable({
                items: 'tr',           // sadece satırlar sürüklensin
                axis: 'y',             // dikey
                handle: 'td, .handle', // istersen bir handle ekleyebilirsin
                placeholder: 'row-placeholder',
                helper: fixHelper,
                tolerance: 'pointer',
                start: function () {
                    // İstersen eski sıralamayı al
                },
                update: function () {
                    // Yeni sıralamayı oku
                    const $tbody = $(this);
                    const ids = $tbody.children('tr').map(function(i, el){
                        return $(el).data('id');
                    }).get();

                    // items = [{id, rank}]
                    const items = ids.map((id, idx) => ({ id: id, rank: idx + 1 }));

                    // CSRF
                    const csrfName  = '<?= csrf_token() ?>';
                    let csrfToken   = $('meta[name="csrf-token"]').attr('content');

                    // Gönder
                    $.ajax({
                        url: '<?= site_url("admin/qualityCertificates/updateRank") ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            items: items,      // Nesne formatı
                            order: ids,        // Liste formatı (yedek)
                            [csrfName]: csrfToken
                        },
                        success: function (res) {
                            if (res.status === 'success') {
                                Swal.fire({ icon:'success', title:'Sıralama güncellendi!', showConfirmButton:false, timer:1200 });
                                // İstersen reload etme; sadece başarı mesajı kalsın
                                // location.reload();
                            } else {
                                Swal.fire({ icon:'error', title:'Sıralama hatası', text: res.message ?? 'Bilinmeyen hata.' });
                            }
                            if (res.csrfToken) {
                                $('meta[name="csrf-token"]').attr('content', res.csrfToken);
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({ icon:'error', title:'Sunucu hatası', text: xhr.responseText || 'Güncellenemedi.' });
                        }
                    });
                }
            });

            // 2) AKTİF/PASİF
            $(document).on('change', '.toggle-isActive', function () {
                const id = $(this).data('id');
                const isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '<?= site_url("admin/qualityCertificates/isActiveSetter") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id, isActive: isActive, [csrfName]: csrfToken },
                    success: function (response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.status === 'success' ? 'Durum güncellendi!' : 'Güncelleme başarısız!',
                            showConfirmButton: false,
                            timer: 1200
                        });
                        if (response.csrfToken) {
                            csrfToken = response.csrfToken;
                            $('meta[name="csrf-token"]').attr('content', csrfToken);
                        }
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Sunucu hatası!', text: 'İşlem gerçekleştirilemedi.' });
                    }
                });
            });

            // 3) SİLME
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Bu kaydı silmek istediğinize emin misiniz?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url("admin/qualityCertificates/delete") ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: { id: id, [csrfName]: csrfToken },
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire({ icon: 'success', title: 'Silindi!', text: 'Kayıt başarıyla silindi.', showConfirmButton: false, timer: 1500 })
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Hata!', text: response.message ?? 'Kayıt silinemedi.' });
                                }
                                if (response.csrfToken) {
                                    csrfToken = response.csrfToken;
                                    $('meta[name="csrf-token"]').attr('content', csrfToken);
                                }
                            },
                            error: function () {
                                Swal.fire({ icon: 'error', title: 'Sunucu hatası!', text: 'İşlem sırasında hata oluştu.' });
                            }
                        });
                    }
                });
            });

            // 4) DÜZENLEME MODALI
            $('.btn-edit').on('click', function(e){
                e.preventDefault();

                const id      = String($(this).data('id'));
                const title   = String($(this).data('title') ?? '');
                const imgUrl  = String($(this).data('img-url') ?? '');
                const iconUrl = String($(this).data('icon-url') ?? '');

                $('.id').val(id);
                $('.title').val(title);
                $('.imgUrl').val(imgUrl);
                $('.iconUrl').val(iconUrl);

                $('.previewImg').html(imgUrl  ? `<img src="${imgUrl}"  style="max-width:100%; max-height:150px;" />` : '');
                $('.previewIcon').html(iconUrl ? `<img src="${iconUrl}" style="max-width:100%; max-height:150px;" />` : '');

                originalData = { id, title, imgUrl, iconUrl };

                $('#editModal').modal('show');
            });

            // 5) DÜZENLEME FORMU GÖNDERME — id ile bağlandı, NO-CHANGE BLOĞU KALDIRILDI
            $('#editForm').on('submit', function () {
                isEditing = true;
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <?= lang("App.saving") ?>'
                );
                // Normal submit devam etsin (prevent yok)
            });

            // İsteğe bağlı: modal kapandığında resetle (no-changes uyarısı kaldırıldı)
            $('#editModal').on('hidden.bs.modal', function () {
                const form = $(this).find('form')[0];
                if (form) form.reset();
                $('.previewImg').empty();
                $('.previewIcon').empty();
            });
        });
    </script>

    <script>
        // Dosya seçici (iframe) kontrolü
        const btnSelectFiles = document.querySelectorAll('.btn-selectfile');
        const modalFiles = document.querySelector('.modal-files');
        let currentIndex = null;

        btnSelectFiles.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                currentIndex = index;
                openFilePicker();
            });
        });

        function selectFile(url) {
            if (currentIndex === null) return;

            const inputFields = document.querySelectorAll('.inp-file');
            const previewAreas = document.querySelectorAll('.preview');

            const input = inputFields[currentIndex];
            const preview = previewAreas[currentIndex];

            if (!input || !preview) return;

            input.value = url;

            const ext = url.split('.').pop().toLowerCase();
            preview.innerHTML = ['jpg','jpeg','png','gif','svg','webp'].includes(ext) ? `<img src="${url}" alt="Preview">` : '';

            closeFilePicker();
        }

        function openFilePicker()  { modalFiles.style.display = 'block'; }
        function closeFilePicker() { modalFiles.style.display = 'none'; }
    </script>

<?= sweetAlert() ?>
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Başarılı',
            text: '<?= esc(session()->getFlashdata('success')) ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Hata',
            text: '<?= esc(session()->getFlashdata('error')) ?>'
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Bilgi',
            text: '<?= esc(session()->getFlashdata('info')) ?>'
        });
    </script>
<?php endif; ?>
<?= $this->endSection() ?>