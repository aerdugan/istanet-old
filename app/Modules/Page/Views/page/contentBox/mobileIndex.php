<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Public</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#">

    <link href="<?= setContentBox() ?>/contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
    <link href="<?= setContentBox() ?>/contentbox/contentbox.css" rel="stylesheet">

    <style>
        /* Switch the device buttons to save space on smaller screen */
        @media all and (max-width: 1380wpx) {
            .custom-topbar .btn-device-desktop,
            .custom-topbar .btn-device-tablet,
            .custom-topbar .btn-device-tablet-landscape,
            .custom-topbar .btn-device-mobile {display:none !important} /* Hide the topbar buttons */

            .is-responsive-toolbar {display:inline-flex !important} /* Show the default buttons */
        }
        .topbar-shadow {
            position: fixed;
            left: 0;top: 47px;
            width: 100%;
            height: 5px;
            z-index: 1;
            box-shadow: rgba(0, 0, 0, 0.04) 0px 5px 5px 0px;
        }
    </style>
    <?=$page['cBoxMobileMainCss']?>
    <?=$page['cBoxMobileSectionCss']?>
</head>
<body>

<div class="builder-ui keep-selection custom-topbar" data-tooltip>
    <div>
        <img src="/uploads/istanetorj.png" width="120px" alt="">
    </div>
    <div>
        <!-- custom buttons here -->
        <button class="btn-back" title="Back">
            <svg><use xlink:href="#icon-back"></use></svg>
            <span>Back</span>
        </button>

        <div class="separator"></div>

        <button class="btn-undo" title="Undo">
            <svg><use xlink:href="#icon-undo"></use></svg>
        </button>

        <button class="btn-redo" title="Redo">
            <svg><use xlink:href="#icon-redo"></use></svg>
        </button>

        <button class="btn-save" title="Save">
            <svg><use xlink:href="#icon-save"></use></svg>
            <span>Save</span>
        </button>

        <button class="btn-publish" title="Publish">
            <svg><use xlink:href="#icon-publish"></use></svg>
            <span>Publish</span>
        </button>

    </div>
    <div>
        <!-- custom buttons here -->
        <button class="btn-device-desktop-large" data-device="desktop-lg" title="Desktop - Large Screen">
            <svg style="width:18px;height:18px;"><use xlink:href="#icon-device-desktop"></use></svg>
        </button>
        <button class="btn-device-desktop" data-device="desktop" title="Desktop / Laptop">
            <svg style="width:20px;height:20px;"><use xlink:href="#icon-device-laptop"></use></svg>
        </button>
        <button class="btn-device-tablet-landscape" data-device="tablet-landscape" title="Tablet - Landscape">
            <svg style="width:18px;height:18px;transform:rotate(-90deg)"><use xlink:href="#icon-device-tablet"></use></svg>
        </button>
        <button class="btn-device-tablet" data-device="tablet" title="Tablet - Portrait">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-device-tablet"></use></svg>
        </button>
        <button class="btn-device-mobile" data-device="mobile" title="Mobile">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-device-mobile"></use></svg>
        </button>
        <button class="btn-fullview" data-device="fullview" title="Full View">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-fullview"></use></svg>
        </button>

        <div class="separator"></div>

        <button class="btn-download" title="Download">
            <svg><use xlink:href="#icon-download"></use></svg>
        </button>

        <button class="btn-html" title="HTML">
            <svg><use xlink:href="#icon-code"></use></svg>
        </button>

        <button class="btn-preview" title="Preview">
            <svg><use xlink:href="#icon-eye"></use></svg>
        </button>

        <div class="separator"></div>

        <button class="btn-togglepanel" data-button="togglepanel" title="Toggle Edit Panel"> <!-- To enable state, add:  data-state="togglepanel" -->
            <svg><use xlink:href="#icon-pencil"></use></svg>
        </button>
    </div>
</div>
<div class="topbar-shadow"></div>

<div class="is-wrapper" style="opacity:0">
    <?=$page['cBoxMobileContent']?>
</div>

<!-- Required js for editing (not needed in production) -->
<script src="https://cdn.istanet.com/cBox/cBox5830/assets/styles/023.pngcontentbox/lang/en.js"></script>
<script src="<?= setContentBox() ?>contentbox/contentbox.min.js"></script>


<script>

    var intervalId, previousHtml; //Used for Auto Save

    localStorage.removeItem('_zoom'); // Reset zoom

    //Enable editing
    const builder = new ContentBox({
        wrapper: '.is-wrapper',
        canvas: true,
        previewURL: '/admin/page/content-box-preview/<?=$page['id']?>',

        clearPreferences: true, // Reset settings

        controlPanel: true,
        // disablePageShift: true,
        iframeSrc: '/admin/page/content-box-page-blank',
        zoom: 1,
        screenMode: 'mobile', // or desktop
        topSpace: true, // to give a space on top for custom toolbar
        iframeCentered: true,
        htmlButton: true, // HTML button on left sidebar
        undoRedoButtons: true, // Undo & redo buttons on control panel
        toggleDeviceButton: false, // Toggle device button on control panel
        deviceButtons: false, // Multiple device buttons on frame


        sendCommandUrl: '/admin/page/send-command',
        AIToolbar: false,
        showDisclaimer: true,
        startAIAssistant: false, // Auto open 'AI Assistant' panel
        enableShortCommands: true,
        speechRecognitionLang: 'en-US',
        triggerWords: {
            send: ['send', 'okay', 'ok', 'execute', 'run'],
            abort: ['abort', 'cancel'],
            clear: ['clear', 'erase']
        },

        // If using DeepGram for speech recognition, specify the speechTranscribeUrl.
        // speechTranscribeUrl: 'ws://localhost:3002',
        // The server implementation for ws://localhost:3002 can be found in server.js (Node.js code)

        // Enabling AI image generation
        listFilesUrl: '/admin/files/listFiles',
        listFoldersUrl: '/admin/files/listFolders',
        deleteFilesUrl: '/admin/files/deleteFile',
        moveFilesUrl: '/admin/files/moveFile',
        createFolderUrl: '/admin/files/createFolder',
        uploadFilesUrl: '/admin/files/uploadFile',
        renameFileUrl: '/admin/files/renameFile',
        getMmodelsUrl: '/admin/files/getModels',
        textToImageUrl: '/admin/files/textToImage',
        upscaleImageUrl: '/admin/files/upscaleImage',
        controlNetUrl: '/admin/files/controlNet',
        saveTextUrl: '/admin/files/saveText',

        imageAutoUpscale: true,

        templates: [
            {
                url: '<?= setContentBox() ?>assets/templates-simple/templates.js',
                path: '<?= setContentBox() ?>assets/templates-simple/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {
                url: '<?= setContentBox() ?>assets/templates-quick/templates.js',
                path: '<?= setContentBox() ?>assets/templates-quick/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {
                url: '<?= setContentBox() ?>assets/templates-animated/templates.js',
                path: '<?= setContentBox() ?>assets/templates-animated/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {
                url: '<?= setContentBox() ?>assets/thelayout/templates.js',
                path: '<?= setContentBox() ?>assets/thelayout/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
        ],

        imageSelect: '/admin/files/fileManagerFiles',
        videoSelect: '/admin/files/fileManagerFiles',
        audioSelect: '/admin/files/fileManagerFiles',
        fileSelect: '/admin/files/fileManagerFiles',
        mediaSelect: '/admin/files/fileManagerFiles',// for images and videos

        onUploadCoverImage: (e) => {
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved image url
                if(uploadedFileUrl) builder.boxImage(uploadedFileUrl); // change cover image
            });
        },
        onImageUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },
        onVideoUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },
        onAudioUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },
        onMediaUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },
        onFileUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },

        onChange: function () {
            // Auto save
            clearInterval(intervalId);
            intervalId = setInterval(()=>{
                // Check for change every 2s
                let html = builder.htmlCheck(); // htmlCheck() is used only for checking purpose
                if(previousHtml!==html) { // Save only if content changed
                    save();
                    console.log('saving');
                    previousHtml=html;
                }
            }, 2000);
        },

        slider: 'glide',
        navbar: false,

        designUrl1: '<?= setContentBox() ?>assets/designs/basic.js',
        designUrl2: '<?= setContentBox() ?>assets/designs/examples.js',
        designPath: '<?= setContentBox() ?>assets/designs/',
        contentStylePath: '<?= setContentBox() ?>assets/styles/',

        /* ContentBuilder settings */
        modulePath: '<?= setContentBox() ?>assets/modules/',
        fontAssetPath: '<?= setContentBox() ?>assets/fonts/',
        assetPath: '<?= setContentBox() ?>assets/',
        snippetUrl: '<?= setContentBox() ?>assets/minimalist-blocks/content.js',
        snippetPath: '<?= setContentBox() ?>assets/minimalist-blocks/',
        pluginPath: '<?= setContentBox() ?>contentbuilder/',
        useLightbox: true,

    });

    // Example of adding custom buttons
    builder.addButton({
        'pos': 2, // button position
        'title': 'Undo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#icon-undo"></use></svg>',
        'onClick': ()=>{
            builder.undo();
        }
    });
    builder.addButton({
        'pos': 3,
        'title': 'Redo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#icon-redo"></use></svg>',
        'onClick': ()=>{
            builder.redo();
        }
    });
    builder.addButton({
        'pos': 4,
        'title': 'Animation',
        'html': '<svg class="is-icon-flex" style="fill:rgba(0, 0, 0, 0.7);width:14px;height:14px;"><use xlink:href="#icon-wand"></use></svg>',
        'onClick': ()=>{
            builder.openAnimationPanel();
        }
    });
    builder.addButton({
        'pos': 5,
        'title': 'Timeline Editor',
        'html': '<svg><use xlink:href="#icon-anim-timeline"></use></svg>',
        'onClick': ()=>{
            builder.openAnimationTimeline();
        }
    });
    builder.addButton({
        'pos': 6,
        'title': 'AI Assistant',
        'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#icon-message"></use></svg>',
        'onClick': (e)=>{

            builder.openAIAssistant();

        }
    });
    // builder.addButton({
    //     'pos': 8,
    //     'title': 'Settings',
    //     'html': '<svg class="is-icon-flex" style="width:15px;height:15px;"><use xlink:href="#icon-settings"></use></svg>',
    //     'onClick': (e)=>{
    //         builder.openSettings(e);
    //     }
    // });
    builder.addButton({
        'pos': 8,
        'title': 'Clear Content',
        'html': '<svg class="is-icon-flex"><use xlink:href="#icon-eraser"></use></svg>',
        'onClick': (e)=>{
            builder.clear();
        }
    });
    builder.addButton({
        'pos': 9,
        'title': 'Preview',
        'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#ion-eye"></use></svg>',
        'onClick': ()=>{
            var html = builder.html();
            localStorage.setItem('preview-html', html);
            var mainCss = builder.mainCss();
            localStorage.setItem('preview-maincss', mainCss);
            var sectionCss = builder.sectionCss();
            localStorage.setItem('preview-sectioncss', sectionCss);

            window.open('/admin/page/content-box-preview/<?=$page['id']?>', '_blank').focus();
        }
    });


    function uploadFile(e, callback) {

        const selectedFile = e.target.files[0];
        const filename = selectedFile.name;

        const formData = new FormData();
        formData.append('file', selectedFile);
        fetch('/admin/page/upload-file', {
            method: 'POST',
            body: formData
        })
            .then(response=>response.json())
            .then(response=>{
                if(callback) callback(response);
            });

    }

    function save() {

        builder.saveImages('', function(){

            var html = builder.html();
            localStorage.setItem('cBox-mobile-html', html);
            var mainCss = builder.mainCss();
            localStorage.setItem('cBox-mobile-maincss', mainCss);
            var sectionCss = builder.sectionCss();
            localStorage.setItem('cBox-mobile-sectioncss', sectionCss);

            const reqBody = { cBoxMobileContent: html, cBoxMobileMainCss: mainCss, cBoxMobileSectionCss: sectionCss,id:<?=$page['id']?> };
            fetch('/admin/page/save-mobile-content', {
                method:'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reqBody),
            })

                .then(response=>response.json())
                .then(data=>{

                });

        }, function(img, base64, filename){

            // Upload image process
            const reqBody = { image: base64, filename: filename };
            fetch('/admin/page/upload-base64', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify( reqBody ),
            })
                .then(response=>response.json())
                .then(response=>{
                    const uploadedImageUrl = response.url; // get saved image url

                    img.setAttribute('src', uploadedImageUrl); // set image src
                });

        });

    }


    /* Custom Topbar */
    const btnBack = document.querySelector('.custom-topbar .btn-back');
    btnBack.addEventListener('click', ()=>{
        alert('Button clicked. This is an example of a custom button.');
    });

    const btnUndo = document.querySelector('.custom-topbar .btn-undo');
    btnUndo.addEventListener('click', ()=>{
        builder.undo();
    });

    const btnRedo = document.querySelector('.custom-topbar .btn-redo');
    btnRedo.addEventListener('click', ()=>{
        builder.redo();
    });

    const btnSave = document.querySelector('.custom-topbar .btn-save');
    btnSave.addEventListener('click', ()=>{
        alert('Button clicked. This is an example of a custom button.');
    });

    const btnPublish = document.querySelector('.custom-topbar .btn-publish');
    btnPublish.addEventListener('click', ()=>{
        alert('Button clicked. This is an example of a custom button.');
    });

    const btnFullView = document.querySelector('.custom-topbar .btn-fullview');
    const btnDeviceDesktopLarge = document.querySelector('.custom-topbar .btn-device-desktop-large');
    const btnDeviceDesktop = document.querySelector('.custom-topbar .btn-device-desktop');
    const btnDeviceTabletLandscape = document.querySelector('.custom-topbar .btn-device-tablet-landscape');
    const btnDeviceTablet = document.querySelector('.custom-topbar .btn-device-tablet');
    const btnDeviceMobile = document.querySelector('.custom-topbar .btn-device-mobile');

    const clearActiveButtons = () => {
        btnFullView.classList.remove('on');
        btnDeviceDesktop.classList.remove('on');
        btnDeviceDesktopLarge.classList.remove('on');
        btnDeviceTabletLandscape.classList.remove('on');
        btnDeviceTablet.classList.remove('on');
        btnDeviceMobile.classList.remove('on');
    }

    btnFullView.addEventListener('click', ()=>{
        builder.setScreenMode('fullview');
        clearActiveButtons();
        btnFullView.classList.add('on');
    });

    btnDeviceDesktopLarge.addEventListener('click', ()=>{
        builder.setScreenMode('desktop-lg');
        clearActiveButtons();
        btnDeviceDesktopLarge.classList.add('on');
    });

    btnDeviceDesktop.addEventListener('click', ()=>{
        builder.setScreenMode('desktop');
        clearActiveButtons();
        btnDeviceDesktop.classList.add('on');
    });

    btnDeviceTabletLandscape.addEventListener('click', ()=>{
        builder.setScreenMode('tablet-landscape');
        clearActiveButtons();
        btnDeviceTabletLandscape.classList.add('on');
    });

    btnDeviceTablet.addEventListener('click', ()=>{
        builder.setScreenMode('tablet');
        clearActiveButtons();
        btnDeviceTablet.classList.add('on');
    });

    btnDeviceMobile.addEventListener('click', ()=>{
        builder.setScreenMode('mobile');
        clearActiveButtons();
        btnDeviceMobile.classList.add('on');
    });

    if(builder.screenMode==='fullview'){
        btnFullView.classList.add('on');
    } else if(builder.screenMode==='desktop-lg'){
        btnDeviceDesktopLarge.classList.add('on');
    } else if(builder.screenMode==='desktop'){
        btnDeviceDesktop.classList.add('on');
    } else if(builder.screenMode==='tablet-landscape'){
        btnDeviceTabletLandscape.classList.add('on');
    } else if(builder.screenMode==='tablet'){
        btnDeviceTablet.classList.add('on');
    } else if(builder.screenMode==='mobile'){
        btnDeviceMobile.classList.add('on');
    }

    const btnDownload = document.querySelector('.custom-topbar .btn-download');
    if(btnDownload) btnDownload.addEventListener('click', ()=>{
        builder.download();
    });

    const btnHtml = document.querySelector('.custom-topbar .btn-html');
    if(btnHtml) btnHtml.addEventListener('click', ()=>{
        builder.viewHtml();
    });

    const btnPreview = document.querySelector('.custom-topbar .btn-preview');
    btnPreview.addEventListener('click', ()=>{
        let html = builder.html();
        localStorage.setItem('preview-html', html);
        let mainCss = builder.mainCss();
        localStorage.setItem('preview-maincss', mainCss);
        let sectionCss = builder.sectionCss();
        localStorage.setItem('preview-sectioncss', sectionCss);

        window.open('/admin/page/content-box-preview/<?=$page['id']?>', '_blank').focus();
    });

    const btnTogglePanel = document.querySelector('.custom-topbar .btn-togglepanel');
    if(btnTogglePanel) btnTogglePanel.addEventListener('click', ()=>{
        builder.toggleEditPanel();
    });

</script>
<script src="<?= setContentBox() ?>box/box-flex.js"></script> <!-- Box Framework js include -->

</body>
</html>
