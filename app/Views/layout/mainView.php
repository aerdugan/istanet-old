<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>İstanet Bilgisayar</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha512-pgGHFWjBtbKHTTDW5buGZ9mU0nGfxNavf5kWK/Od2ugA//9FuMHAunkAiMe5jeL/5WW1r0UxwKi6D5LpMOJD3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?= $this->renderSection('pageStyles') ?>
</head>
<body>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('pageScripts') ?>
</body>
</html>