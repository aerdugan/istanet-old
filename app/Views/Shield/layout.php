<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= base_url() ?>" />
    <title><?= $this->renderSection('title') ?></title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="<?= adminTheme()?>assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <?= $this->renderSection('pageStyles') ?>
    <link href="<?= adminTheme()?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= adminTheme()?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <style>body { background-image: url('<?= adminTheme()?>assets/media/auth/bg4.jpg'); } [data-bs-theme="dark"] body { background-image: url('<?= adminTheme()?>assets/media/auth/bg4-dark.jpg'); }</style>
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <div class="d-flex flex-center flex-lg-start flex-column">
                <a href="<?= base_url() ?>" class="mb-7">
                    <img alt="Logo" src="<?= adminTheme()?>assets/media/logos/custom-3.svg" />
                </a>
                <h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2>
            </div>
        </div>
        <?= $this->renderSection('main') ?>
    </div>
</div>
<script>var hostUrl = "<?= adminTheme()?>assets/";</script>
<script src="<?= adminTheme()?>assets/plugins/global/plugins.bundle.js"></script>
<script src="<?= adminTheme()?>assets/js/scripts.bundle.js"></script>
<script src="<?= adminTheme()?>assets/js/custom/authentication/sign-in/general.js"></script>
<?= $this->renderSection('pageScripts') ?>
</body>
</html>