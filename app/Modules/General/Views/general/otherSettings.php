<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/codemirror.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/gruvbox-dark.min.css" integrity="sha512-FLFAEkNiUCQXE4MNOd7SrEzeNFEhiCnNYsa1S3sNMZDTNFJgPy42giNLGGJ+Rjbce5L6ICJXtlv6Ue61FFIqqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/cobalt.min.css" integrity="sha512-dAYwzcmdv0GvCo9UJmVP430Mc9kmvpdDVk/pHNG90qTZR6tpHQlR9BsVdK9ZGpnNtQNVl+j7UQppCwOPN0TTNQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/material-palenight.min.css" integrity="sha512-uIAyXysSwPKYTS4BrBQGkt7i9ozdNjNA4jXfjFDl6fWIc2zDllleoiY5EkH7Ib2j+Qb8YJx4a5qy192JZqxqVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php  if($item->whatsappStatus == 1 ) { ?>
    <link href="/mare/contact/plugin/components/font-awesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/mare/contact/plugin/czm-chat-support.css" rel="stylesheet">
<?php }  ?>
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Diğer Ayarlar</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="dashboard" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Diğer Ayarlar</li>
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
                <a class="nav-link text-active-primary me-6" href="/admin/general">Firma Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6" href="/admin/general/contact">İletişim Ayarları</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary me-6 active" href="/admin/general/other">Diğer Ayarlar</a>
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
<div class="card mb-5 mb-xl-5">
    <form action="<?= site_url('admin/general/otherSave') ?>" class="form" method="post">
        <?= csrf_field() ?>

        <div class="card-body border-top p-9">

            <!-- Google reCAPTCHA Site Key -->
            <div class="row mb-6">
                <div class="col-xl-3">
                    <label class="fs-6 fw-bold mt-2 mb-3 text-muted">
                        Google reCAPTCHA Site
                        <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="href">Anahtarı</a>
                    </label>
                </div>
                <div class="col-xl-9">
                    <input type="text" class="form-control form-control-solid"
                           name="googleSiteKey"
                           value="<?= esc(old('googleSiteKey', $item->googleSiteKey ?? '')) ?>">
                </div>
            </div>

            <!-- Google reCAPTCHA Secret Key -->
            <div class="row mb-6">
                <div class="col-xl-3">
                    <label class="fs-6 fw-bold mt-2 mb-3 text-muted">
                        Google reCAPTCHA Site Gizli Anahtarı
                    </label>
                </div>
                <div class="col-xl-9">
                    <input type="text"
                           class="form-control form-control-solid"
                           name="googleSecurityKey"
                           value="<?= esc($item->googleSecurityKey ?? '') ?>">
                </div>
            </div>

            <!-- Google Analytics -->
            <div class="row mb-6">
                <div class="col-xl-3">
                    <label class="fs-6 fw-bold mt-2 mb-3 text-muted">Google Analytics</label>
                </div>
                <div class="col-xl-9">
                <textarea class="form-control form-control-solid"
                          rows="5"
                          id="editorJs1"
                          name="googleAnalytics"><?= esc($item->googleAnalytics ?? '') ?></textarea>
                </div>
            </div>

            <!-- Chatbox -->
            <div class="row mb-6">
                <div class="col-xl-3">
                    <label class="fs-6 fw-bold mt-2 mb-3 text-muted">Chatbox Kodları</label>
                </div>
                <div class="col-xl-9">
                <textarea class="form-control form-control-solid mb-3"
                          rows="5"
                          id="editorJs2"
                          name="chatBox"><?= esc($item->chatBox ?? '') ?></textarea>
                    <p class="text-muted mt-2">
                        Tawk.to sitesinden ücretsiz üye olup ayarlarınızı yaptıktan sonra
                        siteye yerleştirme kodunu bu bölüme ekleyebilirsiniz.
                        <a href="https://dashboard.tawk.to/signup" target="_blank" class="href">Üye olmak için tıklayın</a>.
                    </p>
                </div>
            </div>

            <!-- WhatsApp Status -->
            <div class="row mb-6">
                <div class="col-xl-3">
                    <label class="fs-6 fw-bold mt-2 mb-3 text-muted">WhatsApp</label>
                </div>
                <div class="col-xl-3">
                    <select id="whatsappStatus"
                            class="form-select form-select-solid"
                            name="whatsappStatus"
                            data-control="select2"
                            data-hide-search="true"
                            data-placeholder="Buton Durumu">
                        <option value="0" <?= ($item->whatsappStatus ?? '') == "0" ? 'selected' : '' ?>>Pasif</option>
                        <option value="1" <?= ($item->whatsappStatus ?? '') == "1" ? 'selected' : '' ?>>Aktif</option>
                    </select>
                </div>
            </div>

            <!-- WhatsApp Extra Fields -->
            <div id="seeForm" class="card mb-5 mb-xxl-8 p-10" style="display: none;">

                <?php
                $fields = [
                    ['phoneNumber', 'Whatsapp Telefon', 'number', 'Telefon Numarası'],
                    ['messageSubject', 'Whatsapp Gelen Mesaj', 'text', 'Need help? Chat with us'],
                    ['popupTitle', 'Popup Başlık', 'text', 'Need help? Chat with us'],
                    ['popupDesc', 'Popup Açıklama', 'text', 'Customer Support'],
                    ['buttonCaptionName', 'Buton Adı', 'text', 'Start Chat'],
                    ['message', 'Whatsapp Mesaj', 'text', 'How can we help you?']
                ];
                ?>

                <?php foreach ($fields as [$name, $label, $type, $placeholder]): ?>
                    <div class="row mb-3">
                        <div class="col-xl-3">
                            <label class="fs-6 fw-bold mt-2 mb-3 text-muted"><?= $label ?></label>
                        </div>
                        <div class="col-xl-9">
                            <input type="<?= $type ?>"
                                   name="<?= $name ?>"
                                   class="form-control mb-2 form-control-solid"
                                   placeholder="<?= $placeholder ?>"
                                   value="<?= esc($item->{$name} ?? '') ?>">
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- Submit -->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-primary">
                <?= lang("update") ?>
            </button>
        </div>
    </form>
</div>
<div id="example"></div>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="mare/contact/plugin/components/jquery/jquery-1.9.0.min.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="https://geniuscript.com/selio-script/admin-assets/js/codemirror/lib/codemirror.css">
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/javascript/javascript.js"></script>

<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/addon/edit/matchbrackets.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/xml/xml.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/css/css.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/clike/clike.js"></script>
<script src="https://geniuscript.com/selio-script/admin-assets/js/codemirror/mode/php/php.js"></script>

<?php  if($item->whatsappStatus == 1 ) { ?>
<script src="mare/contact/plugin/components/moment/moment.min.js"></script>
<script src="mare/contact/plugin/components/moment/moment-timezone-with-data.min.js"></script>
<script src="mare/contact/plugin/czm-chat-support.min.js"></script>
<?php } ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            icon: '<?= session()->getFlashdata('alert-type') ?>', // 'success', 'error', 'warning', 'info'
            title: '<?= session()->getFlashdata('message') ?>',
            showConfirmButton: false,
            timer: 3000 // Mesajın otomatik kapanma süresi (ms)
        });
        <?php endif; ?>
    });
    $(document).ready(function () {
        $("#whatsappStatus").change(function () {
            if ($(this).val() === "1") {
                $("#seeForm").fadeIn(); // "Aktif" ise formu göster
            } else if ($(this).val() === "0") {
                $("#seeForm").fadeOut(); // "Pasif" ise formu gizle
            }
        });
        if ($("#whatsappStatus").val() === "1") {
            $("#seeForm").show();
        } else {
            $("#seeForm").hide();
        }
    });
</script>
<?php  if($item->whatsappStatus == 1 ) { ?>
<script>
    $('#example').czmChatSupport({
        button: {
            position: "right",
            style: 1, /* Button style. Number between 1 and 7 */
            src: '<i class="fab fa-whatsapp" style="color: white"></i>', /* Image, Icon or SVG */
            backgroundColor: "#10c379", /* Html color code */
            effect: 1, /* Button effect. Number between 1 and 7 */
            notificationNumber: "1", /* Custom text or false. To remove, (notificationNumber:false) */
            speechBubble: "<?php echo $item->message ?>", /* To remove, (speechBubble:false) */
            pulseEffect: true, /* To remove, (pulseEffect:false) */
            text: { /* For Button style larger than 1 */
                title: "<?php echo $item->popupTitle ?>", /* Writing is required */
                description: "Customer Support", /* To remove, (description:false) */
                online: "I'm Online", /* To remove, (online:false) */
                offline: "I will be back soon" /* To remove, (offline:false) */
            }
        },
        /* Popup Settings */
        popup: {
            automaticOpen: false, /* true or false (Open popup automatically when the page is loaded) */
            outsideClickClosePopup: true, /* true or false (Clicking anywhere on the page will close the popup) */
            effect: 1, /* Popup opening effect. Number between 1 and 15 */
            header: {
                backgroundColor: "#10c379", /* Html color code */
            },
            /* Representative Settings */
            persons: [
                /* Copy for more representatives [::Start Copy::] */
                {
                    avatar: {
                        src: '<i class="fab fa-whatsapp"></i>', /* Image, Icon or SVG */
                        backgroundColor: "#10c379", /* Html color code */
                        onlineCircle: true /* Avatar online circle. To remove, (onlineCircle:false) */
                    },
                    text: {
                        title: "<?php echo $item->popupTitle ?>", /* Writing is required */
                        description: "<?php echo $item->popupDesc ?>", /* To remove, (description:false) */

                        /* Used on one account only */
                        message: false, /* Shows message bubble. To remove, (message:false) */
                        textbox: false, /* Allows the visitor to write the message they want. This feature is currently only available on Whatsapp. To remove, (textbox:false) */
                        button: "<?php echo $item->buttonCaptionName ?>" /* Except for Whatsapp, you only need to use the button. For example: (button:"Start Chat") To remove, (button:false) */
                    },
                    link: {
                        desktop: "https://web.whatsapp.com/send?phone=<?php echo $item->phoneNumber ?>&text=<?php echo $item->messageSubject ?>", /* Writing is required */
                        mobile: "https://wa.me/<?php echo $item->phoneNumber ?>/?text=<?php echo $item->messageSubject ?>" /* If it is hidden desktop link will be valid. To remove, (mobile:false) */
                    },
                    onlineDay: {
                        /* Change the day you are offline like this. (sunday:false) */
                        sunday: "00:00-23:59",
                        monday: "00:00-23:59",
                        tuesday: "00:00-23:59",
                        wednesday: "00:00-23:59",
                        thursday: "00:00-23:59",
                        friday: "00:00-23:59",
                        saturday: "00:00-23:59"
                    }
                },
                /* [::End Copy::] */
            ]
        },
        /* Other Settings */
        sound: true, /* true (default sound), false or custom sound. Custom sound example, (sound:'assets/sound/notification.mp3') */
        changeBrowserTitle: "New Message!", /* Custom text or false. To remove, (changeBrowserTitle:false) */
        cookie: false, /* It does not show the speech bubble, notification number, pulse effect and automatic open popup again for the specified time. For example, do not show for 1 hour, (cookie:1) or to remove, (cookie:false) */
    });
</script>
<?php } ?>
<script type="text/javascript">
    $(function() {var editor = CodeMirror.fromTextArea(document.getElementById("editorJs1"), {lineNumbers: true, matchBrackets: true, mode: "application/x-httpd-php", indentUnit: 2, indentWithTabs: true});});
    $(function() {var editor = CodeMirror.fromTextArea(document.getElementById("editorJs2"), {lineNumbers: true, matchBrackets: true, mode: "application/x-httpd-php", indentUnit: 4, indentWithTabs: true});});
</script>
<?= $this->endSection() ?>
