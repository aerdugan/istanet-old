<?= $this->extend('Views/admin/base') ?>
<?= $this->section('head') ?>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="row g-6 g-xl-9 content-container">
    <div class="col-lg-6">
        <div class="card card-flush h-lg-100">
            <div class="card-header mt-6">
                <div class="card-title flex-column">
                    <h3 class="fw-bold mb-1">Popups</h3>
                    <span class="text-muted mt-1 fw-bold fs-7">Tüm Bölümlerde Gösterir</span>
                </div>
                <div class="card-toolbar">
                    <?php if($popup < 1){ ?>
                        <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="modal" data-bs-target="#iwNewTopPlugin">Ekle</a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body d-flex flex-column mb-6 p-6 pt-3 content-container">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 content-container" id="kt_ecommerce_products_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th><i class="fas fa-align-justify"></i></th>
                            <th class="text-center"><?php echo lang('title'); ?></th>
                            <th class="text-center">İçerik Türü</th>
                            <th class="text-start">Durum</th>
                            <th class="text-center"><?php echo lang('action'); ?></th>
                        </tr>
                        </thead>
                        <tbody class="sortable fw-bold text-gray-600" data-url="<?php echo base_url("popups/rankSetter"); ?>">
                        <?php foreach(getPublicPopups("Tüm İçerikler") as $top) { ?>
                            <tr id="ord-<?php echo $top->id; ?>">
                                <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                <td class="text-center"><?php echo $top->title; ?></td>
                                <td class="text-center"><?php echo $top->controllerName; ?></td>
                                <td class="text-start">
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input
                                                data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <?php if(isAllowedUpdateModule()) { ?>
                                        <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        </button>
                                    <?php } ?>
                                    <?php if(isAllowedDeleteModule()) { ?>
                                        <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </button>
                                    <?php } ?>
                                    <?php if(isAllowedUpdateModule()) { ?>
                                        <a href="<?php echo base_url("popups/popupEditFile/$top->popupName"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php foreach(getPublicPopups("Tüm Ürünler") as $top) { ?>
                            <tr id="ord-<?php echo $top->id; ?>">
                                <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                <td class="text-center"><?php echo $top->title; ?></td>
                                <td class="text-center"><?php echo $top->controllerName; ?></td>
                                <td class="text-start">
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input
                                                data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <?php if(isAllowedDeleteModule()) { ?>
                                        <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </button>
                                    <?php } ?>
                                    <?php if(isAllowedUpdateModule()) { ?>
                                        <a href="<?php echo base_url("admin/popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php foreach(getPublicPopups("Tüm Referanslar") as $top) { ?>
                            <tr id="ord-<?php echo $top->id; ?>">
                                <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                <td class="text-center"><?php echo $top->title; ?></td>
                                <td class="text-center"><?php echo $top->controllerName; ?></td>
                                <td class="text-start">
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input
                                                data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <?php if(isAllowedDeleteModule()) { ?>
                                        <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </button>
                                    <?php } ?>
                                    <?php if(isAllowedUpdateModule()) { ?>
                                        <a href="<?php echo base_url("admin/popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php foreach(getPublicPopups("Tüm Bloglar") as $top) { ?>
                            <tr id="ord-<?php echo $top->id; ?>">
                                <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                <td class="text-center"><?php echo $top->title; ?></td>
                                <td class="text-center"><?php echo $top->controllerName; ?></td>
                                <td class="text-start">
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input
                                                data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <?php if(isAllowedDeleteModule()) { ?>
                                        <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </button>
                                    <?php } ?>
                                    <?php if(isAllowedUpdateModule()) { ?>
                                        <a href="<?php echo base_url("popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if($mobileTheme->isMobile == 1) {;?>
        <div class="col-lg-6">
            <div class="card card-flush h-lg-100">
                <div class="card-header mt-6">
                    <div class="card-title flex-column">
                        <h3 class="fw-bold mb-1">Mobile Popups</h3>
                        <span class="text-muted mt-1 fw-bold fs-7">Tüm Bölümlerde Gösterir</span>
                    </div>
                    <?php if($mobilePopup < 1){ ?>
                        <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="modal" data-bs-target="#iwNewMobileTopPlugin">Ekle</a>
                    <?php } ?>
                </div>
                <div class="card-body d-flex flex-column mb-6 p-6 pt-3 content-container">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 content-container" id="kt_ecommerce_products_table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th><i class="fas fa-align-justify"></i></th>
                                <th class="text-center"><?php echo lang('title'); ?></th>
                                <th class="text-center">İçerik Türü</th>
                                <th class="text-start">Durum</th>
                                <th class="text-center"><?php echo lang('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody class="sortable fw-bold text-gray-600" data-url="<?php echo base_url("popups/rankSetter"); ?>">
                            <?php foreach(getPublicMobilePopups("Tüm İçerikler") as $top) { ?>
                                <tr id="ord-<?php echo $top->id; ?>">
                                    <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                    <td class="text-center"><?php echo $top->title; ?></td>
                                    <td class="text-center"><?php echo $top->controllerName; ?></td>
                                    <td class="text-start">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input
                                                    data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                    class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isAllowedDeleteModule()) { ?>
                                            <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </button>
                                        <?php } ?>
                                        <?php if(isAllowedUpdateModule()) { ?>
                                            <a href="<?php echo base_url("popups/popupEditFile/$top->popupName"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php foreach(getPublicMobilePopups("Tüm Ürünler") as $top) { ?>
                                <tr id="ord-<?php echo $top->id; ?>">
                                    <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                    <td class="text-center"><?php echo $top->title; ?></td>
                                    <td class="text-center"><?php echo $top->controllerName; ?></td>
                                    <td class="text-start">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input
                                                    data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                    class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isAllowedDeleteModule()) { ?>
                                            <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </button>
                                        <?php } ?>
                                        <?php if(isAllowedUpdateModule()) { ?>
                                            <a href="<?php echo base_url("popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php foreach(getPublicMobilePopups("Tüm Referanslar") as $top) { ?>
                                <tr id="ord-<?php echo $top->id; ?>">
                                    <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                    <td class="text-center"><?php echo $top->title; ?></td>
                                    <td class="text-center"><?php echo $top->controllerName; ?></td>
                                    <td class="text-start">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input
                                                    data-url="<?php echo base_url("popups/isActiveSetter/$top->id"); ?>"
                                                    class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isAllowedDeleteModule()) { ?>
                                            <button data-url="<?php echo base_url("popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </button>
                                        <?php } ?>
                                        <?php if(isAllowedUpdateModule()) { ?>
                                            <a href="<?php echo base_url("popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php foreach(getPublicMobilePopups("Tüm Bloglar") as $top) { ?>
                                <tr id="ord-<?php echo $top->id; ?>">
                                    <td class="order text-start" style="vertical-align: middle;"><i class="fas fa-align-justify"></i></td>
                                    <td class="text-center"><?php echo $top->title; ?></td>
                                    <td class="text-center"><?php echo $top->controllerName; ?></td>
                                    <td class="text-start">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input
                                                    data-url="<?php echo base_url("admin/popups/isActiveSetter/$top->id"); ?>"
                                                    class="form-check-input isActive h-20px w-30px" type="checkbox" value="1" name="switch" <?php echo ($top->isActive) ? "checked" : ""; ?>/>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <?php if(isAllowedDeleteModule()) { ?>
                                            <button data-url="<?php echo base_url("admin/popups/delete/$top->id"); ?>"  type="button" class="remove-btn btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </button>
                                        <?php } ?>
                                        <?php if(isAllowedUpdateModule()) { ?>
                                            <a href="<?php echo base_url("admin/popups/popupEditFile/$top->plugin_id"); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M16.95 18.9688C16.75 18.9688 16.55 18.8688 16.35 18.7688C15.85 18.4688 15.75 17.8688 16.05 17.3688L19.65 11.9688L16.05 6.56876C15.75 6.06876 15.85 5.46873 16.35 5.16873C16.85 4.86873 17.45 4.96878 17.75 5.46878L21.75 11.4688C21.95 11.7688 21.95 12.2688 21.75 12.5688L17.75 18.5688C17.55 18.7688 17.25 18.9688 16.95 18.9688ZM7.55001 18.7688C8.05001 18.4688 8.15 17.8688 7.85 17.3688L4.25001 11.9688L7.85 6.56876C8.15 6.06876 8.05001 5.46873 7.55001 5.16873C7.05001 4.86873 6.45 4.96878 6.15 5.46878L2.15 11.4688C1.95 11.7688 1.95 12.2688 2.15 12.5688L6.15 18.5688C6.35 18.8688 6.65 18.9688 6.95 18.9688C7.15 18.9688 7.35001 18.8688 7.55001 18.7688Z" fill="currentColor"/>
                                                <path opacity="0.3" d="M10.45 18.9687C10.35 18.9687 10.25 18.9687 10.25 18.9687C9.75 18.8687 9.35 18.2688 9.55 17.7688L12.55 5.76878C12.65 5.26878 13.25 4.8687 13.75 5.0687C14.25 5.1687 14.65 5.76878 14.45 6.26878L11.45 18.2688C11.35 18.6688 10.85 18.9687 10.45 18.9687Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<br>
<div class="modal fade" id="iwNewTopPlugin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="<?php echo base_url("admin/popups/popupSave"); ?>" method="post">
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <h2 class="fw-bolder">Yeni Popup Ekle</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Contoller Adı</label>
                                <select class="form-select form-select-solid mb-2" name="controllerName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                    <option value="Tüm İçerikler">Tüm İçerikler</option>
                                    <?php if($module->isProduct == 1) {;?>
                                        <option value="Tüm Ürünler">Tüm Ürünler</option>
                                    <?php } ?>
                                    <?php if($module->isReference == 1) {;?>
                                        <option value="Tüm Referanslar">Tüm Referanslar</option>
                                    <?php } ?>
                                    <?php if($module->isBlog == 1) {;?>
                                        <option value="Tüm Bloglar">Tüm Bloglar</option>
                                    <?php } ?>
                                    <?php if($module->isNews == 1) {;?>
                                        <option value="Tüm Haberler">Tüm Haberler</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Plugin Adı</label>
                                <select class="form-select form-select-solid mb-2" name="popupName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                    <?= $pOptions ?>
                                </select>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2" for="startDate">Başlangıç Tarihi</label>
                                <input class="form-control form-control-solid" name="startDate" placeholder="Başlangıç Tarihi" id="startDateP1"/>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2" for="endDate">Bitiş Tarihi</label>
                                <input class="form-control form-control-solid" name="endDate" placeholder="Bitiş Tarihi" id="endDateP1"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center text-end">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if($mobileTheme->isMobile == 1) {;?>
    <div class="modal fade" id="iwNewMobileTopPlugin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form class="form" action="<?php echo base_url("admin/popups/mobilePopupSave"); ?>" method="post">
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <h2 class="fw-bolder">Yeni Mobile Popup Ekle</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                        </div>
                    </div>
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-bold mb-2">Contoller Adı</label>
                                    <select class="form-select form-select-solid mb-2" name="controllerName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                        <option value="Tüm İçerikler">Tüm İçerikler</option>
                                        <option value="Tüm Ürünler">Tüm Ürünler</option>
                                        <option value="Tüm Referanslar">Tüm Referanslar</option>
                                        <option value="Tüm Bloglar">Tüm Bloglar</option>
                                        <option value="Tüm Haberler">Tüm Haberler</option>
                                        <option value="İletişim">İletişim</option>
                                    </select>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-bold mb-2">Plugin Adı</label>
                                    <select class="form-select form-select-solid mb-2" name="popupName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                        <?= $pOptions ?>
                                    </select>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-bold mb-2" for="startDate">Başlangıç Tarihi</label>
                                    <input class="form-control form-control-solid" name="startDate" placeholder="Başlangıç Tarihi" id="startDateM1"/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-bold mb-2" for="endDate">Bitiş Tarihi</label>
                                    <input class="form-control form-control-solid" name="endDate" placeholder="Bitiş Tarihi" id="endDateM1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-center text-end">
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Kaydet</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<div class="card card-flush content-container">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h3 class="fw-bold mb-1">Özel Günler</h3>
            <span class="text-muted mt-1 fw-bold fs-7">Bayramlar ve Özel Günler bu Bölümlerde Gösterir</span>
        </div>
        <div class="card-toolbar">
            <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="modal" data-bs-target="#iwNewBrands">Ekle</a>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
            <thead>
            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                <th class="text-start min-w-25px"></th>
                <th class="text-start min-w-25px"></th>
                <th class="text-start min-w-25px"><?php echo lang('picture'); ?></th>
                <th class="text-start min-w-50px"><?php echo lang('title'); ?></th>
                <th class="text-start min-w-50px"><?php echo lang('status'); ?></th>
                <th class="text-start min-w-50px"><?php echo lang('status'); ?></th>
                <th class="text-center min-w-50px"><?php echo lang('action'); ?></th>
            </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">
            <?php foreach(getAllPublicHolidays() as $item) { ?>
                <tr>
                    <td class="text-start pe-0"></td>
                    <td class="text-start pe-0">
                        <span class="fw-bolder" data-kt-ecommerce-product-filter="product_name"><?php echo $item->holidayTitle; ?></span>
                    </td>
                    <td class="text-start pe-0"><?php echo $item->startDate; ?></td>
                    <td class="text-start pe-0"><?php echo $item->endDate; ?></td>
                    <td class="text-start pe-0" data-order="rating-3">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input
                                    data-url="<?php echo base_url("admin/popups/publicHolidayIsActiveSetter/$item->id"); ?>"
                                    type="checkbox"
                                    id="flexSwitchChecked"
                                    class="form-check-input isActive"
                                    name="switch" <?php echo ($item->isActive) ? "checked" : ""; ?>>
                        </div>
                    </td>
                    <td class="text-start pe-0" data-order="<?php echo ($item->isActive) ? "Aktif" : "Pasif"; ?>">
                        <div class="badge badge-light-<?php echo ($item->isActive) ? "success" : "danger"; ?>"><?php echo ($item->isActive) ? "Aktif" : "Pasif"; ?></div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center flex-shrink-0">
                            <?php if(isAllowedUpdateModule()) { ?>
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#publicHolidayUpdate<?php echo $item->id; ?>">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                    </svg>
                                </span>
                                </a>
                            <?php } ?>
                            <?php if(isAllowedDeleteModule()) { ?>
                                <button
                                        data-url="<?php echo base_url("popups/publicHolidayDelete/$item->id"); ?>"
                                        type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm remove-btn">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </button>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <div class="modal fade" id="publicHolidayUpdate<?php echo $item->id; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <form class="form" action="<?php echo base_url("admin/popups/publicHolidayUpdate/$item->id")?>" method="post">
                                <div class="modal-header" id="kt_modal_add_customer_header">
                                    <h2 class="fw-bolder">Yeni Tatil Düzenle</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                                        <div class="row g-9 mb-7">
                                            <div class="col-md-6 fv-row">
                                                <label class="required form-label">Tatil Adı</label>
                                                <input type="text" name="holidayTitle" class="form-control mb-2 form-control-solid" value="<?php echo $item->holidayTitle ?>" />
                                            </div>
                                            <div class="col-md-6 fv-row">
                                                <label class="required form-label">Plugin Adı</label>
                                                <select class="form-select form-select-solid mb-2" name="popupName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                                    <?= $pOptions ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-9 mb-7">
                                            <br>
                                            <div class="col-md-6 fv-row">
                                                <label class="required fs-6 fw-bold mb-2" for="startDate">Başlangıç Tarihi</label>
                                                <input class="form-control form-control-solid" name="startDate" value="<?php echo $item->startDate ?>" id="startDateT2"/>
                                            </div>
                                            <div class="col-md-6 fv-row">
                                                <label class="required fs-6 fw-bold mb-2" for="endDate">Bitiş Tarihi</label>
                                                <input class="form-control form-control-solid" name="endDate" value="<?php echo $item->endDate ?>"  id="endDateT2"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer flex-center text-end">
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label"><?php echo lang("save") ;?></span>
                                        <span class="indicator-progress">Lütfen Bekleyiniz...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="iwNewBrands" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="<?php echo base_url('admin/popups/publicHolidayAdd')?>" method="post">
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <h2 class="fw-bolder">Yeni Tatil Ekle</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <div class="row g-6 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Tatil Adı</label>
                                <input type="text" name="holidayTitle" class="form-control mb-2 form-control-solid" placeholder="Tatil Adı" value="" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required form-label">Plugin Adı</label>
                                <select class="form-select form-select-solid mb-2" name="popupName" data-control="select2" data-hide-search="true" data-placeholder="Müşteri Tipi">
                                    <?= $pOptions ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-6 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2" for="startDate">Başlangıç Tarihi</label>
                                <input class="form-control form-control-solid" name="startDate" id="startDateT1"/>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-bold mb-2" for="endDate">Bitiş Tarihi</label>
                                <input class="form-control form-control-solid" name="endDate" id="endDateT1"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center text-end">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3 AlModal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label"><?php echo lang("save") ;?></span>
                        <span class="indicator-progress">Lütfen Bekleyiniz...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<?= $this->endSection() ?>
