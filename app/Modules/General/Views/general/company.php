<?= $this->extend('Views/layout/main') ?>

<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>

<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Firma Ayarları</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= site_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Firma Ayarları</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-5 pb-5">
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 active" href="/admin/general">Firma Ayarları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/general/contact">İletişim Ayarları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/general/other">Diğer Ayarlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/general/logo">Logo & Favicon</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/general/seo">Seo Ayarları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/general/socials">Sosyal Medya Ayarları</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mb-5 mb-xl-5">
        <form action="<?= site_url('/admin/general/companySave') ?>" class="form" method="post">
            <?= csrf_field() ?>

            <div class="card-body border-top p-9">

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6 text-muted" for="company_name">
                        <?= lang('companyName') ?>
                    </label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <input
                                        type="text"
                                        id="company_name"
                                        name="company_name"
                                        placeholder="İstanet Bilgisayar"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('company_name', $item->company_name ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6 text-muted" for="companyLongName">Firma Adı</label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <input
                                        type="text"
                                        id="companyLongName"
                                        name="companyLongName"
                                        placeholder="İstanet Bil.Elkt.Bil.Sis.İnş.Tic.Ltd.Şti."
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('companyLongName', $item->companyLongName ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6 text-muted" for="taxOffice">Vergi Dairesi - No</label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <input
                                        type="text"
                                        id="taxOffice"
                                        name="taxOffice"
                                        placeholder="Kartal"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('taxOffice', $item->taxOffice ?? '')) ?>">
                            </div>
                            <div class="col-lg-6 fv-row">
                                <input
                                        type="text"
                                        name="taxNumber"
                                        placeholder="4810599118"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('taxNumber', $item->taxNumber ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6 text-muted" for="ticariSicilNo">Ticari Sicil No</label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <input
                                        type="text"
                                        id="ticariSicilNo"
                                        name="ticariSicilNo"
                                        placeholder="0000001"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('ticariSicilNo', $item->ticariSicilNo ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6 text-muted" for="mersisNo">Mersis No</label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <input
                                        type="text"
                                        id="mersisNo"
                                        name="mersisNo"
                                        placeholder="4810599118"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        value="<?= esc(old('mersisNo', $item->mersisNo ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (session()->getFlashdata('message')): ?>
            Swal.fire({
                icon: '<?= session()->getFlashdata('alert-type') ?>',
                title: '<?= session()->getFlashdata('message') ?>',
                showConfirmButton: false,
                timer: 3000
            });
            <?php endif; ?>
        });
    </script>
<?= $this->endSection() ?>