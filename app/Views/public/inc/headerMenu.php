<?php $lang = session('lang') ?? 'tr'; $themeSettings = getThemeSettings(); ?>
<div id="main-menu" class="main-nav zn_mega_wrapper">
    <ul id="menu-main-menu" class="main-menu zn_mega_menu">
        <?php foreach (buildTree(getAllPages()) as $item) { ?>
            <li class="menu-item-has-children">
                <?php if (!empty($item->children)) { ?>
                    <?php if ($item->url != '#') { ?>
                        <a href="<?= base_url($lang . '/' . $item->url); ?>" title="<?= esc($item->title); ?>">
                            <?= esc($item->title); ?>
                        </a>
                    <?php } else { ?>
                        <a href="#" title="<?= esc($item->title); ?>"><?= esc($item->title); ?></a>
                    <?php } ?>
                    <?= ol_treeKallyas($item->children); ?>
                <?php } else { ?>
                    <?php if ($item->url != '#') { ?>
                        <a href="<?= base_url($lang . '/' . $item->url); ?>" title="<?= esc($item->title); ?>">
                            <?= esc($item->title); ?>
                        </a>
                    <?php } else { ?>
                        <a href="#" title="<?= esc($item->title); ?>"><?= esc($item->title); ?></a>
                    <?php } ?>
                <?php } ?>
            </li>
        <?php } ?>
        <?php if ($themeSettings->headerLang == 1){ ?>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <?= getFrontEndLanguageMenuItem(); ?>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <?php } ?>
    </ul>
</div>
