<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
<link href="/mare/cBox/filemanager/files.css" rel="stylesheet">
    <style>
        .file-list a{
            color: #818181 !important;
        }
        .div-breadcrumb .separator {
            display: none !important;
        }
        .is-pop .pop-settings .active {
            top: 100.312px !important;
            left: 787.75px !important;
        ;
        }
    </style>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
<?= $this->endSection() ?>
<?= $this->section('breadCrumbs') ?>
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Dosya Yöneticisi</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                <li class="breadcrumb-item text-muted">
                    <a href="/dashboard" class="text-muted text-hover-primary">Anasayfa</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Dosya Yöneticisi</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <body style="touch-action: pan-y">
            <div class="files"></div>
        </div>
    </div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script type="text/javascript" src="/mare/cBox/filemanager/files.min.js"></script>
<?php $lang = service('request')->getLocale(); ?>
<script>
    const elm = document.querySelector('.files')
    new Files(elm, {
        listFilesUrl: `/admin/files/listFiles`,
        listFoldersUrl: `/admin/files/listFolders`,
        deleteFilesUrl: `/admin/files/deleteFile`,
        moveFilesUrl: `/admin/files/moveFile`,
        createFolderUrl: `/admin/files/createFolder`,
        uploadFilesUrl: `/admin/files/fileUpload`,
        renameFileUrl: `/admin/files/renameFile`,
        getMmodelsUrl: `/admin/files/getModels`,
        textToImageUrl: `/admin/files/textToImage`,
        upscaleImageUrl: `/admin/files/upscaleImage`,
        controlNetUrl: `/admin/files/controlNet`,
        saveTextUrl: `/admin/files/saveText`,

        folderTree: false,
        filesOnly: true,

        panelReverse: true,


        allowedFileTypes: [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/tiff',
            'image/svg+xml',
            'image/vnd.adobe.photoshop',
            'video/mp4',
            'video/quicktime',
            'audio/mpeg',
            'application/json',
            'application/font',
            'application/pdf',
            'application/zip',
            'application/x-rar-compressed',
            'application/msword',
            'application/rtf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/csv',
            'text/markdown',
            'text/plain',
            'text/css',
            'text/javascript',
            'text/html',
        ]
    });
</script>
<?= $this->endSection() ?>