<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome to Calendar !</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"
        <script src="lib/jquery.js"></script>
        <script src="Lib/jquery-validation-1.16.0/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $.validator.addMethod("iduserRule", function (value, element, pattern) {
                if (value !== '0')
                    return true;
                else
                    return false;


            }, "Please enter a valid input.");

            $(function () {

                $("#create_share_form").validate({
                    rules: {
                        iduser: {
                            required: true,
                            iduserRule: 0
                        }
                    },
                    messages: {
                        iduser: {
                            required: 'specify a user',
                            iduserRule: 'choose a user'
                        }
                    }
                });
                $("input:text:first").focus();
            });
        </script>
    </head>
    <body>
        <div class="title"> Share Calendar!</div>
        <?php include('menu.html'); ?>
        
        <div class="menu"> 
        </div>
        <div class="main"> 
            <a href="http://localhost/prwb_1617_G13/Calendar/index">Back</a>
            <tr>
                <th>Pseudo</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($shares as $share): ?>
                <?php if ($share == $theshare && $theshare != '') : ?>
                    <tr>
                        <?php foreach ($users as $user2): ?>
                            <?php if ($user2->iduser == $share->iduser): ?>


                                <td><?= $user2->pseudo ?></td>

                            <form id="share_form" action="calendar/share_update" method="post">

                                <?php if ($share->read_only == 1): ?>
                                    <td><input id="read_only" name="read_only"  type="checkbox" checked="">Read only<br></td>
                                <?php else: ?>
                                    <td><input id="read_only" name="read_only" type="checkbox">Read only<br></td>
                                <?php endif; ?>


                                <input type='text' id="iduser2" name='iduser2' value='<?= $user2->iduser ?>' hidden>
                                <input type='text' name='idcalendar' value='<?= $share->idcalendar ?>' hidden>
                                <td><input type='submit' value='update'></td>
                            </form>

                        <?php endif; ?>
                    <?php endforeach; ?>

                </tr>

            <?php else: ?>
                <tr>
                    <?php foreach ($users as $user2): ?>

                        <?php if ($user2->iduser == $share->iduser): ?>
                            <td><?= $user2->pseudo ?></td>

                            <td><input type='checkbox' disabled <?= ($share->read_only ? ' checked' : '') ?>></td>

                        <form class="link" action="calendar/share_edit" method='post' >
                            <input type='text' name='iduser2' value='<?= $user2->iduser ?>' hidden>
                            <input type='text' name='idcalendar' value='<?= $share->idcalendar ?>' hidden>
                            <td><input type='submit' value='edit'></td>
                        </form>




                        <form class='link' action='calendar/share_delete' method='post'>
                            <input type='text' name='idcalendar' value='<?= $share->idcalendar ?>' hidden>
                            <input type='text' id="iduser2" name='iduser2' value='<?= $user2->iduser ?>' hidden>
                            <td><input type='submit' value='delete'></td>
                        </form>


                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endif; ?>

    <?php endforeach; ?>
    <tr>
    <form id="create_share_form" name="create_share_form" action="calendar/share/<?= $user->pseudo ?>" method="post">
        <td><select id="iduser" name="iduser" >
                <option value="0" hidden >Select pseudo</option>
                <?php foreach ($forShareUser as $user1): ?>
                    <?php if ($user1 != $user) : ?>

                        <option value="<?= $user1->iduser ?>" > <?= $user1->pseudo ?>

                        </option>
                    <?php endif;
                    php ?>
<?php endforeach; ?>
            </select></td>

        <td><input id="read_only" name="read_only" type="checkbox">Read only<br></td>
        <input type='text' name='idcalendar' value="<?= $idcalendar ?>"hidden>
        <td><input id="share_calendar" name="share_calendar" type="submit" value="Share my calendar"></td>


    </form>

</tr>

</div>
</body>
</html>
