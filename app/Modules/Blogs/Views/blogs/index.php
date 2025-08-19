<?= $this->extend('Views/admin/base') ?>
<?= $this->section('head') ?>
    <link href="<?= adminTheme(); ?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
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
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="<?= base_url('blogs/newForm')?>" class="btn btn-flex btn-primary h-40px fs-7 fw-bold <?= $btn_add['class']??''?>">
                <i class="<?= $btn_add['icon']??'' ?>"></i> <?= $btn_add['title']??'' ?>
            </a>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" id="search-users" class="form-control form-control-solid w-250px ps-12" placeholder="Kullanıcı Ara..." />
                </div>
                <div id="kt_ecommerce_report_sales_export" class="d-none"></div>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <i class="ki-outline ki-exit-up fs-2"></i>Export Report</button>
                <div id="kt_ecommerce_report_sales_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">Copy to clipboard</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">Export as Excel</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as CSV</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as PDF</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_user_table">
                <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th><?=lang("App.group_grid_title")?></th>
                    <th><?=lang("App.group_grid_dashboard")?></th>
                    <th><?=lang("App.group_grid_created")?></th>
                    <th>Durum</th>
                    <th>Katelogta Göster</th>
                    <th><?=lang("App.group_grid_options")?></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
    <script src="<?= adminTheme(); ?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        "use strict";

        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';

        var KTUserList = function () {
            var table;
            var datatable;

            var initDatatable = function () {
                table = document.querySelector('#kt_user_table');
                datatable = $(table).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?= base_url('blogs/getBlogs') ?>",
                        type: "POST",
                        data: function (d) {
                            d[csrfName] = csrfHash;
                            return d;
                        },
                        dataSrc: function (json) {
                            if(json.token) {
                                csrfHash = json.token;
                                $('meta[name="' + csrfName + '"]').attr('content', csrfHash);
                            }
                            return json.aaData;
                        }
                    },
                    columns: [
                        { data: 'title' },
                        { data: 'category_id' },
                        { data: 'createdAt' },
                        { data: 'isActive' },
                        { data: 'isFront' },
                        { data: 'options', orderable: false, searchable: false, className: 'text-end' }
                    ]
                });
            };

            var exportButtons = () => {
                const documentTitle = 'User Groups';
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5'].map(type=>({extend:type,title:documentTitle}))
                }).container().appendTo($('#kt_ecommerce_report_sales_export'));

                $('#kt_ecommerce_report_sales_export_menu [data-kt-ecommerce-export]').on('click', function(e){
                    e.preventDefault();
                    $('.buttons-' + $(this).data('kt-ecommerce-export')).click();
                });
            };

            var handleSearchDatatable = function () {
                $('#search-users').keyup(function(e) {
                    datatable.search(e.target.value).draw();
                });
            };

            var handleDelete = function () {
                $(document).on('click', '.delete-blog', function () {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: "Emin misiniz?",
                        text: "Bu kaydı silmek istediğinize emin misiniz?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Evet, sil!",
                        cancelButtonText: "Hayır, iptal",
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "<?= base_url('blogs/delete') ?>",
                                type: "POST",
                                data: { id: id, [csrfName]: csrfHash },
                                success: function(response) {
                                    csrfHash = response.token;
                                    $('meta[name="' + csrfName + '"]').attr('content', csrfHash);

                                    if (response.status === 'success') {
                                        Swal.fire("Silindi!", response.message, "success");
                                        datatable.ajax.reload(null,false); // Sayfa sıfırlamadan yenile
                                    } else {
                                        Swal.fire("Hata!", response.message, "error");
                                    }
                                },
                                error: function(xhr) {
                                    if (xhr.status === 403) {
                                        Swal.fire("Hata!", "CSRF token hatası, lütfen sayfayı yenileyin.", "error");
                                    } else {
                                        Swal.fire("Hata!", "Sunucu ile iletişim kurulamadı.", "error");
                                    }
                                }
                            });
                        }
                    });
                });
            };

            var handleToggles = function () {
                $(document).on('change', '.toggle-active, .toggle-cover', function () {
                    const id = $(this).data('id');
                    const field = $(this).hasClass('toggle-active') ? 'isActive' : 'isFront';
                    const value = $(this).is(':checked') ? 1 : 0;

                    $.ajax({
                        url: "<?= base_url('blogs/toggle') ?>",
                        type: "POST",
                        data: {
                            id: id,
                            field: field,
                            value: value
                        },
                        headers: { // CSRF için header göndermek önemli
                            'X-CSRF-TOKEN': csrfHash
                        },
                        success: function (response) {
                            console.log(response); // (Geçici) Kontrol için
                            // CSRF token yenile
                            if(response.token){
                                csrfHash = response.token;
                                $('meta[name="<?= csrf_token() ?>"]').attr('content', csrfHash);
                            }

                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: 'Değişiklik başarıyla kaydedildi',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata!',
                                    text: response.message || 'İşlem sırasında bir hata oluştu',
                                    timer: 3000,
                                    showConfirmButton: true
                                });
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr); // (Geçici) Kontrol için
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: 'Sunucu ile iletişim kurulamadı.',
                                timer: 3000,
                                showConfirmButton: true
                            });
                        }
                    });
                });
            };


            return {
                init: function () {
                    initDatatable();
                    exportButtons();
                    handleSearchDatatable();
                    handleDelete();
                    handleToggles();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function () {
            KTUserList.init();
        });


    </script>
<?= sweetAlert() ?>
<?= $this->endSection() ?>