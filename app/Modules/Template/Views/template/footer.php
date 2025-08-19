<?= $this->extend('layout/main') ?>
<?= $this->section('pageStyles') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Tasarım Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Tasarım Ayarları</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#iwNewHeader">Yeni Footer Ekle</a>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card mb-5 mb-xxl-8">
    <div class="card-body pt-5 pb-5">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 " href="/admin/template">Genel Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 " href="/admin/template/header">Header Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active" href="/admin/template/footer">Footer Ayarlar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/template/breadCrumb">BreadCrumb Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6"  href="/admin/template/slider">Slider Ayarları</a>
            </li>
        </ul>
    </div>
</div>
<div class="card mb-5 mb-xl-4">
    <div class="card-body pt-60">
        <table id="kt_datatable_responsive_2" class="table table-striped border rounded gy-5 gs-7 p-5 content-container">
            <thead>
            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                <th class="text-start min-w-400px">Dosya Adı</th>
                <th class="text-start min-w-400px">Footer Adı</th>
            </tr>
            </thead>
            <tbody class="sortable fw-bold text-gray-600">
            <?php foreach ($files as $file): ?>
                <?php $file2 = strtolower(str_replace(".php", '', $file)); ?>
                <tr>
                    <td class="text-start pe-0"><?= $file2 ?></td>
                    <td class="text-start pe-0">
                        <a href="/admin/template/footerEdit/<?= $file ?>" class="btn btn-light-primary btn-sm">Düzenle</a>
                        <a href="#" class="btn btn-light-success btn-sm"  data-bs-toggle="modal" data-bs-target="#footerUpdate<?= $file ?>">Adını Değiştir</a>
                        <button onclick="confirmDelete('<?= $file ?>')" class="btn btn-light-danger btn-sm">Sil</button>
                    </td>
                </tr>
                <div class="modal fade" id="footerUpdate<?= $file ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-750px">
                        <div class="modal-content">
                            <form class="form" action="/admin/template/footerRename/<?= $file ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="modal-header">
                                    <h2 class="fw-bolder">Footer Düzenle</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                                        <div class="row g-9 mb-7">
                                            <div class="col-md-12 fv-row">
                                                <label class="required form-label" for="newName">Footer Adı</label>
                                                <input type="text" name="newName" id="newName" class="form-control mb-2 form-control-solid"  value="<?= pathinfo($file, PATHINFO_FILENAME) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer flex-center text-end">
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Kaydet</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="iwNewHeader" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <form class="form" action="/admin/template/footerStore" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h2 class="fw-bolder">Yeni Footer Ekle</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <div class="row g-9 mb-7">
                            <div class="col-md-12 fv-row">
                                <label class="required form-label" for="name">Footer Adı</label>
                                <input type="text" name="name" id="name" class="form-control mb-2 form-control-solid" placeholder="Footer Adı" value="" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center text-end">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    <?php if (session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Başarılı!',
        text: '<?= session()->getFlashdata('success') ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php elseif (session()->getFlashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Hata!',
        text: '<?= session()->getFlashdata('error') ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php endif; ?>

    function confirmDelete(fileName) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: fileName + ' dosyasını silmek istediğinizden emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'Hayır'
        }).then((result) => {
            if (result.isConfirmed) {
                // Silme işlemine yönlendir
                window.location.href = '/admin/template/footerDelete/' + fileName;
            }
        });
    }
</script>
<?= $this->endSection() ?>
