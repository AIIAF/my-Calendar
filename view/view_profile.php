<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $User->pseudo ?>'s Calendar!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <h1>Welcome <?= $User->pseudo ?></h1>
        <?php include('menu.html'); ?>
    </body>
</html>
