<?= $this->extend('Views/layout/main') ?>
<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <style>
        .modal-files {
            width: 1530px !important;
            height: 850px;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 6px;
            box-shadow: 0px 15px 30px 0px rgba(95, 95, 95, 0.39);
            z-index: 99999;
        }
        .modal-files iframe {
            position:absolute; top:0; left:0; width:100%;height:100%;border:none;
        }
        .preview img {
            max-width:300px;max-height:100px;
        }
    </style>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <div class="card mb-5 mb-xxl-8">
        <div class="card-body pt-5 pb-5">
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/page/updateForm/<?= $item->id ;?>">Sayfa Ayarları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/page/pluginSettings/<?= $item->id ;?>">Plugin Ayarları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" href="/admin/page/popupSettings/<?= $item->id ;?>">Popup Ayarları</a>
                </li>

                <?php if ($item->isWebEditor == 1 ) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6" href="/admin/page/contentBuilderEdit/<?= $item->id ;?>">Web Editor CB</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6" target="_blank"  href="/admin/page/contentBoxEdit/<?= $item->id ;?>">Web Editor CBOX</a>
                    </li>
                <?php } ?>
                <?php if ($item->isMobileEditor == 1 ) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6" href="/admin/page/contentBuilderMobileEdit/<?= $item->id ;?>">Mobile Editor CB</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary me-6"  target="_blank" href="/admin/page/contentBoxMobileEdit/<?= $item->id ;?>">Mobile Editor CBOX</a>
                    </li>
                <?php } ?>

            </ul>
        </div>
    </div>
    <div class="card mb-5 mb-xl-5">
        <form class="form" action="/admin/page/pageUpdate/<?= $item->id ;?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" class="form-control form-control-solid" id="id" value="<?= $item->id ;?>"/>
            <div class="card-body pt-0 mt-10">
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="title"><span>Sayfa Adı</span></label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="d-flex align-items-center mb-1">
                                    <input type="text" class="form-control form-control-solid" name="title" id="title" value="<?= $item->title ;?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="ikinci"><span>Sayfa Url</span></label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="input-group mb-5">
                                        <span class="input-group-text" id="basic-addon3"><?php echo base_url(""); ?></span>
                                        <input type="text" class="form-control" name="url" id="ikinci" aria-describedby="" value="<?= $item->url ;?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $activeLang = session()->get('data_lang'); ?>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="ikinci"><span>İçerik Çevirisi ve SEO</span></label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-4">Aktif Dil: <?= $currentLang ?? 'yok' ?></div>
                                <div class="col-4">Varsayılan Dil: <?= $defaultLang ?? 'yok' ?></div>
                                <div class="col-4">Eksik Diller: <?= json_encode(array_column($missingLanguages ?? [], 'shorten')) ?></div>
                                <div class="col-8">
                                    <br>
                                    <?php if (!empty($missingLanguages)) : ?>
                                        <div class="row fv-row mb-7">
                                            <?php foreach ($missingLanguages as $lang) : ?>
                                            <div class="col-md-6">
                                                    <a href="#"
                                                       class="btn btn-sm btn-primary generate-lang-btn me-2 mb-2"
                                                       data-lang="<?= esc($lang['shorten']) ?>"
                                                       data-refid="<?= $item->referenceID ?? $item->id ?>">
                                                        <?= strtoupper($lang['shorten']) ?> Sayfasını Oluştur
                                                    </a>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($item->data_lang !== getDefaultLanguage()) : ?>
                                        <a href="#" class="btn btn-info translate-fields-btn" data-id="<?= $item->id ?>">
                                            Bu Dilde Varsayılan İçeriği Çevir
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" class="btn btn-secondary generate-seo-btn" data-id="<?= $item->id ?>">
                                        SEO Açıklaması ve Anahtar Kelimeleri Oluştur
                                    </a>
                                </div>
                                <div class="col-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <br>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="seoDesc">
                            <span>Sayfa Açıklaması</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <textarea class="form-control form-control-solid" id="seoDesc" name="seoDesc" rows="4" data-plugin-maxlength maxlength="320"><?php echo $item->seoDesc; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="exist-values">
                            <span>Sayfa Anahtar Kelimeler</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row" style="border: 4px">
                                <input type="text"
                                       id="exist-values"
                                       name="seoKeywords"
                                       class="form-control form-control-sm form-control-solid"
                                       value='<?= esc($item->seoKeywords, 'html') ?>'
                                       placeholder="Etiket ekleyin">
                            </div>
                        </div>
                    </div>
                </div>
                <hr><br>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_1_option_1">
                            <span>Yayın Durumu</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isActive" value="1" <?php echo ($item->isActive === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_1_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_1_option_1">
                                    <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                            <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                        <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-3">Yayında</span>
                                    </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isActive" value="0" <?php echo ($item->isActive === "0") ? "checked" : ""; ?> id="kt_radio_buttons_1_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_1_option_2">
                                    <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                        <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-3">Taslak</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_2_option_1">
                            <span>Header Durumu</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isHeader" value="1" <?php echo ($item->isHeader === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_2_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_1">
                                    <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M20 21H3C2.4 21 2 20.6 2 20V10C2 9.4 2.4 9 3 9H20C20.6 9 21 9.4 21 10V20C21 20.6 20.6 21 20 21Z" fill="currentColor"/>
                                            <path d="M20 7H3C2.4 7 2 6.6 2 6V3C2 2.4 2.4 2 3 2H20C20.6 2 21 2.4 21 3V6C21 6.6 20.6 7 20 7Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                        <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Göster</span>
                                    </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isHeader" value="0" <?php echo ($item->isHeader === "0") ? "checked" : ""; ?> id="kt_radio_buttons_2_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_2_option_2">
                                    <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M20 21H3C2.4 21 2 20.6 2 20V10C2 9.4 2.4 9 3 9H20C20.6 9 21 9.4 21 10V20C21 20.6 20.6 21 20 21Z" fill="currentColor"/>
                                            <path d="M20 7H3C2.4 7 2 6.6 2 6V3C2 2.4 2.4 2 3 2H20C20.6 2 21 2.4 21 3V6C21 6.6 20.6 7 20 7Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                        <span class="d-block fw-bold text-start">
                                        <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Gösterme</span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_15_option_1">
                            <span>Breadcrumb Durumu</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="breadcrumbStatus" value="1" <?php echo ($item->breadcrumbStatus === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_15_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_15_option_1">
                                        <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        <span class="d-block fw-bold text-start">
                                <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Aktif</span>
                            </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="breadcrumbStatus" value="0" <?php echo ($item->breadcrumbStatus === "0") ? "checked" : ""; ?> id="kt_radio_buttons_15_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_15_option_2">
                                        <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Pasif</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($item && $item->breadcrumbStatus == 1 ) { ?>
                    <div class="row fv-row mb-7">
                        <div class="col-md-3 text-md-end">
                            <label class="fs-6 fw-bold form-label mt-3" for="breadcrumbTitle">
                                <span>Breadcrumb Başlığı</span>

                            </label>
                        </div>
                        <div class="col-md-9">
                            <div class="w-100">
                                <div class="row">
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="text" id="breadcrumbTitle" class="form-control form-control-solid" name="breadcrumbTitle" value="<?php echo $item->breadcrumbTitle ;?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row fv-row mb-7">
                        <div class="col-md-3 text-md-end">
                            <label class="fs-6 fw-bold form-label mt-3" for="breadcrumbSlogan">
                                <span>Breadcrumb Slogan</span>

                            </label>
                        </div>
                        <div class="col-md-9">
                            <div class="w-100">
                                <div class="row">
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="text" id="breadcrumbSlogan" class="form-control form-control-solid" name="breadcrumbSlogan" value="<?php echo $item->breadcrumbSlogan ;?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row fv-row mb-7">
                        <div class="col-md-3 text-md-end">
                            <label class="fs-6 fw-bold form-label mt-3">
                                <span>Breadcrumb Link</span>

                            </label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="inp-file form-control mb-2 form-control-solid" type="text" name="breadcrumbImage" value="<?php echo $item->breadcrumbImage ;?>"><br>
                                    <button type="button" class="btn-selectfile btn btn-light-success">Select File</button>
                                </div>
                                <div class="col-md-6">
                                    <div class="preview"><img src="<?php echo $item->breadcrumbImage ;?>" alt="" height="60px"></div>
                                </div>
                                <div class="modal-files">
                                    <iframe src="<?= site_url("admin/files/getModals") ?>"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <hr>
                <br>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_3_option_1">
                            <span>Footer Durumu</span>

                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isFooter" value="1" <?php echo ($item->isFooter === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_3_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_3_option_1">
                                        <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
                                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Göster</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isFooter" value="0" <?php echo ($item->isFooter === "0") ? "checked" : ""; ?> id="kt_radio_buttons_3_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_3_option_2">
                                        <span class="svg-icon svg-icon-muted svg-icon-3hx">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18 12C18 11.4 17.6 11 17 11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H17C17.6 13 18 12.6 18 12Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Gösterme</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_5_option_1">
                            <span>Select Web Editor</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isWebEditor" value="1" <?php echo ($item->isWebEditor === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_5_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_5_option_1">
                                        <img src="/mare/icons/writing.png" height="40px" alt="" style="margin: 6px">
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Content Builder</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isWebEditor" value="0" <?php echo ($item->isWebEditor === "0") ? "checked" : ""; ?> id="kt_radio_buttons_5_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_5_option_2">
                                        <img src="/mare/icons/content.png" height="40px" alt="" style="margin: 6px">
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Content Box</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row fv-row mb-7">
                    <div class="col-md-3 text-md-end">
                        <label class="fs-6 fw-bold form-label mt-3" for="kt_radio_buttons_6_option_1">
                            <span>Select Mobile Editor</span>
                        </label>
                    </div>
                    <div class="col-md-9">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isMobileEditor" value="1" <?php echo ($item->isMobileEditor === "1") ? "checked" : ""; ?>   id="kt_radio_buttons_6_option_1"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-3 d-flex align-items-center mb-5" for="kt_radio_buttons_6_option_1">
                                        <img src="/mare/icons/writing.png" height="40px" alt="" style="margin: 6px">
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Content Builder</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="isMobileEditor" value="0" <?php echo ($item->isMobileEditor === "0") ? "checked" : ""; ?> id="kt_radio_buttons_6_option_2"/>
                                    <label class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-3 d-flex align-items-center" for="kt_radio_buttons_6_option_2">
                                        <img src="/mare/icons/content.png" height="40px" alt="" style="margin: 6px">
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-3" style="padding-left: 10px">   Content Box</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <div class="separator mb-6"></div>
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo base_url("pageManager"); ?>" class="btn btn-danger me-3">
                            <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor"/>
                                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor"/>
                                </svg>
                            </span>İçerik Listesi
                            </a>
                            <button type="submit" class="btn btn-success">
                            <span class="indicator-label">
                                <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M10 18C9.7 18 9.5 17.9 9.3 17.7L2.3 10.7C1.9 10.3 1.9 9.7 2.3 9.3C2.7 8.9 3.29999 8.9 3.69999 9.3L10.7 16.3C11.1 16.7 11.1 17.3 10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor"/>
                                    <path d="M10 18C9.7 18 9.5 17.9 9.3 17.7C8.9 17.3 8.9 16.7 9.3 16.3L20.3 5.3C20.7 4.9 21.3 4.9 21.7 5.3C22.1 5.7 22.1 6.30002 21.7 6.70002L10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor"/>
                                    </svg>
                                </span>
                            </span>Güncelle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>

    <script>
        // Tagify Etiket Girişi
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.querySelector('#exist-values');
            if (input) {
                new Tagify(input);
            }
        });

        // Title → URL dönüşümü
        $(document).ready(function(){
            $("#title").on("keyup", function() {
                let str = $(this).val();
                str = replaceSpecialChars(str);
                str = str.toLowerCase();
                str = str.replace(/\s\s+/g, ' ').replace(/[^a-z0-9\s]/gi, '').replace(/[^\w]/ig, "-");
                $("#ikinci").val(str);
            });

            function replaceSpecialChars(str) {
                const specialChars = [["ş", "s"], ["ğ", "g"], ["ü", "u"], ["ı", "i"],["_", "-"],
                    ["ö", "o"], ["Ş", "S"], ["Ğ", "G"], ["Ç", "C"], ["ç", "c"],
                    ["Ü", "U"], ["İ", "I"], ["Ö", "O"]];
                for (let i = 0; i < specialChars.length; i++) {
                    str = str.replace(new RegExp(specialChars[i][0], 'ig'), specialChars[i][1]);
                }
                return str;
            }
        });
        // Select File Butonu
        document.addEventListener("DOMContentLoaded", function () {
            const btnSelectFile = document.querySelector('.btn-selectfile');
            if (btnSelectFile) {
                btnSelectFile.addEventListener('click', () => {
                    openModal();
                });
            }
        });

        function selectFile(url) {
            const inpFile = document.querySelector('.inp-file');
            inpFile.value = url;

            const ext = url.split('.').pop().toLowerCase();
            if (['jpg', 'png', 'gif'].includes(ext)) {
                const preview = document.querySelector('.preview');
                preview.innerHTML = `<img src="${url}">`;
            }
        }

        const modalFiles = document.querySelector('.modal-files');
        function openModal() {
            modalFiles.style.display = 'block';
        }
        function closeModal() {
            modalFiles.style.display = 'none';
        }
        // SEO Butonu (Artık dışarıda tanımlı)

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.generate-lang-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const lang = this.dataset.lang;
                    const refId = this.dataset.refid;

                    fetch(`/admin/page/addMissingLanguagePages/${refId}?only=${lang}`)
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: res.status === 'success' ? 'success' : 'error',
                                title: res.status === 'success' ? 'Tamamlandı' : 'Hata',
                                text: res.message,
                            }).then(() => location.reload());
                        });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.translate-lang-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const lang = this.dataset.lang;
                    const refId = this.dataset.refid;

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: `${lang.toUpperCase()} için çeviri yapılacak.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Evet, başla',
                        cancelButtonText: 'Vazgeç'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/page/translateAndCreateLangVersion/${refId}/${lang}`)
                                .then(res => res.json())
                                .then(res => {
                                    Swal.fire({
                                        icon: res.status === 'success' ? 'success' : (res.status === 'info' ? 'info' : 'error'),
                                        title: res.status === 'success' ? 'Tamamlandı' : 'Uyarı',
                                        text: res.message,
                                    }).then(() => location.reload());
                                });
                        }
                    });
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.translate-fields-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'İşlem yapılıyor...',
                        text: 'Lütfen bekleyin. Sayfa içeriği çevriliyor.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/admin/page/translateFields/${id}`)
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: res.status === 'success' ? 'success' : (res.status === 'info' ? 'info' : 'error'),
                                title: res.status === 'success' ? 'Tamamlandı' : 'Uyarı',
                                text: res.message,
                            }).then(() => location.reload());
                        });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.generate-seo-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'İşlem yapılıyor...',
                        text: 'SEO içeriği hazırlanıyor, lütfen bekleyin.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(`/admin/page/generateSeoFields/${id}`)
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: res.status === 'success' ? 'success' : (res.status === 'info' ? 'info' : 'error'),
                                title: res.status === 'success' ? 'Tamamlandı' : 'Uyarı',
                                html: `
                            <strong>Açıklama:</strong><br><em>${res.desc || '-'}</em><br><br>
                            <strong>Keywords:</strong><br><code>${res.keywords || '-'}</code>
                        `
                            }).then(() => location.reload());
                        });
                });
            });
        });

    </script>


<?= sweetAlert2() ?>

<?= $this->endSection() ?>