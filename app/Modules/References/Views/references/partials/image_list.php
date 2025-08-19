<?php if (empty($item_images)): ?>
    <div class="alert alert-danger text-center">
        <p>Burada herhangi bir resim bulunmamaktadır.</p>
    </div>
<?php else: ?>
    <table class="table table-bordered table-striped table-hover pictures_list">
        <thead>
        <tr>
            <th class="order text-center"><i class="fa fa-align-justify"></i></th>
            <th class="text-center">#id</th>
            <th>Görsel</th>
            <th>Resim Adı</th>
            <th>Durumu</th>
            <th>Kapak</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody class="sortable" data-url="<?= base_url("references/imageRankSetter") ?>">
        <?php foreach ($item_images as $image): ?>
            <tr id="ord-<?= $image['id'] ?>">
                <td class="order text-center"><i class="fa fa-align-justify"></i></td>
                <td class="w50 text-center align-middle">#<?= $image['rank']+1 ?></td>
                <td class=" text-center">
                    <a class="d-block overlay" data-fslightbox="lightbox-basic" href="<?= base_url("uploads/references/" . $image['img_url']) ?>">
                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-100px min-w-100px"
                             style="background-image:url('<?= base_url("uploads/references/" . $image['img_url']) ?>')">
                        </div>
                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                            <i class="bi bi-eye-fill text-white fs-3x"></i>
                        </div>
                    </a>
                </td>
                <td><a href="<?= base_url("uploads/references/").$image['img_url'] ?>" target="_blank" class="href"><?= $image['img_url'] ?></a></td>
                <td class="w100 text-center">
                    <label class="el-switch el-switch-blue">
                        <input data-url="<?= base_url("references/imageIsActiveSetter/" . $image['id']) ?>"
                               type="checkbox"
                               class="isActive"
                            <?= ($image['isActive']) ? "checked" : "" ?>>
                        <span class="el-switch-style"></span>
                    </label>
                </td>
                <td class="text-center">
                    <label class="el-switch el-switch-red">
                        <input data-url="<?= base_url("references/isCoverSetter/" . $image['id'] . "/" . $image['reference_id']) ?>"
                               type="checkbox"
                               class="isCover"
                            <?= ($image['isCover']) ? "checked" : "" ?>>
                        <span class="el-switch-style"></span>
                    </label>
                </td>
                <td class="w100 text-center">
                    <button data-url="<?= base_url("references/imageDelete/" . $image['id'] . "/" . $image['reference_id']) ?>" class="btn btn-sm btn-danger btn-outline remove-btn">
                        <i class="fa fa-trash"></i> Sil
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>