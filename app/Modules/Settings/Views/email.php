<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">E-Mail Ayarları</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="/dashboard" class="text-muted text-hover-primary">Kontrol Paneli</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Ayarlar</li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">E-Mail Ayarları</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title fs-3 fw-bold">E-Mail Ayarları</div>
    </div>
    <form action="<?= site_url('admin/settings/email/save') ?>" method="post" class="row g-3">
        <?= csrf_field() ?>
        <div class="card-body p-9">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
            <?php endif; ?>
            <div class="row mb-8">
                <div class="col-md-3">
                    <label class="form-label">Protocol</label>
                    <select class="form-select" name="protocol">
                        <option value="smtp" <?= $protocol==='smtp'?'selected':'' ?>>SMTP</option>
                        <option value="sendmail" <?= $protocol==='sendmail'?'selected':'' ?>>sendmail</option>
                        <option value="mail" <?= $protocol==='mail'?'selected':'' ?>>mail()</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">SMTP Host</label>
                    <input class="form-control" name="SMTPHost" value="<?= esc($SMTPHost) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SMTP User</label>
                    <input class="form-control" name="SMTPUser" value="<?= esc($SMTPUser) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SMTP Pass</label>
                    <input class="form-control" name="SMTPPass" value="<?= esc($SMTPPass) ?>">
                    <?php if (!empty($hasEncPass)): ?>
                        <small class="text-muted">Kayıtlı bir parola mevcut. Değiştirmek için yeni parola girin.</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Port</label>
                    <input class="form-control" name="SMTPPort" type="number" value="<?= esc($SMTPPort) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">From Email</label>
                    <input class="form-control" name="fromEmail" value="<?= esc($fromEmail) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">From Name</label>
                    <input class="form-control" name="fromName" value="<?= esc($fromName) ?>">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-light-success me-2">Güncelle</button>
            <a class="btn btn-light-danger" href="/dashboard">İptal</a>
        </div>
    </form>
</div>
<br>
<div class="card shadow-sm">
    <div class="card-header">
        <div class="card-title fs-3 fw-bold">E-Posta Test</div>
    </div>
    <form action="<?= site_url('admin/settings/email/save') ?>" method="post" class="row g-3">
        <?= csrf_field() ?>
        <div class="card-body p-9">
            <div class="row mb-8">
                <div class="col-md-4 mt-5">
                    <label class="form-label">Test E‑posta Adresi</label>
                    <div class="input-group">
                        <input type="email" id="test_email" class="form-control" placeholder="test@example.com">
                        <button type="button" id="send_test_email" class="btn btn-secondary">Test Gönder</button>
                    </div>
                    <small class="text-muted">Girilen adrese mevcut ayarlarla test maili gönderilir.</small>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    (function () {
        const btn = document.getElementById('send_test_email');
        const input = document.getElementById('test_email');
        if (!btn) return;

        btn.addEventListener('click', async function () {
            const email = (input.value || '').trim();
            if (!email) { alert('Lütfen bir e‑posta adresi girin.'); return; }

            try {
                const res = await fetch("<?= route_to('admin.settings.email.test') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // CI4 CSRF (header adı sabit: X-CSRF-TOKEN)
                        'X-CSRF-TOKEN': "<?= csrf_hash() ?>",
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email })
                });

                const data = await res.json();
                if (data.status === 'success') {
                    alert('✅ Test maili gönderildi: ' + email);
                } else {
                    alert('❌ Gönderilemedi: ' + (data.message || res.status));
                }
            } catch (e) {
                alert('❌ İstek hatası: ' + e);
            }
        });
    })();
</script>
<?= $this->endSection() ?>