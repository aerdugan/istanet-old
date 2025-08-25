<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#" />
    <link href="<?= setContentBox() ?>assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />
    <link href="<?= setContentBox() ?>contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
    <style>
        .container {  margin: 0 auto;max-width: 1000px; width:100%; padding:35px 35px; box-sizing: border-box;}
    </style>
</head>
<body style="background-color: #eaeaea">

<div class="container container-builder" style="margin-top: 100px;background-color: white;margin-bottom: 50px;border-radius: 15px">
    <?= $page['inpHtml'] ?>
</div>

<div class="is-tool" style="position:fixed;width:145px;height:50px;top:25px;left:30px;right:auto;border:none;display:block;">
    <form id="form" method="post" action="<?php echo base_url('/admin/page/contentBuilderSave') ?>">
        <input type="hidden" name="id" value="<?php echo @$page['id'] ?>">
        <input type="hidden" name="inpHtml">
        <button type="button" id="btnSave" class="classic" style="width:70px;height:50px;background-color: #17C653;color: white">Kaydet</button>
        <button type="button" onclick="window.location.href='<?= base_url('/admin/page/updateForm/' . $page['id']) ?>'" class="classic" style="width:70px;height:50px;background-color: #F8285A;color: white">
        İptal
        </button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="<?php setContentBox() ?>contentbuilder/contentbuilder.min.js" type="text/javascript"></script>
<script src="<?php setContentBox() ?>assets/minimalist-blocks/content.js" type="text/javascript"></script> <!-- Snippets file -->

<script>
    var builder = new ContentBuilder({
        container: '.container-builder',
        useLightbox: true,
        assetPath: '<?php echo setContentBox() ;?>assets/',
        snippetUrl: '<?php echo setContentBox() ;?>assets/minimalist-blocks/content.js', // Snippet file
        snippetPath: '<?php echo setContentBox() ;?>assets/minimalist-blocks/', // Location of snippets' assets
        snippetPathReplace: ['assets/minimalist-blocks/', '<?php echo setContentBox() ;?>assets/minimalist-blocks/'],
        fontAssetPath: '<?php echo setContentBox() ;?>assets/fonts/',
        modulePath: '<?php echo setContentBox() ;?>assets/modules/',
        snippetOpen: true,
        imageQuality: 0.92,
        maxEmbedImageWidth: 1600,
        snippetAddTool: true,
        disableConfig: false,


        assetFolderTree: true,
        assetPanelReverse: false,
        assetFilesOnly: false,
        assetRefreshButton: false,
        imageAutoUpscale: false,
        panelReverse: false,
        filesOnly: true,

        listFilesUrl: `/files/listFiles`,
        listFoldersUrl: `/files/listFolders`,
        deleteFilesUrl: `/files/deleteFile`,
        moveFilesUrl: `/files/moveFile`,
        createFolderUrl: `/files/createFolder`,
        uploadFilesUrl: `/files/fileUpload`,
        renameFileUrl: `/files/renameFile`,
        getMmodelsUrl: `/files/getModels`,
        textToImageUrl: `/files/textToImage`,
        upscaleImageUrl: `/files/upscaleImage`,
        controlNetUrl: `/files/controlNet`,
        saveTextUrl: `/files/saveText`,




        imageSelect: '/files/fileManagerFiles',
        videoSelect: '/files/fileManagerFiles',
        audioSelect: '/files/fileManagerFiles',
        fileSelect: '/files/fileManagerFiles',
        mediaSelect: '/files/fileManagerFiles',
        mediaHandler: '<?php echo base_url("/admin/page/saveMedia")?>',
        plugins: [
            { name: 'preview', showInMainToolbar: true, showInElementToolbar: true },
            { name: 'wordcount', showInMainToolbar: true, showInElementToolbar: true },
            { name: 'symbols', showInMainToolbar: true, showInElementToolbar: false },
            { name: 'buttoneditor', showInMainToolbar: false, showInElementToolbar: false },
        ],
        snippetCategories: [
            [120,'Basic'],
            [119,'Headline'],
            [102,'Photos'],
            [103,'Profile'],
            [116,'Contact'],
            [104,'Products'],
            [105,'Features'],
            [106,'Process'],
            [107,'Pricing'],
            [108,'Skills'],
            [109,'Achievements'],
            [110,'Quotes'],
            [111,'Partners'],
            [112,'As Featured On'],
            [113,'Page Not Found'],
            [114,'Coming Soon'],
            [115,'Help, FAQ']
        ],
        defaultSnippetCategory: 120, // the default category is 'Basic'
        pluginPath: '<?php echo setContentBox() ;?>contentbuilder/',
        //builderMode: 'clean',
    });

    $('#btnSave').click(function(event) {
        builder.saveImages('<?php echo site_url('/admin/page/saveImage') ?>', function(){
            $('#form [name="inpHtml"]').val(builder.html());
            $('#form').submit();
        });
    });
</script>

</body>
</html>