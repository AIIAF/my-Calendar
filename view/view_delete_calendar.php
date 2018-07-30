<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Delete Calendar </title>
        <base href="<?= $web_root ?> "/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <?php include('menu.html'); ?>
        <div class ="title">Confirm Calendar Deletion </div>
        <div class ="main">
            <br><br>
            <p>The calendar you are contains somme event(s)!</p>
            <p>Are you sure you want to delete it?</p>
            <form action="calendar/delete" method="post">
                <input type="hidden" name="idcalendar" value="<?= $idcalendar; ?>"/>
                <input class="btn" type="submit" name="cancel" value="Cancel">
                <input class="btn" type="submit" name="delete" value="Confirm">
            </form>

        </div> 
    </body>
</html>

