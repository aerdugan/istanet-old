<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/codemirror.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/gruvbox-dark.min.css" integrity="sha512-FLFAEkNiUCQXE4MNOd7SrEzeNFEhiCnNYsa1S3sNMZDTNFJgPy42giNLGGJ+Rjbce5L6ICJXtlv6Ue61FFIqqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/cobalt.min.css" integrity="sha512-dAYwzcmdv0GvCo9UJmVP430Mc9kmvpdDVk/pHNG90qTZR6tpHQlR9BsVdK9ZGpnNtQNVl+j7UQppCwOPN0TTNQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/material-palenight.min.css" integrity="sha512-uIAyXysSwPKYTS4BrBQGkt7i9ozdNjNA4jXfjFDl6fWIc2zDllleoiY5EkH7Ib2j+Qb8YJx4a5qy192JZqxqVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Theme Elements Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Theme Elements Ayarları</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <a href="#" class="btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#iwNewHeader">Yeni Theme Elements Ekle</a>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card mb-5 mb-xl-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-ban"></i>&nbsp; <?= $folder ?> dosyasını düzenliyorsunuz.</h3>
    </div>
    <div class="card-body pt-60">
        <style>.CodeMirror{height: 700px !important;}</style>
        <form action="<?= site_url('admin/themeElements/themeElementsUpdate/' . $folder) ?>" method="post">
            <?= csrf_field() ?>
            <div class="card card-flush content-container">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <label class="required form-label">Html Codes</label>
                    <div class="col-xl-12">
                        <textarea name="indexContent" rows="5" cols="10" class="form-control form-control-solid" id="editorJs1"><?= htmlspecialchars($indexContent) ?></textarea>
                    </div>
                    <label class="required form-label">Js Codes</label>
                    <div class="col-xl-12">
                        <textarea name="scriptContent" rows="5" cols="10" class="form-control form-control-solid" id="editorJs3" ><?= htmlspecialchars($scriptContent) ?></textarea>

                    </div>
                    <label class="required form-label">Css Codes</label>
                    <div class="col-xl-12">
                        <textarea name="styleContent" rows="5" cols="10" class="form-control form-control-solid" id="editorJs2"><?= htmlspecialchars($styleContent) ?></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div class="modal-footer flex-right text-end">
                <a href="/admin/themeElements" class="href btn btn-danger me-3 ">Geri</a>
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Kaydet</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>

<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="https://geniuscript.com/selio-script/admin-assets/js/codemirror/lib/codemirror.css">
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/javascript/javascript.js"></script>

<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/addon/edit/matchbrackets.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/xml/xml.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/css/css.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/clike/clike.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/php/php.js"></script>

<script>
    const editor1 = CodeMirror.fromTextArea(document.getElementById("editorJs1"), { mode: "htmlmixed", lineNumbers: true });
    const editor2 = CodeMirror.fromTextArea(document.getElementById("editorJs2"), { mode: "css", lineNumbers: true });
    const editor3 = CodeMirror.fromTextArea(document.getElementById("editorJs3"), { mode: "javascript", lineNumbers: true });

    // Form submit olduğunda verileri textarea'lara geri yaz
    document.querySelector("form").addEventListener("submit", function () {
        editor1.save();
        editor2.save();
        editor3.save();
    });
</script>

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
</script>
<?= $this->endSection() ?>
