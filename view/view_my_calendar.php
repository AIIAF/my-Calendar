<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome to Calendar !</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>


        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.16.0/jquery.validate.min.js" type="text/javascript"></script>

        <script>

            $.validator.addMethod("regex", function (value, element, pattern) {
                if (pattern instanceof Array) {
                    for (p of pattern) {
                        if (!p.test(value))
                            return false;
                    }
                    return true;
                } else {
                    return pattern.test(value);
                }
            }, "Please enter a valid input.");


            var delIdcalendar;
            $(function () {

                $('form').each(function () {
                    $(this).validate({
                        rules: {
                            description: {
                                minlength: 3,
                                required: true,
                                maxlength: 500
                            }

                        },
                        messages: {
                            description: {
                                minlenth: 'minimum 3 characters',
                                required: 'specify description',
                                maxlength: 'maximum 500 characters'
                            }


                        }
                    });
                });
                $("input:text:first").focus();
                $("button.delete").click(function (e) {
                    e.preventDefault();
                    $("#idcalendarC").val(delIdcalendar);
                    $('#confirmDialog').dialog({
                        resizable: false,
                        height: 300,
                        width: 500,
                        modal: true,
                        autoOpen: true

                    });
                });

                tblCalendars = $('#calendar_list');
            });
            function fob(field)
            {
                delIdcalendar = field;
            }
        </script>
    </head>
    <body>
        <div class="title">Calendar!</div>
        <?php include('menu.html'); ?>
        <div class="menu"> 
        </div>
        <div class="main"> 
            <table>
                <tr>
                    <th>Description</th>

                    <th>Color</th>

                    <th colspan="3"> Action </th>
                </tr>
            </table>


            <?php foreach ($shares as $share): ?>

                <?php foreach ($allCalendars as $allCalendar): ?>

                    <?php if ($share->idcalendar == $allCalendar->idcalendar) : ?>
                        <?php if ($editshare == true): ?>

                            <form id="calendar_share_edit" action="calendar/update" method="post">
                                <table>
                                <input type = "text" name="description" value="<?= $allCalendar->description ?>">
                                <input type='text' name='idcalendar' value='<?= $allCalendar->idcalendar ?>' hidden>
                                <input type="color" name="color" value="#<?= $allCalendar->color ?>">
                                <input type='submit' value='update'>
                                </table>
                            </form>


                        <?php else: ?>

                            <form id="calendar_share_edit" action="calendar/edit" method="post">
                                <span style='color:#<?= $allCalendar->color ?>'><?= $allCalendar->description ?>(Partagé avec vous!)</span>
                                <input type="color" name="color" value="#<?= $allCalendar->color ?>">
                                <?php if (($share->read_only) == 0) : ?>
                                    <input type='text' name='idcalendar' value='<?= $allCalendar->idcalendar ?>' hidden>
                                    <input type='submit' value='edit'

                                       <?php endif; ?>
                            </form>

                        <?php endif ?>

                        <?php if (($share->read_only) == 0) : ?>
                            <form class='form' action='calendar/deleteDirect' method='post'>
                                <input type='text' name='idcalendar' value='<?= $allCalendar->idcalendar ?>' hidden>
                                <?php if ((Event::get_events_idcalendar($allCalendar->idcalendar)) !== []): ?>
                                    <input type='text' id='direct' name='direct' value='nodirect' hidden>
                                    <button class="delete" id="deleteCalendar" name="action" value="<?= $allCalendar->idcalendar ?>" onclick="fob(this.value)" type="submit">delete</button>
                                <?php else: ?>

                                    <input type='text' id='direct' name='direct' value='direct' hidden>
                                    <input type='submit' id='delete' name="delete" id="delete" value='delete'>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>

                    <?php endif; ?>

                <?php endforeach; ?>

            <?php endforeach; ?>


            <?php foreach ($calendars as $calendar): ?>
                <?php if ($calendar == $thecalendar && $thecalendar != '') : ?>


                    <form class='form' id="calendar_update" action="calendar/update" method="post">

                        <input type = "text" name="description" value="<?= $thecalendar->description ?>">
                        <input type='text'  name='idcalendar' value='<?= $calendar->idcalendar ?>' hidden>
                        <input type="color" name="color" value="#<?= $calendar->color ?>">

                        <input type='submit' value='update'>

                    </form>




                    <?php if ($user->iduser == $calendar->iduser): ?>
                        <form class='form' action='calendar/deleteDirect' method='post'>
                            <input type='text' name='idcalendar' value='<?= $calendar->idcalendar ?>' hidden>
                            <?php if ((Event::get_events_idcalendar($calendar->idcalendar)) !== []): ?>

                                <input type='text' id='direct' name='direct' value='nodirect' hidden>
                                <button class="delete" id="deleteCalendar" name="action" value="<?= $calendar->idcalendar ?>"  onclick="fob(this.value)" type="submit">delete</button>
                            <?php else: ?>
                                <input type='text' id='direct' name='direct' value='direct' hidden>
                                <input type='submit' id='delete' name="delete" id="delete" value='delete'>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>


                <?php else: ?>

            <tr> <t> <font color="#<?= $calendar->color ?>"><?= $calendar->description ?></font></t>

                    <input type="color" name="color" value="#<?= $calendar->color ?>">


                    <?php if ($user->iduser == $calendar->iduser): ?>

                        <form id="calendar_edit" class="form" action="calendar/edit" method='post' >
                            <table>
                             <input type='text' name="idcalendar"  value='<?= $calendar->idcalendar ?>' hidden>
                             <td><input type='submit' value='edit'></td>
                            </table>
                        </form> 

                    <?php endif; ?>


                    <?php if ($user->iduser == $calendar->iduser): ?>

                        <form class='form' action='calendar/deleteDirect' method='post'>
                            <input id="idcalendar" type='text' name='idcalendar' value='<?= $calendar->idcalendar ?>' hidden>
                            <?php if ((Event::get_events_idcalendar($calendar->idcalendar)) !== []): ?>

                                <input type='text' id='direct' name='direct' value='nodirect' hidden>
                                <button class="delete" id="deleteCalendar" name="action" value="<?= $calendar->idcalendar ?>"  onclick="fob(this.value)" type="submit">delete</button>
                            <?php else: ?>
                                <input type='text' id='direct' name='direct' value='direct' hidden>

                                <td><input type='submit' name="delete" id="delete" value='delete'></td>
                            <?php endif; ?>
                        </form>
                    

                        <div id="confirmDialog" title="Delete calendar : " style="display:none">
                            <form id="DeleteCalendarForm" name="DeleteCalendarForm" action="calendar/delete" method="post">
                                <input id="idcalendarC" type='text' name='idcalendar' value='' hidden>
                                <p>The calendar you wanted to delete contains event(s). Are you sure you want to delete?</p>
                                <input class="btn" id="cancel" type="submit" name = "cancel" value="cancel" >
                                <input class="btn" id="delete" type="submit" name = "delete" value="delete" >


                            </form>
                        </div>
                    </tr>
                    <?php endif; ?>

                    <?php if ($user->iduser == $calendar->iduser): ?>
                        <form class='form' action='calendar/share' method='post'>
                            <input type='text' name='idcalendar' value='<?= $calendar->idcalendar ?>' hidden>
                            <input type='submit' name="share_create" value='share settings'>
                        </form>
                    <?php endif; ?>

                <?php endif; ?>

            <?php endforeach; ?>


            <form class='form' id="create_calendar" action="calendar/index" method="post">

                <input type = "text" id="description" name="description" value="" placeholder="Enter description">

                <input id="color" type="color" name="color">



                <input id="post" type="submit" value="Create">


            </form>



        </div>
    </body>
</html>

