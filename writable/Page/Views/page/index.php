<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="/mare/checkbox.css">
<link rel="stylesheet" href="/mare/nestable.css">
<style>
    .form-control {
        line-height: 1.1 !important;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0"><?= lang("App.menu_page")?></h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted"><?= lang("App.menu_page")?></li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#kt_modal_new_card"><?= lang("App.page_add")?></a>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="content-body">
    <div class="container-fluid">
        <div class="dd" id="nestable">
            <ol class="dd-list">
                <?php foreach($items as $item) { ?>
                    <li class="dd-item" data-id="<?php echo $item->id ?>">
                        <div class="dd-handle"><?php echo $item->title; ?></div>
                        <div style="position:absolute;right:0;top:7px;z-index:99;">
                            <div class="row" style="height: 0px !important;padding-right:30px !important;">
                                <div class="d-flex justify-content-end flex-shrink-0" style="padding-right: 10px;padding-top: 1px!important;">
                                    <div class="col-md-6" style="padding-right: 35px"></div>
                                    <div class="col-md-2" style="margin-right: 20px;">
                                        <a href="<?php echo base_url("admin/page/updateForm/$item->id"); ?>" class="btn btn-info btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-2" style="margin-right: 20px;">
                                        <button data-id="<?= $item->id;?>" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-2" style="margin-right: 12px;">
                                        <label class="el-switch">
                                            <input type="checkbox" name="switch" class="toggle-isActive" data-id="<?= $item->id;?>" <?= $item->isActive ? 'checked' : '' ?> hidden>
                                            <span class="el-switch-style"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($item->children)){ print_r(ol_tree_menu($item->children)); }?>
                    </li>
                <?php } ?>
            </ol>
        </div>
    </div>
</div>
<!-- Modal Edit Product-->
<form action="/admin/page/edit" method="post">
    <?= csrf_field() ?>
    <div class="modal fade bd-example-modal-lg" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Page Adı</label>
                            <input type="text" name="name" class="form-control name" placeholder="Page Adı"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Page Başlığı</label>
                            <input type="text" name="title" class="form-control title" placeholder="Page Başlığı"/>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Açıklama 1.Satır</label>
                            <textarea name="desc1" class="form-control desc1"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Açıklama 2.Satır</label>
                            <textarea name="desc2" class="form-control desc2"></textarea>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Buton</label>
                            <select class="form-select btnSwitch allowButton" name="allowButton" id="clientType" data-dropdown-parent="#iwNewSlide" data-control="select2" data-hide-search="true" data-placeholder="Buton Durumu">
                                <option value="0">Pasif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 addBtn" style="padding: 20px;border: 2px solid lightgray;border-style: solid;border-radius: 5px;">
                            <div class="form-group col-md-12">
                                <label>Buton Adı</label>
                                <input type="text" name="buttonCaption" class="form-control buttonCaption" placeholder="Buton Adı" value="" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Buton Url</label>
                                <input type="text" name="buttonUrl" class="form-control buttonUrl" placeholder="Buton Url" value="" />
                            </div>
                            <div class="form-group col-md-12">
                                <input class="inp-file form-control imgUrl" type="text" name="imgUrl" placeholder="img Url"><br>
                                <button type="button" class="btn-selectfile btn btn-primary">Select File</button>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" class="id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?= lang('App.page_add') ?></h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form action="/admin/page/store" method="post">
                <div class="modal-body scroll-y mx-5">
                    <?= csrf_field() ?>
                    <div class="mb-5 fv-row">
                        <label class="required fs-5 fw-semibold mb-2"><?= lang('App.page_title') ?></label>
                        <input type="text" name="title" id="title" class="form-control orm-control-solid" placeholder="<?= lang('App.page_title') ?>"/>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="url" class="form-label"><?= lang('App.page_url') ?></label>
                        <div class="input-group mb-5">
                            <span class="input-group-text" id="basic-addon3"><?php echo site_url()?></span>
                            <input type="text" class="form-control" name="url" id="url" aria-describedby="basic-addon3"/>
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Delete Product-->

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.0/sweetalert2.all.min.js" integrity="sha512-vHKpHh3VBF4B8QqZ1ppqnNb8zoTBceER6pyGb5XQyGtkCmeGwxDi5yyCmFLZA4Xuf9Jn1LBoAnx9sVvy+MFjNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js" integrity="sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/mare/nestable2/js/jquery.nestable.min.js"></script>
<script src="<?=site_url("assets/js/main.js")?>"></script>
<script>
    $(document).ready(function() {

        $('select[name="clonePageId"]').on('change', function(e) {
            e.stopPropagation(); // Nestable tetiklenmesini engelle
        });

        var csrfName = '<?= csrf_token() ?>';  // CSRF token name
        var csrfHash = '<?= csrf_hash() ?>';   // CSRF token hash

        $('.toggle-isActive').on('change', function(e) {
            e.stopPropagation(); // Nestable'ın değişiklik olayını tetiklemeyi engelle

            var id = $(this).data('id');
            var isActive = $(this).is(':checked') ? 1 : 0;

            // Send AJAX request
            $.ajax({
                url: '/admin/page/isActiveSetter',
                method: 'POST',
                data: {
                    id: id,
                    isActive: isActive,
                    [csrfName]: csrfHash // Include the CSRF token in the request
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            position: "center",
                            icon: 'success',
                            title: "Page Durumu Güncellendi!",
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // CSRF token'ı güncelle
                        csrfHash = response.csrf_token;
                    } else {
                        Swal.fire({
                            position: "center",
                            icon: 'warning',
                            title: "Hata Oluştu!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        position: "center",
                        icon: 'error',
                        title: "Bir hata oluştu!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });



        $("#nestable").nestable({
            group: 2,
            maxDepth: 10
        }).on("change", function() {
            var e = {
                id: $(".dd").nestable("serialize"),
                [csrfName]: csrfHash // Dinamik CSRF token adı ve değeri
            };

            if (typeof xhrSira !== "undefined") {
                xhrSira.abort();
            }

            // Sıralama işlemi için AJAX isteği
            xhrSira = $.post("admin/page/ordering", e, function(response) {
                // CSRF token'larını güncelle
                csrfName = response.csrfName;
                csrfHash = response.csrfHash;

                // Yanıtın JSON formatında olduğunu ve işlem başarılı olup olmadığını kontrol edin
                if (response.success) {
                    Swal.fire({
                        title: 'Başarılı!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    });
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            }, "json").fail(function(xhr, status, error) {
                Swal.fire({
                    title: 'Hata!',
                    text: 'Sıralama kaydedilirken bir hata oluştu. Lütfen tekrar deneyin.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            });
        });



        $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const title = $(this).data('title');
            const desc1 = $(this).data('desc1');
            const desc2 = $(this).data('desc2');
            const allowButton = $(this).data('allow-button');
            const buttonCaption = $(this).data('button-caption');
            const buttonUrl = $(this).data('button-url');
            const imgUrl = $(this).data('img-url');

            $('.id').val(id);
            $('.name').val(name);
            $('.title').val(title);
            $('.desc1').val(desc1);
            $('.desc2').val(desc2);
            $('.allowButton').val(allowButton);
            $('.buttonCaption').val(buttonCaption);
            $('.buttonUrl').val(buttonUrl);
            $('.imgUrl').val(imgUrl);

            if (allowButton == 1) {
                $('.addBtn').show();
            } else {
                $('.addBtn').hide();

                $('.buttonCaption').val('');
                $('.buttonUrl').val('');
                $('.imgUrl').val('');
            }

            $('#editModal').modal('show');
        });
        $('.btn-delete').on('click', function(){
            const id = $(this).data('id');
            console.log("ID to delete:", id);
            console.log("CSRF Token:", csrfHash); // Log CSRF token for debug

            Swal.fire({
                title: "Emin misiniz?",
                text: "Bu işlem geri alınamaz!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "Evet, sil!",
                cancelButtonText: "İptal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete the page
                    $.ajax({
                        url: '/admin/page/pageDelete',
                        method: 'POST',
                        data: {
                            id: id,
                            [csrfName]: csrfHash // Include CSRF token
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    position: "center",
                                    icon: 'success',
                                    title: "Page başarıyla silindi!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1100);
                            } else {
                                Swal.fire({
                                    position: "center",
                                    icon: 'warning',
                                    title: response.message || "Hata oluştu!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                            csrfHash = response.csrf_token; // Update CSRF token
                            $('meta[name="csrf-token"]').attr('content', csrfHash); // Update meta tag
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                position: "center",
                                icon: 'error',
                                title: "Bir hata oluştu!",
                                text: `Status: ${status}, Error: ${error}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            console.error("Error details:", xhr.responseText); // Log detailed error information
                        }
                    });
                }
            });
        });
    });
    $(document).on('keyup', '#title', function() {
        var str = $(this).val();
        str = replaceSpecialChars(str);
        str = str.toLowerCase();
        str = str.replace(/\s\s+/g, ' ')
            .replace(/[^a-z0-9\s]/gi, '')
            .replace(/\W+/g, "-");

        function replaceSpecialChars(str) {
            var specialChars = [
                ["ş", "s"], ["ğ", "g"], ["ü", "u"], ["ı", "i"], ["_", "-"],
                ["ö", "o"], ["Ş", "S"], ["Ğ", "G"], ["Ç", "C"], ["ç", "c"],
                ["Ü", "U"], ["İ", "I"], ["Ö", "O"], ["ş", "s"]
            ];
            for (var i = 0; i < specialChars.length; i++) {
                var regex = new RegExp(specialChars[i][0], "gi");
                str = str.replace(regex, specialChars[i][1]);
            }
            return str;
        }

        $("#url").val(str);
    });

</script>
<?= sweetAlert() ?>
<?= sweetAlert2() ?>

<?= $this->endSection() ?>


