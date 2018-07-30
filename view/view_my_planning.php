<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome to Calendar !</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"
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
            $.validator.addMethod("idcalendarRule", function (value, element, pattern) {
                if (value !== '0')
                    return true;
                else
                    return false;
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
            var whole_day, Timestart, Datestart, Timefinish, Datefinish, errStart, errFinish, create, editEvent,
                    whole_dayC, TimestartC, DatestartC, TimefinishC, DatefinishC, errStartC, errFinishC, createC, editEventC;
            $(function () {
                $('#CreateEventForm').validate({
                    rules: {
                        titleC: {
                            required: true,
                            minlength: 3,
                            maxlength: 50,
                            regex: /^[a-zA-Z][\sa-zA-Z0-9]*$/
                        },
                        idcalendarC: {
                            required: true,
                            idcalendarRule: 0
                        },
                        descriptionC: {
                            minlength: 0,
                            maxlength: 500,
                            regex: /^[a-zA-Z][\sa-zA-Z0-9]*$/
                        },
                        DatestartC: {
                            required: true
                        },
                        TimestartC: {
                            required: true
                        },
                        DatefinishC: {//value
                            required: true,
                            checkDate: ""
                        },
                        TimefinishC: {//value
                            required: true,
                            checkTime: ""
                        }
                    },
                    messages: {
                        titleC: {
                            remote: 'this title is already taken',
                            required: 'required',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 50 characters',
                            regex: 'bad format for title!'

                        },
                        idcalendarC: {
                            required: 'required a calendar',
                            idcalendarRule: 'please select a calendar'

                        },
                        descriptionC: {
                            required: 'required description',
                            maxlength: 'maximum 500 characters',
                            regex: 'bad format for description'
                        },
                        DatestartC: {
                            required: 'required'

                        },
                        TimestartC: {
                            required: 'required'

                        },
                        DatefinishC: {
                            required: 'required',
                            checkDate: 'Date start must be bigger than Date finish '

                        },
                        TimefinishC: {
                            required: 'required',
                            checkTime: 'Time start must be bigger than Time finish'
                        }
                    }
                });
                document.onreadystatechange = function () {
                    if (document.readyState === 'complete') {
                        whole_day = document.getElementById("whole_day");
                        Timestart = document.getElementById("Timestart");
                        Timefinish = document.getElementById("Timefinish");
                        Datestart = document.getElementById("Datestart");
                        Datefinish = document.getElementById("Datefinish");
                        errStart = document.getElementById("errStart");
                        errFinish = document.getElementById("errFinish");
                        whole_dayC = document.getElementById("whole_dayC");
                        TimestartC = document.getElementById("TimestartC");
                        TimefinishC = document.getElementById("TimefinishC");
                        DatestartC = document.getElementById("DatestartC");
                        DatefinishC = document.getElementById("DatefinishC");
                        errStartC = document.getElementById("errStartC");
                        errFinishC = document.getElementById("errFinishC");
                    }
                };
                $('#myPlanning').hide();
                $("button.delete").click(function (e) {
                    e.preventDefault();
                    $('#confirmDialog').dialog({
                        resizable: false,
                        height: 300,
                        width: 500,
                        modal: true,
                        autoOpen: true
                    });
                });
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    defaultDate: '<?= $defaultDate ?>',
                    defaultView: '<?= $defaultView ?>',
                    selectable: true,
                    selectHelper: true,
                    navLinks: true,
                    editable: true,
                    eventLimit: true,
                    events: {url: 'planning/events_json',
                        type: 'POST',
                        datatype: 'json'},
                    select: function (start, end) {
                        var calendar = $('#calendar').fullCalendar('getCalendar');
                        var view = $('#calendar').fullCalendar('getView');
                        var startV = view.start._d;
                        var endV = view.end._d;

                        $("#fullCalendarStartC").val(startV);
                        $("#fullCalendarEndC").val(endV);
                        DatestartC.value = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
                        DatefinishC.value = $.fullCalendar.formatDate(end, "YYYY-MM-DD");
                        TimestartC.value = $.fullCalendar.formatDate(start, "HH:mm");
                        TimefinishC.value = $.fullCalendar.formatDate(end, "HH:mm");
                        $("#dialogCreate").dialog({
                            title: event.title,
                            width: 350,
                            modal: true,
                            autoOpen: true,
                            close: function () {

                                location.reload(true);

                            }
                        });
                    },
                    eventRender: function (event, element) {

                        element.attr('href', 'javascript:void(0);');
                        element.click(function () {
                            var calendar = $('#calendar').fullCalendar('getCalendar');
                            var view = calendar.view;
                            var startV = view.start._d;
                            var endV = view.end._d;
                            $("#fullCalendarStart").val(startV);
                            $("#fullCalendarEnd").val(endV);
                            editEvent = event;
                            $("#title").val(event.title);
                            $("#idcalendar").val(event.idcalendar);
                            $("#description").val(event.description);
                            if (event.erasable == false && event.editable == false)
                            {
                                document.getElementById('edit').disabled = true;
                                document.getElementById('delete').disabled = true;
                            }

                            document.getElementById('idevent').value = event.idevent;
                            document.getElementById("idcalendar").disabled = true;
                            document.getElementById("title").disabled = true;
                            document.getElementById("description").disabled = true;
                            if (event.allDay === true)
                            {
                                whole_day.checked = true;
                                whole_day.disabled = true;
                                Timestart.disabled = true;
                                Timefinish.disabled = true;
                                Timestart.hidden = true;
                                Timefinish.hidden = true;
                                Datefinish.disabled = true;
                                Datestart.value = moment(event.start).format("YYYY-MM-DD");
                                Datefinish.value = moment(event.end).format("YYYY-MM-DD");
                                whole_day.disabled = true;
                                Datestart.disabled = true;
                            } else
                            {

                                whole_day.checked = false;
                                whole_day.disabled = true;
                                Datestart.value = moment(event.start).format("YYYY-MM-DD");
                                Timestart.value = moment(event.start).format("HH:mm");
                                Datefinish.value = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");
                                Timefinish.value = $.fullCalendar.formatDate(event.end, "HH:mm");
                                Timestart.disabled = true;
                                Timefinish.disabled = true;
                                Datestart.disabled = true;
                                Datefinish.disabled = true;
                            }
                            $("#dialog").dialog({
                                title: event.title,
                                width: 350,
                                modal: true,
                                autoOpen: true,
                                close: function () {
                                    location.reload(true);
                                }
                            });
                        });
                    }



                });
            });
            function onChanged(field)
            {
                var checked = whole_dayC.checked;
                if (checked === true)
                {
                    TimestartC.disabled = true;
                    TimefinishC.disabled = true;
                    TimestartC.hidden = true;
                    TimefinishC.hidden = true;
                } else
                {
                    TimestartC.disabled = false;
                    TimefinishC.disabled = false;
                    TimestartC.hidden = false;
                    TimefinishC.hidden = false;
                }
            }

            function EditMode(field)
            {
                var calendar = $('#calendar').fullCalendar('getCalendar');
                var view = calendar.view;
                var startV = view.start._d;
                var endV = view.end._d;
                $("#fullCalendarStartC").val(startV);
                $("#fullCalendarEndC").val(endV);
                event = editEvent;
                $("#titleC").val(event.title);
                $("#idcalendarC").val(event.idcalendar);
                $("#descriptionC").val(event.description);
                $("#update").val('udpate');
                document.getElementById('ideventC').value = event.idevent;
                if (event.allDay === true)
                {
                    whole_dayC.checked = true;
                    TimestartC.disabled = true;
                    TimefinishC.disabled = true;
                    TimestartC.hidden = true;
                    TimefinishC.hidden = true;

                    DatestartC.value = moment(event.start).format("YYYY-MM-DD");
                    DatefinishC.value = moment(event.end).format("YYYY-MM-DD");
                } else
                {

                    DatestartC.value = moment(event.start).format("YYYY-MM-DD");
                    TimestartC.value = moment(event.start).format("HH:mm");
                    DatefinishC.value = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");
                    TimefinishC.value = $.fullCalendar.formatDate(event.end, "HH:mm");
                }
                $("#dialogCreate").dialog({
                    title: event.title,
                    width: 350,
                    modal: true,
                    autoOpen: true,
                    close: function () {

                        location.reload(true);
                    }
                });
            }
        </script>


    </head>


    <body>
        <div class="title">Planning!</div>
        <?php include('menu.html'); ?>
        <div id='calendar'></div>
        <div class="main">
        <div id="dialog" title="Update Event: " style="display:none">
            <form id="updateventForm" name="updateEventForm1" action="planning/update" method="post">
                <table>
                    <tr>
                        <td>Title :</td>

                        <td><textarea id="title" name="title" rows='1'></textarea></td>
                    </tr>
                    <tr>
                        <td>calendar :</td>
                        <td><select id="idcalendar" name="idcalendar" >
                                <option value="" hidden >Select a calendar</option>
                                <?php foreach ($calendars_shares as $calendars_share): ?>
                                    <?php if (Share::get_share($calendars_share->idcalendar, $user->iduser) != null): ?>
                                        <?php if ((Share::get_share($calendars_share->idcalendar, $user->iduser)->read_only == 0)) : ?>

                                            <option style="color:#<?= $calendars_share->color ?>" value="<?= $calendars_share->idcalendar ?>"><font color="#<?= $calendars_share->color ?>"><?= $calendars_share->description ?></font>

                                            </option>

                                        <?php endif; ?>
                                    <?php elseif (Share::get_share($calendars_share->idcalendar, $user->iduser) == null): ?>
                                        <option style="color:#<?= $calendars_share->color ?>" value="<?= $calendars_share->idcalendar ?>"><font color="#<?= $calendars_share->color ?>"><?= $calendars_share->description ?></font>

                                        </option>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </select></td>

                    </tr>
                    <tr>
                        <td>Description :</td>

                        <td><textarea id="description"  name="description"  rows='4'></textarea></td>
                    </tr>
                    <tr>
                        <td>Start time :</td>    
                        <td>
                            <input type="date" id="Datestart" name="Datestart" onchange="onChanged(this);">
                            <input type="time" id="Timestart" name="Timestart" onchange="onChanged(this);"><br>
                        <td id="errStart"></td>
                        </td>
                    </tr>
                    <tr>
                        <td>Finish time :</td> 
                        <td>

                            <input type="date" id="Datefinish" name="Datefinish" onchange="onChanged(this);">

                            <input type="time" id="Timefinish" name="Timefinish" onchange="onChanged(this);"><br>
                        <td id="errFinish"></td>

                        </td>
                    </tr>
                    <tr>
                        <td><input id="whole_day" name="whole_day" type="checkbox" onchange="onChanged(this);">Whole day event<br></td>
                    </tr>
                    <input id="idevent" type='text' name='idevent' value=""   hidden="">
                </table>  
                <input id="iduser" type='text' name='iduser' value="<?= $user->iduser ?>"   hidden="">
                <input id="fullCalendarStart" type='text' name='fullCalendarStart'    hidden="">
                <input id="fullCalendarEnd" type='text' name='fullCalendarEnd'    hidden="">
                <input class="btn" id="delete" type="submit" name = "deleteC" value="delete" >

            </form>
            <input class="btn" id="edit" type="button" value="edit" onClick="EditMode(this)">
        </div>
        <div id="dialogCreate" title="Create Event: " style="display:none">
            <form id="CreateEventForm" name="CreateEventForm" action="planning/updateC" method="post">
                <table>
                    <tr>
                        <td>Title :</td>

                        <td><textarea id="titleC" name="titleC" rows='1'></textarea></td>
                    </tr>
                    <tr>
                        <td>calendar :</td>
                        <td><select id="idcalendarC" name="idcalendarC" >
                                <option value="0" hidden >Select a calendar</option>
                                <?php foreach ($calendars_shares as $calendars_share): ?>
                                    <?php if (Share::get_share($calendars_share->idcalendar, $user->iduser) != null): ?>
                                        <?php if ((Share::get_share($calendars_share->idcalendar, $user->iduser)->read_only == 0)) : ?>

                                            <option style="color:#<?= $calendars_share->color ?>" value="<?= $calendars_share->idcalendar ?>"><font color="#<?= $calendars_share->color ?>"><?= $calendars_share->description ?></font>

                                            </option>

                                        <?php endif; ?>
                                    <?php elseif (Share::get_share($calendars_share->idcalendar, $user->iduser) == null): ?>
                                        <option style="color:#<?= $calendars_share->color ?>" value="<?= $calendars_share->idcalendar ?>"><font color="#<?= $calendars_share->color ?>"><?= $calendars_share->description ?></font>

                                        </option>
                                    <?php endif; ?>


                                <?php endforeach; ?>
                            </select></td>

                    </tr>
                    <tr>
                        <td>Description :</td>

                        <td><textarea id="descriptionC"  name="descriptionC"  rows='4'></textarea></td>
                    </tr>
                    <tr>
                        <td>Start time :</td>    
                        <td>
                            <input type="date" id="DatestartC" name="DatestartC">
                            <input type="time" id="TimestartC" name="TimestartC"><br>
                        <td id="errStartC"></td>
                        </td>
                    </tr>
                    <tr>
                        <td>Finish time :</td> 
                        <td>

                            <input type="date" id="DatefinishC" name="DatefinishC" >

                            <input type="time" id="TimefinishC" name="TimefinishC" ><br>
                        <td id="errFinishC"></td>

                        </td>
                    </tr>
                    <tr>
                        <td><input id="whole_dayC" name="whole_dayC" type="checkbox" onchange="onChanged(this);">Whole day event<br></td>
                    </tr>
                    <input id="ideventC" type='text' name='ideventC' value=""   hidden="">
                    <input id="update" type='text' name='update' value=""   hidden="">
                    <input id="fullCalendarStartC" type='text' name='fullCalendarStartC'    hidden="">
                    <input id="fullCalendarEndC" type='text' name='fullCalendarEndC'    hidden="">
                </table>

                <input class="btn" id="create" type="submit" name = "create" value="create" >


            </form>
        </div>

        

        <div class="myPlanning" id="myPlanning" >
            <div class="menu" id="menu"> 
                <h1 align="center">From <?= (new DateTime($daysInWeek[0]))->format('d/m/Y') ?> to <?= (new DateTime($daysInWeek[6]))->format('d/m/Y') ?> </h1>

                <form id='planningWeekFormPrev' class="link" action="planning/previous_event/<?= $user->pseudo ?>" method="post">

                    <input type='number' name="previous_event" value="<?= $current_week ?>" hidden>
                    <input type="submit" value ="previous week" style="float: left;">


                </form>

                <form id='planningWeekFormNext' class="link" action="planning/next_event/<?= $user->pseudo ?>" method="post">

                    <input type='number' name="next_event" value="<?= $current_week ?>" hidden>
                    <input id="post" type="submit" value ="next week" style="float: right;">


                </form>
            </div>
                <br></br>
                <table>
                    <?php for ($i = 0; $i < 7; ++$i): ?>
                        <tr>
                            <td><h1><?= $jour[$i] ?> <?php
                                    $date = new DateTime($daysInWeek[$i]);
                                    echo $date->format('d/m/Y');
                                    ?></h1>

                                <hr>
                            </td>

                            <td>
                                <?php foreach ($sevents as $sevent): ?>
                                    <?php if ((new DateTime($sevent->start) <= (new DateTime($daysInWeek[$i]))) and ( new DateTime($sevent->finish) >= (new DateTime($daysInWeek[$i])))) : ?>
                                <tr>
                                    <?php if ((new DateTime($sevent->start))->format('Y-m-d') == (new DateTime($daysInWeek[$i]))->format('Y-m-d')): ?>
                                        <td><font color="#<?= Calendar::get_calendar($sevent->idcalendar)->color ?>"> <?= (new DateTime($sevent->start))->format('H\hi ') ?> >></font> </td>
                                    <?php elseif ((new DateTime($sevent->finish))->format('Y-m-d') == (new DateTime($daysInWeek[$i]))->format('Y-m-d')): ?>
                                        <td><font color="#<?= Calendar::get_calendar($sevent->idcalendar)->color ?>"><< <?= (new DateTime($sevent->finish))->format('H\hi ') ?> </font></td>
                                    <?php else: ?>
                                        <td><font color="#<?= Calendar::get_calendar($sevent->idcalendar)->color ?>">  All day </font></td>
                                    <?php endif; ?>
                                    <td><font color="#<?= Calendar::get_calendar($sevent->idcalendar)->color ?>">  <?= $sevent->title ?> </font></td>
                                    <?php foreach ($shares as $share): ?>
                                        <?php if ($share->read_only == 0 && $share->idcalendar == $sevent->idcalendar) : ?>

                                            <td>    <form  id='planningFormEdit' class="link" action="planning/edit" method='post' >
                                                    <input type='text' name='idevent' value='<?= $sevent->idevent ?>' hidden>
                                                    <input type='submit' value='edit'>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </td>
                        <td>


                            <?php foreach ($events as $event): ?>

                                <?php if ((new DateTime($event->start))->format('Y-m-d') == (new DateTime($daysInWeek[$i]))->format('Y-m-d') or ( new DateTime($event->start) <= (new DateTime($daysInWeek[$i]))) and ( new DateTime($event->finish) >= (new DateTime($daysInWeek[$i])))) : ?>
                                <tr>

                                    <?php if ((new DateTime($event->start))->format('Y-m-d') == (new DateTime($daysInWeek[$i]))->format('Y-m-d')): ?>
                                        <td><font color="#<?= Calendar::get_calendar($event->idcalendar)->color ?>"> <?= (new DateTime($event->start))->format('H\hi ') ?> >></font> </td>
                                    <?php elseif ((new DateTime($event->finish))->format('Y-m-d') == (new DateTime($daysInWeek[$i]))->format('Y-m-d')): ?>
                                        <td><font color="#<?= Calendar::get_calendar($event->idcalendar)->color ?>"><< <?= (new DateTime($event->finish))->format('H\hi ') ?> </font></td>
                                    <?php else: ?>                           
                                        <td><font color="#<?= Calendar::get_calendar($event->idcalendar)->color ?>">  All day </font></td>
                                    <?php endif; ?>


                                    <td><font color="#<?= Calendar::get_calendar($event->idcalendar)->color ?>">  <?= $event->title ?> </font></td>
                                    <td>    <form id='planningFormEventEdit' class="link" action="planning/edit" method='post' >
                                            <input id ="idevent" type='text' name='idevent' value='<?= $event->idevent ?>' hidden>
                                            <input type='submit' value='edit'>
                                        </form>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php endforeach; ?>

                        </td>
                        </tr>
                    <?php endfor; ?>
                    <form id="calendar_form" action="planning/create_event" method="post">

                        <td><input id="post" type="submit" name="New event" value="New event"></td>

                    </form>
                </table>
            </div>
        </div>

    </body>
</html>