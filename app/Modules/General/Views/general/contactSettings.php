<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?php site_url()?>mare/checkbox.css">
<style>
    .sortable tr {
        cursor: move;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">İletişim Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">İletişim Ayarları</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#addModal"><?= lang("App.slider_add")?></a>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<div class="card mb-5 mb-xxl-8">
    <div class="card-body pt-5 pb-5">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 " href="/admin/general">Firma Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active" href="/admin/general/contact">İletişim Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/other">Diğer Ayarlar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/logo">Logo & Favicon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6"  href="/admin/general/seo">Seo Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/socials">Sosyal Medya Ayarları</a>
            </li>
        </ul>
    </div>
</div>

<div class="card card-flush">
    <div class="align-items-center py-5 gap-2 gap-md-5"></div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
            <thead>
            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th>#</th>
                <th>Title</th>
                <th>Telefon</th>
                <th>Fax</th>
                <th>email</th>
                <th>address</th>
                <th>isActrive</th>
                <th><?=lang("App.actions")?></th>
            </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600" id="sortable-list">
            <?php foreach($contacts as $row): ?>
                <tr data-id="<?= $row->id; ?>">
                    <td>#</td>
                    <td><?= $row->title ?></td>
                    <td><?= $row->phone_1; ?></td>
                    <td><?= $row->fax_1; ?></td>
                    <td><?= $row->email; ?></td>
                    <td><?= $row->address; ?></td>
                    <td>
                        <label class="el-switch">
                            <input type="checkbox" name="switch" class="toggle-isActive" data-id="<?= $row->id;?>" <?= $row->isActive ? 'checked' : '' ?>>
                            <span class="el-switch-style"></span>
                        </label>
                    </td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm btn-edit"
                           data-id="<?= $row->id; ?>"
                           data-title="<?= $row->title; ?>"
                           data-phone_1="<?= $row->phone_1; ?>"
                           data-phone_2="<?= $row->phone_2; ?>"
                           data-fax_1="<?= $row->fax_1; ?>"
                           data-fax_2="<?= $row->fax_2; ?>"
                           data-email="<?= $row->email; ?>"
                           data-address="<?= $row->address; ?>"
                           data-address-location="<?= $row->address_location; ?>">
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
                <h2><?= lang("App.slider_add")?></h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                <form action="/admin/general/store" class="form" method="post">
                    <?= csrf_field() ?>
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2" for="title"><?php echo lang('branchName'); ?></label>
                                <input type="text" class="form-control form-control-solid" placeholder="İstanet Merkez" name="title" />
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2 " for="iw_phone1a"><?php echo lang('telephone'); ?> </label>
                                <input class="form-control form-control-solid iw_phone" name="phone_1" placeholder="(216)383-0430"  />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-bold mb-2" for="iw_phone2a"><?php echo lang('telephone'); ?> 2</label>
                                <input class="form-control form-control-solid iw_phone" name="phone_2" placeholder="(532)244-5570"  />
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2 " for="iw_phone3a">Fax </label>
                                <input class="form-control form-control-solid iw_phone" name="fax_1" placeholder="(216)383-0430" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-bold mb-2" for="iw_phone4a"><?php echo lang('mobile'); ?></label>
                                <input class="form-control form-control-solid iw_phone" name="fax_2" placeholder="(532)244-5570" />
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2" for="email">
                                <span class=""><?php echo lang('email'); ?></span>
                            </label>
                            <input type="email" class="form-control form-control-solid"  autocomplete="on" placeholder="ahmet@istanet.com" name="email"/>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2" for="address"><?php echo lang('address'); ?></label>
                            <textarea class="form-control form-control-solid" rows="4" name="address" autocomplete="on" placeholder="Esentepe, Milangaz Cd. No:71, 34870 Kartal/İstanbul"></textarea>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2" for="address_location">Googel Maps Code</label>
                            <textarea class="form-control form-control-solid" rows="4" name="address_location"  placeholder="Maps Code"></textarea>
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
                <h2><?= lang("App.slider_update")?></h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-2 my-7">
                <form action="/admin/general/edit" class="form" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2" for="title"><?php echo lang('branchName'); ?></label>
                            <input type="text" class="form-control form-control-solid" placeholder="İstanet Merkez" name="title" id="title" />
                        </div>
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2 " for="iw_phone1a"><?php echo lang('telephone'); ?> </label>
                            <input class="form-control form-control-solid iw_phone" name="phone_1" placeholder="(216)383-0430" id="iw_phone1a" />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2" for="iw_phone2a"><?php echo lang('telephone'); ?> 2</label>
                            <input class="form-control form-control-solid iw_phone" name="phone_2" placeholder="(532)244-5570" id="iw_phone2a" />
                        </div>
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2 " for="iw_phone3a">Fax </label>
                            <input class="form-control form-control-solid iw_phone" name="fax_1" placeholder="(216)383-0430" id="iw_phone3a" />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2" for="iw_phone4a"><?php echo lang('mobile'); ?></label>
                            <input class="form-control form-control-solid iw_phone" name="fax_2" placeholder="(532)244-5570" id="iw_phone4a" />
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2" for="email">
                            <span class=""><?php echo lang('email'); ?></span>
                        </label>
                        <input type="email" class="form-control form-control-solid" id="email" autocomplete="on" placeholder="ahmet@istanet.com" name="email"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2" for="address"><?php echo lang('address'); ?></label>
                        <textarea class="form-control form-control-solid" rows="4" name="address" id="address" autocomplete="on" placeholder="Esentepe, Milangaz Cd. No:71, 34870 Kartal/İstanbul"></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bold mb-2" for="address_location">Googel Maps Code</label>
                        <textarea class="form-control form-control-solid" rows="4" name="address_location" id="address_location" placeholder="Maps Code"></textarea>
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
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.all.min.js" integrity="sha512-vHKpHh3VBF4B8QqZ1ppqnNb8zoTBceER6pyGb5XQyGtkCmeGwxDi5yyCmFLZA4Xuf9Jn1LBoAnx9sVvy+MFjNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?=site_url("themes/focus2/vendor/jqueryui/js/jquery-ui.min.js")?>"></script>
<script src="<?=site_url("themes/focus2/vendor/toastr/js/toastr.min.js")?>"></script>
<!-- Custom -->
<script src="<?=site_url("assets/js/main.js")?>"></script>
<script>
    $(document).ready(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const csrfName = '<?= csrf_token() ?>'; // CodeIgniter's CSRF token name

        // Initialize sortable for rows
        $('#sortable-list').sortable({
            update: function (event, ui) {
                const order = $(this).sortable('toArray', { attribute: 'data-id' });

                $.ajax({
                    url: '/admin/general/updateRank', // Your server endpoint to update the rank
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken, // Add CSRF token in headers
                    },
                    data: {
                        order: order,
                        [csrfName]: csrfToken, // Include CSRF token in the data
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                position: "center",
                                icon: 'success', // veya 'error', 'warning', 'info' gibi değerler
                                title: "<?= lang("App.slider_ranking_changed")?>!",
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
            },
        });

        // Handle isActive toggle
        $('.toggle-isActive').on('change', function () {
            const id = $(this).data('id');
            const isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/admin/general/isActiveSetter', // Your endpoint to toggle isActive status
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                data: {
                    id: id,
                    isActive: isActive,
                    [csrfName]: csrfToken,
                },
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({
                            position: "center",
                            icon: 'success', // veya 'error', 'warning', 'info' gibi değerler
                            title: "<?= lang("App.slider_status_changed")?>!",
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

        // Handle edit button click
        $('.btn-edit').on('click', function (e) {
            e.preventDefault(); // Prevent default action of the <a> tag

            const id = $(this).data('id');
            const title = $(this).data('title');
            const phone1 = $(this).data('phone_1');
            const phone2 = $(this).data('phone_2');
            const fax1 = $(this).data('fax_1');
            const fax2 = $(this).data('fax_2');
            const email = $(this).data('email');
            const address = $(this).data('address');
            const addressLocation = $(this).data('address_location');

            // Debug logs to verify data attributes
            console.log({ id, title, phone1, phone2, fax1, fax2, email, address, addressLocation });

            // Populate modal fields with data
            $('.id').val(id);
            $('#title').val(title);
            $('#iw_phone1a').val(phone1);
            $('#iw_phone2a').val(phone2);
            $('#iw_phone3a').val(fax1);
            $('#iw_phone4a').val(fax2);
            $('#email').val(email);
            $('#address').val(address);
            $('#address_location').val(addressLocation);

            // Show the modal
            $('#editModal').modal('show');
        });
        // Handle delete button click
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();

            const id = $(this).data('id');

            Swal.fire({
                title: '<?= lang("App.confirm_delete") ?>',
                text: '<?= lang("App.delete_warning") ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<?= lang("App.yes_delete") ?>',
                cancelButtonText: '<?= lang("App.cancel") ?>',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/general/delete', // Your delete endpoint
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        data: {
                            id: id,
                            [csrfName]: csrfToken,
                        },
                        success: function(response) {
                            Swal.fire({
                                position: "center",
                                icon: 'success',
                                title: "<?= lang("App.slider_deleted")?>!",
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
                                title: "<?= lang("App.error_message")?>!",
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
    document.addEventListener("DOMContentLoaded", function() {
        // Flashdata kontrol
        <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            icon: '<?= session()->getFlashdata('alert-type') ?>', // 'success', 'error', 'warning', 'info'
            title: '<?= session()->getFlashdata('message') ?>',
            showConfirmButton: false,
            timer: 3000 // Mesajın otomatik kapanma süresi (ms)
        });
        <?php endif; ?>
    });
</script>
<?= sweetAlert() ?>
<?= $this->endSection() ?>
