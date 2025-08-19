<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="<?php site_url()?>mare/checkbox.css">
    <style>
        .modal-files {
            width: 1530px;
            height: 850px;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 6px;
            box-shadow: 0px 15px 30px 0px rgba(95, 95, 95, 0.39);
            z-index: 99999;
        }
        .modal-files iframe {
            position:absolute; top:0; left:0; width:100%;height:100%;border:none;
        }
        .preview img {
            max-width:300px;max-height:100px;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= lang("App.menu_reference_category")?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="dashboard" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted"><?= lang("App.menu_reference_category")?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#addModal"><?= lang("App.reference_category_add")?></a>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <div class="card card-flush">
        <div class="align-items-center py-5 gap-2 gap-md-5"></div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th><?=lang("App.title")?></th>
                    <th><?=lang("App.created_user")?></th>
                    <th><?=lang("App.status")?></th>
                    <th><?=lang("App.actions")?></th>
                </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" id="sortable-list">
                <?php foreach($categories as $row): ?>
                    <tr data-id="<?= $row->id; ?>">
                        <td>#</td>
                        <td><?= $row->title; ?></td>
                        <td><?= getUserFullName($row->createdUser); ?></td>
                        <td>
                            <label class="el-switch">
                                <input type="checkbox" name="switch" class="toggle-isActive" data-id="<?= $row->id;?>" <?= $row->isActive ? 'checked' : '' ?> hidden>
                                <span class="el-switch-style"></span>
                            </label>
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm btn-edit" type="button"
                               data-id="<?= $row->id;?>"
                               data-title="<?= $row->title;?>"">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" type="button" data-id="<?= $row->id;?>"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?= lang("App.reference_category_add")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <form action="/references/categoryAdd" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("App.title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("App.title")?>"/>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?= lang("App.reference_category_update")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <form action="/references/categoryEdit" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("App.title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("App.title")?>"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" class="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("App.close")?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("App.save")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= site_url('themes/focus2/vendor/jqueryui/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= site_url('themes/focus2/vendor/toastr/js/toastr.min.js') ?>"></script>
    <script>
        $(document).ready(function () {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let csrfName = '<?= csrf_token() ?>';
            let originalOrder = [];
            let originalData = {};
            let isEditing = false;

            // --- 1. SIRALAMA (Sortable) ---
            $('#sortable-list').sortable({
                start: function () {
                    originalOrder = $(this).sortable('toArray', { attribute: 'data-id' });
                },
                update: function () {
                    const newOrder = $(this).sortable('toArray', { attribute: 'data-id' });

                    if (JSON.stringify(newOrder) === JSON.stringify(originalOrder)) return;

                    $.ajax({
                        url: '<?= site_url("references/categoryRankUpdate") ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            order: newOrder,
                            [csrfName]: csrfToken
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sıralama güncellendi!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                window.location.reload();

                                originalOrder = newOrder;

                                if (response.csrfToken) {
                                    csrfToken = response.csrfToken;
                                    $('meta[name="csrf-token"]').attr('content', csrfToken);
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sıralama hatası!',
                                    text: response.message ?? 'Bilinmeyen bir hata oluştu.'
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Sunucu hatası!',
                                text: 'Sıralama güncellenemedi.'
                            });
                        }
                    });
                }
            });

            // --- 2. AKTİF/PASİF DEĞİŞTİRME ---
            $(document).on('change', '.toggle-isActive', function () {
                const id = $(this).data('id');
                const isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '<?= site_url("references/categoryIsActiveSetter") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        isActive: isActive,
                        [csrfName]: csrfToken
                    },
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Sunucu hatası!',
                            text: 'İşlem gerçekleştirilemedi.'
                        });
                    }
                });
            });

            // --- 3. SİLME İŞLEMİ ---
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
                            url: '<?= site_url("references/categoryDelete") ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id,
                                [csrfName]: csrfToken
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Silindi!',
                                        text: 'Kayıt başarıyla silindi.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata!',
                                        text: response.message ?? 'Kayıt silinemedi.'
                                    });
                                }

                                if (response.csrfToken) {
                                    csrfToken = response.csrfToken;
                                    $('meta[name="csrf-token"]').attr('content', csrfToken);
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sunucu hatası!',
                                    text: 'İşlem sırasında hata oluştu.'
                                });
                            }
                        });
                    }
                });
            });

            // --- 4. DÜZENLEME MODALI AÇMA ---
            $(document).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                const title = $(this).data('title');

                $('.id').val(id);
                $('.title').val(title);
                originalData = { id: id, title: title.trim() };
                isEditing = false;

                $('#editModal').modal('show');
            });

            $('#editModal').on('shown.bs.modal', function () {
                $('.title').trigger('focus');
            });

            $('#editModal').on('hidden.bs.modal', function () {
                if (!isEditing) {
                    Swal.fire({
                        icon: 'info',
                        title: "<?= lang('App.no_changes') ?>",
                        text: "<?= lang('App.update_canceled') ?>",
                        confirmButtonText: "<?= lang('App.confirm') ?>"
                    });
                }
                $(this).find('form')[0].reset();
            });

            // --- 5. DÜZENLEME FORMU GÖNDERME ---
            $(document).on('submit', 'form[action="/references/categoryEdit"]', function (e) {
                const currentData = {
                    id: $('.id').val(),
                    title: $('.title').val().trim()
                };

                if (currentData.title === originalData.title && currentData.id === originalData.id) {
                    e.preventDefault();
                    $('#editModal').modal('hide');
                    return false;
                }

                isEditing = true;

                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <?= lang("App.saving") ?>'
                );
            });
        });
    </script>


<?= sweetAlert2() ?>

<?= $this->endSection() ?>