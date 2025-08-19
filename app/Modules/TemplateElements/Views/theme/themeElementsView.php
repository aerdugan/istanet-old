<?= $this->extend('Views/layout/mainView') ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="<?= base_url("modules/themeElements/$themeElements/index.css") ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$file = FCPATH . "modules/themeElements/$themeElements/index.php";
if (file_exists($file)) {
    include($file);
} else {
    echo "<div class='alert alert-danger'>Dosya bulunamadı: $file</div>";
}
?>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url("modules/themeElements/$themeElements/index.js") ?>"></script>
<?= $this->endSection() ?>
