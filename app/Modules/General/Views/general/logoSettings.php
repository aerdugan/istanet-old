<?= $this->extend('Views/layout/main') ?>

<?= $this->section('pageStyles') ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        .modal-files { display:none; position:fixed; top:50px; left:50px; right:50px; bottom:50px; background:#fff; border-radius:6px; box-shadow:0 15px 30px rgba(95,95,95,.39); z-index:99999; }
        .modal-files iframe { position:absolute; inset:0; width:100%; height:100%; border:none; }
        .preview img { max-width:300px; max-height:100px; }
        .image-container { width:100%; height:0; padding-bottom:40%; position:relative; overflow:hidden; }
        .image-container img { position:absolute; top:0; left:0; width:100%; height:auto; }
    </style>
<?= $this->endSection() ?>

<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Logo Ayarları</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted"><a href="<?= site_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Logo Ayarları</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-5 pb-5">
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item"><a class="nav-link text-active-primary me-6" href="/admin/general">Firma Ayarları</a></li>
                <li class="nav-item"><a class="nav-link text-active-primary me-6" href="/admin/general/contact">İletişim Ayarları</a></li>
                <li class="nav-item"><a class="nav-link text-active-primary me-6" href="/admin/general/other">Diğer Ayarlar</a></li>
                <li class="nav-item"><a class="nav-link text-active-primary me-6 active" href="/admin/general/logo">Logo & Favicon</a></li>
                <li class="nav-item"><a class="nav-link text-active-primary me-6" href="/admin/general/seo">Seo Ayarları</a></li>
                <li class="nav-item"><a class="nav-link text-active-primary me-6" href="/admin/general/socials">Sosyal Medya Ayarları</a></li>
            </ul>
        </div>
    </div>

<?php
$blank = base_url('assets/admin/assets/media/svg/avatars/blank.svg');
$norm = static function (?string $v) use ($blank): string {
    $v = (string)($v ?? '');
    if ($v === '') return $blank;
    if (preg_match('#^https?://#i', $v)) return $v;
    return base_url(ltrim($v, '/'));
};
?>

    <form action="<?= site_url('/admin/general/logoSave') ?>" class="form" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-6 g-xl-9">
            <!-- Logo -->
            <div class="col-md-6 col-xl-4">
                <div class="card border-hover-primary" style="height: 375px">
                    <div class="card-body p-9">
                        <div class="fs-3 fw-bold text-gray-900">Logo</div>
                        <p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7"></p>
                        <div class="d-flex flex-wrap mb-5">
                            <input type="text" name="logo"
                                   value="<?= esc($item['logo'] ?? '') ?>"
                                   class="inp-file form-control form-control-solid" placeholder="/uploads/logo.png">
                        </div>

                        <div class="d-flex flex-wrap mb-5">
                            <button type="button" class="text-center btn-selectfile btn btn-light-success">Select File</button>
                        </div>
                        <div class="card-header border-0 pt-9">
                            <div class="preview image-container">
                                <img src="<?= esc($norm($item['logo'] ?? '')) ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Logo -->
            <div class="col-md-6 col-xl-4">
                <div class="card border-hover-primary" style="height: 375px">
                    <div class="card-body p-9">
                        <div class="fs-3 fw-bold text-gray-900">2. Logo</div>
                        <p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7"></p>

                        <div class="d-flex flex-wrap mb-5">
                            <input type="text" name="secondLogo"
                                   value="<?= esc($item['secondLogo'] ?? '') ?>"
                                   class="inp-file form-control form-control-solid" placeholder="/uploads/second-logo.png">
                        </div>
                        <div class="d-flex flex-wrap mb-5">
                            <button type="button" class="text-center btn-selectfile btn btn-light-success">Select File</button>
                        </div>

                        <div class="card-header border-0 pt-9" style="background-color:black">
                            <div class="preview image-container">
                                <img src="<?= esc($norm($item['secondLogo'] ?? '')) ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Favicon -->
            <div class="col-md-6 col-xl-4">
                <div class="card border-hover-primary" style="height: 375px">
                    <div class="card-body p-9">
                        <div class="fs-3 fw-bold text-gray-900">Favicon</div>
                        <p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7"></p>

                        <div class="d-flex flex-wrap mb-5">
                            <input type="text" name="favicon"
                                   value="<?= esc($item['favicon'] ?? '') ?>"
                                   class="inp-file form-control form-control-solid" placeholder="/uploads/favicon.ico">
                        </div>

                        <div class="d-flex flex-wrap mb-5">
                            <button type="button" class="text-center btn-selectfile btn btn-light-success">Select File</button>
                        </div>

                        <div class="card-header border-0 pt-9">
                            <div class="preview text-center">
                                <img src="<?= esc($norm($item['favicon'] ?? '')) ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-light-primary"><?= lang('update') ?></button>
        </div>
    </form>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div id="fileModal" class="modal-files" style="display:none">
        <iframe id="fileIframe"></iframe>
    </div>

    <script>
        let currentInpFile = null;
        let currentPreview = null;
        const modalFiles = document.getElementById('fileModal');
        const iframeEl   = document.getElementById('fileIframe');

        document.querySelectorAll('.btn-selectfile').forEach((btn) => {
            btn.addEventListener('click', function() {
                const parent = this.closest('.card');
                currentInpFile = parent.querySelector('.inp-file');
                currentPreview = parent.querySelector('.preview');

                iframeEl.src = "<?= site_url('admin/files/getModals') ?>?t=" + Date.now();
                modalFiles.style.display = 'block';
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                modalFiles.style.display = 'none';
                iframeEl.src = 'about:blank';
            }
        });

        // getModals içinden parent.selectFile(url) çağrılacak
        window.selectFile = function(url) {
            if (!currentInpFile || !currentPreview) return;
            currentInpFile.value = url;

            const ext = (url.split('.').pop() || '').toLowerCase();
            if (['jpg','jpeg','png','gif','webp','svg','ico'].includes(ext)) {
                currentPreview.innerHTML = `<img src="${url}" alt="Seçilen Resim">`;
            } else {
                currentPreview.innerHTML = '';
            }

            modalFiles.style.display = 'none';
            iframeEl.src = 'about:blank';
        };
    </script>

<?php if (session()->getFlashdata('message')): ?>
    <script>
        Swal.fire({
            icon: "<?= session()->getFlashdata('alert-type') ?>",
            title: "Bilgi",
            text: "<?= session()->getFlashdata('message') ?>",
            confirmButtonText: "Tamam"
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>