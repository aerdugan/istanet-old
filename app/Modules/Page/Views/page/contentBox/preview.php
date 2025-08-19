<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Preview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#">

    <link href="<?= setContentBox() ?>/assets/minimalist-blocks/content.css" rel="stylesheet">
    <link href="<?= setContentBox() ?>box/box-flex.css" rel="stylesheet">
    <link href="<?= setContentBox() ?>assets/scripts/glide/css/glide.core.css" rel="stylesheet">
    <link href="<?= setContentBox() ?>assets/scripts/glide/css/glide.theme.css" rel="stylesheet">
    <link href="<?= setContentBox() ?>assets/scripts/navbar/navbar.css" rel="stylesheet">

    <?php
    if (!empty($page->cBoxContent)) {
        echo $page->cBoxMainCss;
    } else {
        echo "<script>
            if (localStorage.getItem('preview-maincss') != null) {
                document.head.insertAdjacentHTML('beforeend', localStorage.getItem('preview-maincss'));
            }
        </script>";
    }
    ?>
    <?php
    if (!empty($page->cBoxContent)) {
        echo $page->cBoxSectionCss;
    } else {
        echo "<script>
        if (localStorage.getItem('preview-sectioncss') != null) {
            document.head.insertAdjacentHTML('beforeend', localStorage.getItem('preview-sectioncss'));
        }
        </script>";
    }
    ?>

</head>
<body style="touch-action: pan-y">
<?php
if (!empty($page->cBoxContent)) {
    echo "<div id='isContentBox' class='is-wrapper'>$page->cBoxContent</div>";
} else {
    echo "<div id='isContentBox' class='is-wrapper'></div>
    <script>
        var html = localStorage.getItem('preview-html');
        const wrapper = document.querySelector('.is-wrapper');
        wrapper.innerHTML = '';
        const range = document.createRange();
        range.setStart(wrapper, 0);
        wrapper.appendChild(
            range.createContextualFragment(html)
        );
    </script>";
}
?>
<script src="assets/scripts/glide/glide.js"></script>
<script src="assets/scripts/navbar/navbar.min.js"></script>

<script src="<?= setContentBox() ?>/box/box-flex.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"></script>
<script>
    SmoothScroll({
        frameRate: 150,
        animationTime: 800,
        stepSize: 120,
        pulseAlgorithm: 1,
        pulseScale: 4,
        pulseNormalize: 1,
        accelerationDelta: 300,
        accelerationMax: 2,
        keyboardSupport: 1,
        arrowScroll: 50,
        fixedBackground: 0
    });
</script>
</body>
</html>