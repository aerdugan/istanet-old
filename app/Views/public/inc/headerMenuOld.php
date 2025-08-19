<div id="main-menu" class="main-nav zn_mega_wrapper">
    <ul id="menu-main-menu" class="main-menu zn_mega_menu">
        <?php foreach (buildTree(getAllPages()) as $item){ ?>
            <li class="menu-item-has-children">
                <?php if (!empty($item->children)){ ?>
                    <?php if ($item->url!='#'){ ?>
                        <a href="<?php echo base_url($item->url); ?>" title='<?php echo $item->title;?>'><?php echo $item->title;?></a>
                    <?php }else{ ?>
                        <a href="#" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
                    <?php } ?>
                    <?php print_r(ol_treeKallyas($item->children)); ?>
                <?php }else{ ?>
                    <?php if ($item->url!='#'){ ?>
                        <a href="<?php echo base_url($item->url);?>" title='<?php echo $item->title;?>'><?php echo $item->title;?></a>
                    <?php }else{?>
                        <a href="#" title='<?php echo $item->title;?>'><?php echo $item->title;?></a>
                    <?php } ?>
                <?php } ?>
            </li>
        <?php } ?>
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
        <?= getFrontEndLanguageMenu(); ?>
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
    </ul>
</div>

