<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome to Calendar !</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="css/styles.css" rel="stylesheet" type="text/css"/>

    </head>


    <body>
        <div class="title">My Calendar !</div>
        <div class="firstline">
            <div class="rightbox">

                <div class="dropdown">
                    <a>Welcome: <?php echo $user->pseudo ?></a>
                    <div class="dropdown-content">
                        <a href="User/profile">Home</a>
                        <a href="Calendar/index">My Calendar</a>
                        <a href="Planning/index">My planning </a>
                        <a href="user/edit_profile">Edit Profile</a>
                        <a href="User/logout">Log Out</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu"> 
            From <?= $daysInWeek[0] ?> to <?= $daysInWeek[6] ?> !
            <form class="link" action="planning/previous_event/<?= $user->pseudo ?>" method="post">

                <input type='number' name="previous_event" value="<?= $current_week ?>" hidden>
                <input type="submit" value ="previous week">


            </form>
            <form class="link" action="planning/next_event/<?= $user->pseudo ?>" method="post">

                <input type='number' name="next_event" value="<?= $current_week ?>" hidden>
                <input id="post" type="submit" value ="next week"></td>


            </form>
        </div>
        <div class="main"> 
            <table>
                <?php for ($i = 0; $i < 7; ++$i): ?>
                    <tr>
                        <td><?= $daysInWeek[$i] ?></td>
                        <td>
                            <?php foreach ($sevents as $sevent): ?>
                                <?php if ((new DateTime($sevent->start) <= (new DateTime($daysInWeek[$i]))) and ( new DateTime($sevent->finish) >= (new DateTime($daysInWeek[$i])))) : ?>
                            <tr>
                                <td><?= $sevent->start ?></td>
                                <td><?= $sevent->title ?><td>
                                    <?php foreach ($shares as $share): ?>
                                        <?php if ($share->read_only == 0 && $share->idcalendar== $sevent->idcalendar) : ?>

                                        <td>    <form class="link" action="planning/edit" method='post' >
                                                <input type='text' name='idevent' value='<?= $sevent->idevent ?>' hidden>
                                                <input type='submit' value='edit'>
                                            </form>
                                        </td>
                                        <td>
                                            <form class="link" action="planning/delete" method='post' >
                                                <input type='text' name='idevent' value='<?= $sevent->idevent ?>' hidden>
                                                <input type='submit' value='delete'>
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

                            <?php if ((new DateTime($event->start) <= (new DateTime($daysInWeek[$i]))) and ( new DateTime($event->finish) >= (new DateTime($daysInWeek[$i])))) : ?>
                            <tr>
                                <td><?= $event->start ?></td>
                                <td><?= $event->title ?><td>
                                <td>    <form class="link" action="planning/edit" method='post' >
                                        <input id ="idevent" type='text' name='idevent' value='<?= $event->idevent ?>' hidden>
                                        <input type='submit' value='edit'>
                                    </form>
                                </td>
                                <td>
                                    <form class="link" action="planning/delete" method='post' >
                                        <input type='text' name='idevent' value='<?= $event->idevent ?>' hidden>
                                        <input type='submit' value='delete'>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>

                    </td>
                    </tr>
                <?php endfor; ?>
                <form id="calendar_form" action="planning/create_event/<?= $user->pseudo ?>" method="post">

                    <td><input id="post" type="submit" name="New event" value="New event"></td>

                </form>
            </table>
        </div>
    </body>
</html>
