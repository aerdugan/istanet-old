<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Files</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#" />

    <link href="/mare/cBox/filemanager/files.css" rel="stylesheet">
</head>
<body style="touch-action: pan-y">

<div class="files"></div>

<script src="/mare/cBox/filemanager/files.min.js"></script>
<script>
    const elm = document.querySelector('.files');
    window.fileManager = new Files(elm, {

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
        filePicker: true,

        onSelect: (url)=>{
            parent.selectFile(url);
            parent.close();
        },
        onCancel: ()=>{
            parent.close();
        },
        refreshButton: true
    });


</script>
</body>
</html>