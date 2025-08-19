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
        <tbody class="sortable" data-url="<?= base_url("blog/imageRankSetter") ?>">
        <?php foreach ($item_images as $image): ?>
            <tr id="ord-<?= $image['id'] ?>">
                <td class="order text-center"><i class="fa fa-align-justify"></i></td>
                <td class="w50 text-center">#<?= $image['id'] ?></td>
                <td class="w100 text-center">
                    <img width="30" src="<?= base_url("uploads/blog/" . $image['img_url']) ?>" alt="<?= $image['img_url'] ?>" class="img-responsive">
                </td>
                <td><?= $image['img_url'] ?></td>
                <td class="w100 text-center">
                    <label class="el-switch el-switch-blue">
                        <input data-url="<?= base_url("blogs/imageIsActiveSetter/" . $image['id']) ?>" type="checkbox" class="isActive" <?= ($image['isActive']) ? "checked" : "" ?>>
                        <span class="el-switch-style"></span>
                    </label>
                </td>
                <td class="w100 text-center">
                    <label class="el-switch el-switch-red">
                        <input data-url="<?= base_url("blogs/isCoverSetter/" . $image['id'] . "/" . $image['blog_id']) ?>" type="checkbox" id="reLoad" class="isActive" <?= ($image['isCover']) ? "checked" : "" ?>>
                        <span class="el-switch-style"></span>
                    </label>
                </td>
                <td class="w100 text-center">
                    <button data-url="<?= base_url("blogs/imageDelete/" . $image['id'] . "/" . $image['blog_id']) ?>" class="btn btn-sm btn-danger btn-outline remove-btn">
                        <i class="fa fa-trash"></i> Sil
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>