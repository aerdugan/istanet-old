<?php $socialSettings = getSocialSettings(); $themeSettings = getThemeSettings(); ?>
<?php if($themeSettings->headerSocial == 1) { ?>
    <?php if (!get_user_agent()->isMobile()) {?>
        <?php if(!empty($socialSettings->facebook)){ ?>
            <li><a href="<?php echo $socialSettings->facebook ;?>" target="_blank" rel="nofollow" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
        <?php } ;?>
        <?php if(!empty($socialSettings->twitter)){ ?>
            <li><a href="<?php echo $socialSettings->twitter ;?>" target="_blank" rel="nofollow" title="twitter"><i class="fab fa-twitter"></i></a></li>
        <?php } ;?>
        <?php if(!empty($socialSettings->instagram)){ ?>
            <li><a href="<?php echo $socialSettings->instagram ;?>" target="_blank" rel="nofollow" title="instagram"><i class="fab fa-instagram"></i></a></li>
        <?php } ;?>
        <?php if(!empty($socialSettings->youtube)){ ?>
            <li><a href="<?php echo $socialSettings->youtube ;?>" target="_blank" rel="nofollow" title="youtube"><i class="fab fa-youtube"></i></a></li>
        <?php } ;?>
        <?php if(!empty($socialSettings->linkedin)){ ?>
            <li><a href="<?php echo $socialSettings->linkedin ;?>" target="_blank" rel="nofollow" title="linkedin"><i class="fab fa-linkedin"></i></a></li>
        <?php } ;?>
    <?php }  ?>
<?php } ?>