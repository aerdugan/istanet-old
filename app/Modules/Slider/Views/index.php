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
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= lang("Slider.menu_slider")?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="dashboard" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted"><?= lang("Slider.menu_slider")?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#addModal"><?= lang("Slider.slider_add")?></a>
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
                    <th>Slider Resmi</th>
                    <th><?=lang("Slider.slider_name")?></th>
                    <th><?=lang("Slider.title")?></th>
                    <th><?=lang("Slider.created_user")?></th>
                    <th><?=lang("Slider.status")?></th>
                    <th><?=lang("Slider.actions")?></th>
                </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" id="sortable-list">
                <?php foreach($sliders as $row): ?>
                    <tr data-id="<?= $row->id; ?>">
                        <td>#</td>
                        <td>
                            <a class="d-block overlay" data-fslightbox="lightbox-basic" href="<?= base_url($row->imgUrl) ?>">
                                <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-100px min-w-100px"
                                     style="background-image:url('<?= base_url($row->imgUrl) ?>')">
                                </div>
                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                    <i class="bi bi-eye-fill text-white fs-3x"></i>
                                </div>
                            </a>
                        </td>
                        <td><?= $row->name ?></td>
                        <td><?= $row->title; ?></td>
                        <td><?= $row->createdUser; ?></td>
                        <td>
                            <label class="el-switch">
                                <input type="checkbox" name="switch" class="toggle-isActive" data-id="<?= $row->id;?>" <?= $row->isActive ? 'checked' : '' ?> hidden>
                                <span class="el-switch-style"></span>
                            </label>
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm btn-edit" type="button"
                               data-id="<?= $row->id;?>"
                               data-name="<?= $row->name;?>"
                               data-title="<?= $row->title;?>"
                               data-desc1="<?= $row->desc1;?>"
                               data-desc2="<?= $row->desc2;?>"
                               data-allow-button="<?= $row->allowButton;?>"
                               data-button-caption="<?= $row->buttonCaption;?>"
                               data-button-url="<?= $row->buttonUrl;?>"
                               data-img-url="<?= $row->imgUrl;?>">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" type="button" data-id="<?= $row->id;?>"><i class="fa fa-trash"></i></a>
                            <?php
                            $refID = $row->referenceID ?? $row->id;
                            $existingLangs = $referenceLangs[$refID] ?? [];
                            $activeShortCodes = array_column($activeLanguages, 'shorten');
                            $missingLangs = array_diff($activeShortCodes, $existingLangs);
                            ?>
                            <?php foreach ($missingLangs as $lang): ?>
                                <a href="#" class="btn btn-sm btn-info generate-slider-btn"
                                   data-id="<?= $refID ?>"
                                   data-lang="<?= $lang ?>">
                                    <?= strtoupper($lang) ?> Oluştur
                                </a>
                            <?php endforeach; ?>
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
                    <h2><?= lang("Slider.slider_add")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <form action="/admin/slider/store" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_name")?></label>
                                <input type="text" name="name" class="form-control form-control-solid name" placeholder="<?= lang("Slider.slider_name")?>" required/>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("Slider.slider_title")?>"/>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_desc1")?></label>
                                <textarea name="desc1" class="form-control form-control-solid desc1"></textarea>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_desc2")?></label>
                                <textarea name="desc2" class="form-control form-control-solid desc2"></textarea>
                            </div>
                            <div class="col-md-8 fv-row">
                                <label class="required fs-6 fw-semibold form-label mb-2"><?= lang("Slider.slider_button")?></label>
                                <div class="row fv-row">
                                    <div class="col-6">
                                        <select name="allowButton" class="form-select form-select-solid btnSwitch allowButton" data-control="select2" data-hide-search="true" data-placeholder="Month">
                                            <option value="0"><?= lang("Slider.passive")?></option>
                                            <option value="1"><?= lang("Slider.active")?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group col-md-12 addBtn" style="padding: 20px;border: 1px solid lightgray;border-style: solid;border-radius: 5px;">
                                <div class="mb-5">
                                    <label class="form-label"><?= lang("Slider.slider_button_title")?></label>
                                    <input type="text" name="buttonCaption" class="form-control form-control-solid buttonCaption" placeholder="<?= lang("Slider.slider_button_title")?>" value="" />
                                </div>
                                <div class="mb-5">
                                    <label class="form-label"><?= lang("Slider.slider_button_url")?></label>
                                    <input type="text" name="buttonUrl" class="form-control form-control-solid buttonUrl" placeholder="<?= lang("Slider.slider_button_url")?>" value="" />
                                </div>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">

                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid imgUrl" type="text" name="imgUrl" placeholder="img Url"><br>
                                        <button type="button" class="btn-selectfile btn btn-light-success"><?= lang("Slider.slider_select_file")?></button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"  data-bs-dismiss="modal"><?= lang("Slider.close")?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("Slider.save")?></button>
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
                    <h2><?= lang("Slider.slider_update")?></h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                    <form action="/admin/slider/edit" class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="form-row">
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_name")?></label>
                                <input type="text" name="name" class="form-control form-control-solid name" placeholder="<?= lang("Slider.slider_name")?>"/>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_title")?></label>
                                <input type="text" name="title" class="form-control form-control-solid title" placeholder="<?= lang("Slider.slider_title")?>"/>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_desc1")?></label>
                                <textarea name="desc1" class="form-control form-control-solid desc1"></textarea>
                            </div>
                            <div class="mb-5">
                                <label class="form-label"><?= lang("Slider.slider_desc2")?></label>
                                <textarea name="desc2" class="form-control form-control-solid desc2"></textarea>
                            </div>
                            <div class="col-md-8 fv-row">
                                <label class="required fs-6 fw-semibold form-label mb-2"><?= lang("Slider.slider_button")?></label>
                                <div class="row fv-row">
                                    <div class="col-6">
                                        <select name="allowButton" class="form-select form-select-solid btnSwitch allowButton" data-control="select2" data-hide-search="true" data-placeholder="Month">
                                            <option value="0"><?= lang("Slider.passive")?></option>
                                            <option value="1"><?= lang("Slider.active")?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group col-md-12 addBtn" style="padding: 20px;border: 1px solid lightgray;border-style: solid;border-radius: 5px;">
                                <div class="mb-5">
                                    <label class="form-label"><?= lang("Slider.slider_button_title")?></label>
                                    <input type="text" name="buttonCaption" class="form-control form-control-solid buttonCaption" placeholder="<?= lang("Slider.slider_button_title")?>" value="" />
                                </div>
                                <div class="mb-5">
                                    <label class="form-label"><?= lang("Slider.slider_button_url")?></label>
                                    <input type="text" name="buttonUrl" class="form-control form-control-solid buttonUrl" placeholder="<?= lang("Slider.slider_button_url")?>" value="" />
                                </div>
                            </div>
                            <div class="col-md-8 fv-row mt-10 mb-10">
                                <div class="row fv-row">

                                    <div class="col-md-6">
                                        <input class="inp-file form-control mb-2 form-control-solid imgUrl" type="text" name="imgUrl" placeholder="img Url"><br>
                                        <button type="button" class="btn-selectfile btn btn-light-success"><?= lang("Slider.slider_select_file")?></button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" class="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Slider.close")?></button>
                            <button type="submit" class="btn btn-primary"><?= lang("Slider.save")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-files">
        <iframe src="<?= site_url("admin/files/getModals") ?>"></iframe>
    </div>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?= adminTheme();?>assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
    <script>

        $(document).ready(function(){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var csrfName = '<?= csrf_token() ?>'; // Örneğin 'csrf_test_name'

            $('#sortable-list').sortable({
                start: function () {
                    originalOrder = $(this).sortable('toArray', { attribute: 'data-id' });
                },
                update: function () {
                    const newOrder = $(this).sortable('toArray', { attribute: 'data-id' });

                    if (JSON.stringify(newOrder) === JSON.stringify(originalOrder)) return;

                    $.ajax({
                        url: '<?= site_url("admin/slider/updateRank") ?>',
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


            $('.toggle-isActive').on('change', function() {
                var id = $(this).data('id');
                var isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '/admin/slider/isActiveSetter',
                    method: 'POST',
                    data: {
                        id: id,
                        isActive: isActive,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire({
                                position: "center",
                                icon: 'success', // veya 'error', 'warning', 'info' gibi değerler
                                title: "<?= lang("Slider.slider_status_changed")?>!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1100); // 3 saniye bekler
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: 'warning', // veya 'error', 'warning', 'info' gibi değerler
                                title: "Hata Oluştu!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });

            $('.btn-edit').on('click', function(e){
                e.preventDefault();

                const id = $(this).data('id');
                const name = $(this).data('name');
                const title = $(this).data('title');
                const desc1 = $(this).data('desc1');
                const desc2 = $(this).data('desc2');
                const allowButton = $(this).data('allow-button');
                const buttonCaption = $(this).data('button-caption');
                const buttonUrl = $(this).data('button-url');
                const imgUrl = $(this).data('img-url'); // Mevcut resim URL'si

                $('.id').val(id);
                $('.name').val(name);
                $('.title').val(title);
                $('.desc1').val(desc1);
                $('.desc2').val(desc2);
                $('.allowButton').val(allowButton).trigger('change'); // trigger change event to apply changes to allowButton
                $('.buttonCaption').val(buttonCaption);
                $('.buttonUrl').val(buttonUrl);
                $('.imgUrl').val(imgUrl);

                if (imgUrl) {
                    $('.preview').html(`<img src="${imgUrl}" style="max-width:100%; max-height:150px;" />`);
                } else {
                    $('.preview').html('');
                }

                // Başlangıçta allowButton değerine göre addBtn görünürlüğünü ayarla
                if (allowButton == 1) {
                    $('.addBtn').show();
                } else {
                    $('.addBtn').hide();
                }

                $('#editModal').modal('show');
            });

            $(".btnSwitch").change(function(){
                if($(this).val() === "1"){
                    $(".addBtn").fadeIn();
                } else if ($(this).val() === "0"){
                    $(".addBtn").hide();
                }
            });

            $('#addModal').on('show.bs.modal', function () {
                const allowButton = $('.allowButton').val();
                if (allowButton == 1) {
                    $('.addBtn').show();
                } else {
                    $('.addBtn').hide();
                }
            });

            $('.btn-delete').on('click', function(e){
                e.preventDefault();

                const id = $(this).data('id');

                Swal.fire({
                    title: "<?= lang("Slider.are_you_sure")?>?",
                    text: "<?= lang("Slider.delete_message")?>!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: "<?= lang("Slider.yes_delete")?>!",
                    cancelButtonText: "<?= lang("Slider.cancel")?>"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/slider/delete',
                            method: 'POST',
                            data: {
                                id: id,
                                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: "center",
                                    icon: 'success',
                                    title: "<?= lang("Slider.slider_deleted")?>!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                setTimeout(function() {
                                    location.reload();
                                }, 1100);
                            },
                            error: function() {
                                Swal.fire({
                                    position: "center",
                                    icon: 'error',
                                    title: "<?= lang("Slider.error_message")?>!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });

        });

    </script>
    <script>
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-selectfile')) {
                open();
            }
        });

        function selectFile(url) {
            const activeModal = document.querySelector('.modal.show');
            const inpFile = activeModal.querySelector('.inp-file');
            inpFile.value = url;

            const ext = url.split('.').pop().toLowerCase();
            const preview = activeModal.querySelector('.preview');
            if (['jpg', 'png', 'gif', 'jpeg', 'webp'].includes(ext)) {
                preview.innerHTML = `<img src="${url}">`;
            } else {
                preview.innerHTML = '';
            }
        }

        const modalFiles = document.querySelector('.modal-files');
        function open() {
            modalFiles.style.display = 'block';
        }
        function close() {
            modalFiles.style.display = 'none';
        }
        $('form').on('submit', function (e) {
            if ($(this).hasClass('cancelled')) {
                e.preventDefault(); // iptal edilen formu gönderme
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.generate-slider-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const refId = this.dataset.id;
                    const lang = this.dataset.lang;

                    Swal.fire({
                        title: `${lang.toUpperCase()} slider oluşturulacak`,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const baseUrl = "<?= site_url() ?>";
                    fetch(`${baseUrl}/admin/slider/generateLangVersion/${refId}/${lang}`)
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: res.status === 'success' ? 'success' : (res.status === 'info' ? 'info' : 'error'),
                                title: res.status === 'success' ? 'Tamamlandı' : 'Uyarı',
                                text: res.message
                            }).then(() => location.reload());
                        });
                });
            });
        });
    </script>
<?= sweetAlert() ?>
<?= sweetAlert2() ?>

<?= $this->endSection() ?>