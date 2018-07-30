
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create event</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="Lib/jquery-3.1.1.min.js" type="text/javascript"></script>


        <link href="lib/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet" />

        <link href='lib/fullcalendar-3.4.0/fullcalendar.min.css' rel='stylesheet' />

        <link href="lib/jquery-ui-themes-1.12.1/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet" />
        <script src="lib/jquery.js"></script>
        <script src="lib/moment.js"></script>
        <script src="Lib/fullcalendar-3.4.0/fullcalendar.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="Lib/jquery-validation-1.16.0/jquery.validate.min.js" type="text/javascript"></script>

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

            $.validator.addMethod("checkDate", function (value, element, pattern) {
                if (document.getElementById("DatestartC").value <= document.getElementById("DatefinishC").value)
                {
                    return true;
                } else
                    return false;

            }, "Please enter a valid input.");



            $.validator.addMethod("checkTime", function (value, element, pattern) {

                if (document.getElementById("DatestartC").value == document.getElementById("DatefinishC").value) {

                    if (document.getElementById("TimestartC").value < document.getElementById("TimefinishC").value)
                    {
                        return true;
                    } else
                        return false;

                } else if (document.getElementById("DatestartC").value < document.getElementById("DatefinishC").value) {
                    return true;

                }

            }, "Please enter a valid input.");

            $(function () {


                $("#EditEventForm").validate({
                    rules: {
                        titleC: {
                            required: true,
                            minlength: 2,
                            maxlength: 16
                        },
                        descriptionC: {
                            required: true,
                            maxlength: 500
                        },
                        DatestartC: {
                            required: true
                        },
                        DatefinishC: {
                            required: true,
                            checkDate: ""


                        },
                        TimestartC: {
                            required: true
                        },
                        TimefinishC: {
                            required: true,
                            checkTime: ""


                        }
                    },
                    messages: {
                        titleC: {
                            required: 'specify title',
                            minlength: 'minimum 2 characters',
                            maxlength: 'maximum 16 characters'
                        },
                        descriptionC: {
                            required: 'specify description',
                            maxlength: 'maximum 500 characters'
                        },
                        DatestartC: {
                            required: 'specify start'


                        },
                        DatefinishC: {
                            required: 'specify finish',
                            checkDate: 'dateFinish doit être plus grande que dateStart'

                        },
                        TimestartC: {
                            required: 'specify start'


                        },
                        TimefinishC: {
                            required: 'specify finish',
                            checkTime: 'timeFinish doit être plus grand que timeStart'

                        }

                    }
                });
                $("input:text:first").focus();
            });
            var whole_day, Timestart, Datestart, Timefinish, Datefinish, errStart, errFinish;
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    whole_day = document.getElementById("whole_dayC");
                    Timestart = document.getElementById("TimestartC");
                    Timefinish = document.getElementById("TimefinishC");
                    Datestart = document.getElementById("DatestartC");
                    Datefinish = document.getElementById("DatefinishC");
                    errStart = document.getElementById("errStartC");
                    errFinish = document.getElementById("errFinishC");

                }
            };
            function onChanged(field)
            {
                var checked = whole_day.checked;
                if (checked === true)
                {
                    Timestart.disabled = true;
                    Timefinish.disabled = true;
                    Timestart.hidden = true;
                    Timefinish.hidden = true;
                } else
                {
                    Timestart.disabled = false;
                    Timefinish.disabled = false;
                    Timestart.hidden = false;
                    Timefinish.hidden = false;
                }
            }

        </script>
    </head>
    <body>
        <div class="title">Edit event!</div>
        <?php include('menu.html'); ?>
        <a href="http://localhost/prwb_1617_G13/Planning/index">Back</a>
        <form id="EditEventForm" action="planning/updateC" method="post">
                    <table>
                        <tr>
                            <td>Title :</td>

                            <td><textarea id="titleC" name="titleC" rows='1' ><?= $title ?></textarea><br></td>
                        </tr>
                        <tr>
                            <td>calendar :</td>
                            <td><select id="idcalendarC" name="idcalendarC" >
                                    <?php foreach ($calendars_shares as $calendars_share): ?>

                                        <option style="color:#<?= $calendars_share->color ?>" value="<?= $calendars_share->idcalendar ?>" value="<?= $calendars_share->description ?>"><font color="#<?= $calendars_share->color ?>"><?= $calendars_share->description ?></font>

                                        </option>


                                    <?php endforeach; ?>
                                </select></td>


                        </tr>
                        <tr>
                            <td>Description :</td>
                            <td><textarea id="descriptionC"  name="descriptionC"  rows='4'><?= $description ?></textarea><br></td>
                        </tr>
                        <?php date_default_timezone_set('Europe/Brussels'); ?>
                        <tr>
                            <td>Start time :</td>  
                            <td>
                                <input type="date" id="DatestartC" name="DatestartC"  value="<?= $Datestart?>">
                                <input type="time" id="TimestartC" name="TimestartC" value="<?= $Timestart?>"><br>
                            <td id="errStart"></td>
                            </td>
                        </tr>
                        <tr>
                            <td>Finish time :</td> 
                            <td>

                                <input type="date" id="DatefinishC" name="DatefinishC" value="<?= $Datefinish?>">

                                <input type="time" id="TimefinishC" name="TimefinishC"  value="<?= $Timefinish?>"><br>
                            <td id="errFinish"></td>

                            </td>
                        </tr>
                        <tr>
                        
                                <?php if ($whole_day == 1): ?>
                                <td><input id="whole_dayC" name="whole_dayC"type="checkbox" checked="" onchange="onChanged(this);" >Whole day event<br></td>
                            <?php else: ?>
                                <td><input id="whole_dayC" name="whole_dayC" value="<?= $whole_day ?>" type="checkbox" onchange="onChanged(this);">Whole day event<br></td>
                            <?php endif; ?>

                    
                        </tr>
                    </table>
                    <input  type='text' id='ideventC' name='ideventC' value='<?= $idevent ?>' hidden>      
                    <input type='submit' id="update" name="update" value='update'>
                    
                    <input type='submit' id="cancel" name="cancel" value='cancel'>
                    
       

                </form>
        <form id="EditEventForm" action="planning/updateC" method="post">
            <input  type='text' id='ideventC' name='ideventC' value='<?= $idevent ?>' hidden>  
            <input type='submit' id="delete" name="delete" value='delete'>
        </form>
    </div>
</body>
</html>
