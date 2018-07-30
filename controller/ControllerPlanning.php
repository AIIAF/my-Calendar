<?php

require_once 'model/User.php';
require_once 'model/Event.php';
require_once 'framework/View.php';
require_once 'model/Calendar.php';
require_once 'model/Planning.php';
require_once 'model/share.php';
require_once 'framework/Controller.php';

class ControllerPlanning extends Controller {

    public function index() {
        $defaultDate = strval(date('Y-m-d'));
        $defaultView = 'month';
        $data = '';
        $file = fopen(__DIR__ . '/defaultDateView.txt', 'r');
        while (!feof($file)) {
            $data[] = fgets($file);
//            echo(fgets($file));
        }


        if ($data[0] != false) {
            $defaultDate = $data[0];
            $defaultView = $data[1];
        }

        fclose($file);
        $defaultDate = substr($defaultDate, 0, 10);



        $user = $this->get_user_or_redirect();
        $calendars = $user->get_calendars(); //r&cupère tous les calendriers correspondant à l'utilisateur
        $jour = ['Mon', 'Tue', 'Wen', 'Thu', 'Fri', 'Sat', 'Sun'];
        $current_week = Planning::current_week();
        $daysInWeek = Planning::daysInWeek($current_week);
        if (isset($_POST['title']) && $_POST['title'] != '' && isset($_POST['Create'])) {
            $Timestart = "";
            $Timefinish = "";
            $Datefinish = "";
            $finish = null;
            if (isset($_POST['Timestart']) && isset($_POST['Timefinish']) && isset($_POST['Datefinish'])) {
                $Timestart = $_POST['Timestart'];
                $Timefinish = $_POST['Timefinish'];
                $Datefinish = $_POST['Datefinish'];
                $finish = $Datefinish . " " . $Timefinish;
            }
            $Datestart = $_POST['Datestart'];
            $start = $Datestart . " " . $Timestart;
            $whole_day = isset($_POST['whole_day']) ? 1 : 0;
            $title = $_POST['title'];
            $description = $_POST['description'];
            $idcalendar = $_POST['idcalendar'];

            $eventcop = Event::get_event_cop($start, $finish, $whole_day, $title, $description, $idcalendar);
            if ($eventcop != []) {

                $this->create_Aevent();
            } else {
                $this->create_Aevent();
            }
        }





        $shares = $user->get_sharesI(); // récupère les shares  qui contient l'utilsateur
        $sevents = $user->get_sevents();
        $events = $user->get_events();
        $calendars_shares = $calendars;
        foreach ($shares as $share) {
            $calendars_shares[] = Calendar::get_calendar($share->idcalendar);
            
        }
        if ($calendars_shares == []) {
            (new View("no_calendar"))->show(array("user" => $user));
        } else {
            (new View("my_planning"))->show(array('defaultDate' => $defaultDate, 'defaultView' => $defaultView, 'calendars_shares' => $calendars_shares, 'calendars' => $calendars, 'jour' => $jour, "shares" => $shares, "sevents" => $sevents, "events" => $events, "user" => $user, "daysInWeek" => $daysInWeek, "current_week" => $current_week));
        }
    }

    public function preIndex() {

        if (isset($_POST['title']) && $_POST['title'] != '') {
            $Timestart = "";
            $Timefinish = "";
            $Datefinish = "";
            $finish = null;
            if (isset($_POST['Timestart']) && isset($_POST['Timefinish']) && isset($_POST['Datefinish'])) {
                $Timestart = $_POST['Timestart'];
                $Timefinish = $_POST['Timefinish'];
                $Datefinish = $_POST['Datefinish'];
                $finish = $Datefinish . " " . $Timefinish;
            }
            $Datestart = $_POST['Datestart'];
            $start = $Datestart . " " . $Timestart;
            $whole_day = isset($_POST['whole_day']) ? 1 : 0;
            $title = $_POST['title'];
            $description = $_POST['description'];
            $idcalendar = $_POST['idcalendar'];

            $eventcop = Event::get_event_cop($start, $finish, $whole_day, $title, $description, $idcalendar);
            if ($eventcop != []) {

                $this->create_Aevent();
            } else {
                $this->create_Aevent();
            }
        }

        $this->index();
    }

    public function update() {
        date_default_timezone_set('Europe/Brussels');
        if (isset($_POST['fullCalendarStart']) && isset($_POST['fullCalendarEnd'])) {
            $startF = $_POST['fullCalendarStart'];
            $endF = $_POST['fullCalendarEnd'];

            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');
            $defaultView = 'month';

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            }
            $myfile = fopen(__DIR__ . '/defaultDateView.txt', "w") or die("Unable to open file!");
            fwrite($myfile, $defaultDate . "\n");
            fwrite($myfile, $defaultView);
            fclose($myfile);
        }
        $user = $this->get_user_or_redirect();
        if (isset($_POST['update']) || isset($_POST['create'])) {
            $idevent = $_POST['idevent'];
            $theevent = Event::get_event($idevent);
            if (isset($_POST['title']) && $_POST['title'] != '') {
                $Timestart = "";
                $Timefinish = "";

                if (isset($_POST['Timestart']) && isset($_POST['Timefinish'])) {
                    $Timestart = $_POST['Timestart'];
                    $Timefinish = $_POST['Timefinish'];
                }
                $Datefinish = $_POST['Datefinish'];
                $Datestart = $_POST['Datestart'];
                $start = $Datestart . " " . $Timestart;
                $finish = $Datefinish . " " . $Timefinish;
                $whole_day = isset($_POST['whole_day']) ? 1 : 0;
                $title = $_POST['title'];
                $description = $_POST['description'];
                $idcalendar = $_POST['idcalendar'];

                if ($start != '' && $title != '') {
                    if ($theevent->idevent != null) {
                        $theevent->start = $start;
                        $theevent->finish = $finish;
                        $theevent->whole_day = $whole_day;
                        $theevent->title = $title;
                        $theevent->description = $description;
                        $theevent->idcalendar = $idcalendar;
                        $theevent->idevent = $idevent;
                        $theevent->updateEvent();

                        $this->redirect("planning", "index", $user->pseudo);
                        $success = "Your event  has been successfully updated.";
                    } else {
                        $event = new Event($start, $whole_day, $title, $idcalendar, $finish, $description);
                        $event->add_event($event);

                        $this->redirect("planning", "index", $user->pseudo);
                        $success = "Your event  has been successfully created.";
                    }
                }
            }
        } else if (isset($_POST['delete'])) {

            $this->remove_event();

            $this->redirect("planning", "index", $user->pseudo);
        } else if (isset($_POST['deleteC'])) {

            $this->remove_eventC();

            $this->redirect("planning", "index", $user->pseudo);
        } else if (isset($_POST['cancel'])) {
            $this->redirect("planning", "index", $user->pseudo);
        } else {
            $this->redirect("planning", "index", $user->pseudo);
        }
    }

    public function updateC() {

        if (isset($_POST['fullCalendarStartC']) && isset($_POST['fullCalendarEndC'])) {
            $startF = $_POST['fullCalendarStartC'];
            $endF = $_POST['fullCalendarEndC'];
            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');
            $defaultView = 'month';

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            }
            $myfile = fopen(__DIR__ . '/defaultDateView.txt', "w") or die("Unable to open file!");
            fwrite($myfile, $defaultDate . "\n");
            fwrite($myfile, $defaultView);
            fclose($myfile);
        }


        $user = $this->get_user_or_redirect();
        if (isset($_POST['update']) || isset($_POST['create'])) {
            $idevent = $_POST['ideventC'];
            $theevent = Event::get_event($idevent);
            if (isset($_POST['titleC']) && $_POST['titleC'] != '') {
                $Timestart = "";
                $Timefinish = "";


                if (isset($_POST['TimestartC']) && isset($_POST['TimefinishC'])) {
                    $Timestart = $_POST['TimestartC'];
                    $Timefinish = $_POST['TimefinishC'];
                }
                $Datefinish = $_POST['DatefinishC'];
                $Datestart = $_POST['DatestartC'];
                $start = $Datestart . " " . $Timestart;
                $finish = $Datefinish . " " . $Timefinish;
                $whole_day = isset($_POST['whole_dayC']) ? 1 : 0;
                $title = $_POST['titleC'];
                $description = $_POST['descriptionC'];
                $idcalendar = $_POST['idcalendarC'];

                if ($start != '' && $title != '') {
                    if ($theevent->idevent != null) {
                        $theevent->start = $start;
                        $theevent->finish = $finish;
                        $theevent->whole_day = $whole_day;
                        $theevent->title = $title;
                        $theevent->description = $description;
                        $theevent->idcalendar = $idcalendar;
                        $theevent->idevent = $idevent;
                        $theevent->updateEvent();

                        $this->redirect("planning", "index", $user->pseudo);
                        $success = "Your event  has been successfully updated.";
                    } else {
                        $event = new Event($start, $whole_day, $title, $idcalendar, $finish, $description);
                        $event->add_event($event);

                        $this->redirect("planning", "index", $user->pseudo);
                        $success = "Your event  has been successfully created.";
                    }
                }
            }
        } else if (isset($_POST['delete'])) {

            $this->remove_event();

            $this->redirect("planning", "index", $user->pseudo);
        } else if (isset($_POST['cancel'])) {

            $this->redirect("planning", "index", $user->pseudo);
        } else {
            $this->redirect("planning", "index", $user->pseudo);
        }
    }

    private function remove_event() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST['ideventC']) && $_POST['ideventC'] != "") {
            $idevent = $_POST['ideventC'];
            $event = Event::get_event($idevent);
            if ($event) {
                return $event->delete($idevent);
            }
        } else {
            return false;
        }
    }

    private function remove_eventC() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST['idevent']) && $_POST['idevent'] != "") {
            $idevent = $_POST['idevent'];
            $event = Event::get_event($idevent);
            if ($event) {
                return $event->delete($idevent);
            }
        } else {
            return false;
        }
    }

    public function previous_event() {

        date_default_timezone_set('Europe/Brussels');
        $defaultDate = (date('Y-m-d'));

        $defaultView = 'month';
        if (isset($_POST['fullCalendarStart']) && isset($_POST['fullCalendarEnd'])) {
            $startF = $_POST['fullCalendarStart'];
            $endF = $_POST['fullCalendarEnd'];

            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            } else {
                'month';
            }
        }
        if (isset($_POST['fullCalendarStartC']) && isset($_POST['fullCalendarEndC'])) {
            $startF = $_POST['fullCalendarStartC'];
            $endF = $_POST['fullCalendarEndC'];
            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            } else {
                'month';
            }
        }
        $user = $this->get_user_or_redirect();
        $calendars = $user->get_calendars();
        $jour = ['Mon', 'Tue', 'Wen', 'Thu', 'Fri', 'Sat', 'Sun'];
        $events = $user->get_events();
        $current_week = '';
        $daysInWeek = '';
        if (isset($_POST['previous_event']) && $_POST['previous_event'] != '') {
            $current_week = $_POST['previous_event'];
            $current_week = Planning::decrement_week($current_week);
            $daysInWeek = Planning::daysInWeek($current_week);
        }

        $shares = $user->get_sharesI();
        $sevents = $user->get_sevents();
        $events_json = $user->get_visible_events_as_json($user);
        $calendars_shares = $calendars;
        foreach ($shares as $share) {
            $calendars_shares[] = Calendar::get_calendar($share->idcalendar);
        }

        (new View("my_planning"))->show(array('defaultDate' => $defaultDate, 'defaultView' => $defaultView, 'calendars_shares' => $calendars_shares, 'calendars' => $calendars, 'jour' => $jour, "events_json" => $events_json, "shares" => $shares, "sevents" => $sevents, "events" => $events, "user" => $user, "daysInWeek" => $daysInWeek, "current_week" => $current_week));
    }

    public function next_event() {

        date_default_timezone_set('Europe/Brussels');
        $defaultDate = (date('Y-m-d'));

        $defaultView = 'month';
        if (isset($_POST['fullCalendarStart']) && isset($_POST['fullCalendarEnd'])) {
            $startF = $_POST['fullCalendarStart'];
            $endF = $_POST['fullCalendarEnd'];

            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            } else {
                'month';
            }
        }
        if (isset($_POST['fullCalendarStartC']) && isset($_POST['fullCalendarEndC'])) {
            $startF = $_POST['fullCalendarStartC'];
            $endF = $_POST['fullCalendarEndC'];
            $datetime1 = (new DateTime(substr($startF, 0, 15)))->format('Y-m-d');
            $time = strtotime($datetime1);
            $defaultDate = date("Y-m-d", strtotime("+1 month", $time));
            $datetime1 = new DateTime($datetime1);

            $datetime2 = (new DateTime(substr($endF, 0, 15)))->format('Y-m-d');
            $datetime2 = new DateTime($datetime2);
            $interval = $datetime1->diff($datetime2);
            $interval = $interval->format('%a');

            if ($interval == '1') {
                $defaultView = 'agendaDay';
            } else if ($interval == '7') {
                $defaultView = 'agendaWeek';
            } else {
                'month';
            }
        }
        $user = $this->get_user_or_redirect();
        $calendars = $user->get_calendars();
        $jour = ['Mon', 'Tue', 'Wen', 'Thu', 'Fri', 'Sat', 'Sun'];
        $events = $user->get_events();
        $current_week = '';
        $daysInWeek = '';
        if (isset($_POST['next_event']) && $_POST['next_event'] != '') {
            $current_week = $_POST['next_event'];
            $current_week = Planning::increment_week($current_week);
            $daysInWeek = Planning::daysInWeek($current_week);
        }
        $shares = $user->get_sharesI();
        $sevents = $user->get_sevents();
        $events_json = $user->get_visible_events_as_json($user);
        $calendars_shares = $calendars;
        foreach ($shares as $share) {
            $calendars_shares[] = Calendar::get_calendar($share->idcalendar);
        }




        (new View("my_planning"))->show(array('defaultDate' => $defaultDate, 'defaultView' => $defaultView, 'calendars_shares' => $calendars_shares, 'calendars' => $calendars, 'jour' => $jour, "events_json" => $events_json, "shares" => $shares, "sevents" => $sevents, "events" => $events, "user" => $user, "daysInWeek" => $daysInWeek, "current_week" => $current_week));
    }

    public function create_event() {
        $user = $this->get_user_or_redirect();
        $title = '';
        $description = '';
        $whole_day = '';
        $idcalendar = '';
        $start = '';
        $finish = '';
        $calendars = $user->get_calendars();
        $shares = $user->get_sharesI();
        $calendars_shares = $calendars;
        
        foreach ($shares as $share) {
            $calendars_shares[] = Calendar::get_calendar($share->idcalendar);
        }
        
        (new View("create_event"))->show(array("calendars_shares" => $calendars_shares, "user" => $user, "title" => $title, "description" => $description, "whole_day" => $whole_day, "idcalendar" => $idcalendar, "calendars" => $calendars, "start" => $start, "finish" => $finish));
    }

    public function create_Aevent() {
        if (isset($_POST['titleC']) && $_POST['titleC'] != '') {
            $Timestart = "";
            $Timefinish = "";
            $Datefinish = "";
            $finish = null;
            if (isset($_POST['TimestartC']) && isset($_POST['TimefinishC']) && isset($_POST['DatefinishC'])) {
                $Timestart = $_POST['TimestartC'];
                $Timefinish = $_POST['TimefinishC'];
                $Datefinish = $_POST['DatefinishC'];
                $finish = $Datefinish . " " . $Timefinish;
            }
            $Datestart = $_POST['DatestartC'];
            $start = $Datestart . " " . $Timestart;
            $whole_day = isset($_POST['whole_dayC']) ? 1 : 0;
            $title = $_POST['titleC'];
            $description = $_POST['descriptionC'];
            $idcalendar = $_POST['idcalendarC'];

            $event = new Event($start, $whole_day, $title, $idcalendar, $finish, $description);
            $event->add_event($event);
        }

        $this->redirect("planning", "index", $user->pseudo);
    }

    public function edit() {
        date_default_timezone_set("Europe/Brussels");
        $errors = [];
        $success = "";
        $user = $this->get_user_or_redirect();
        if (isset($_POST['idevent'])) {
            $idevent = ($_POST['idevent']);
            $theidevent = Event::get_event($idevent);
            $calendars = $user->get_calendars();
            $title = $theidevent->title;
            $description = $theidevent->description;
            $whole_day = $theidevent->whole_day;
            $idcalendar = $theidevent->idcalendar;
            $cetcalendar = Calendar::get_calendar($idcalendar);
            $start = $theidevent->start;
            $startT = new DateTime($start);

            $Datestart = $startT->format('Y-m-d');
            $Timestart = $startT->format('H:m');

            $finish = $theidevent->finish;
            $finishT = new DateTime($finish);
            $Datefinish = $finishT->format('Y-m-d');
            $Timefinish = $finishT->format('H:m');

            $shares = $user->get_sharesI();
            $calendars_shares = $calendars;
            foreach ($shares as $share) {
                $calendars_shares[] = Calendar::get_calendar($share->idcalendar);
            }

            (new View("edit_event"))->show(array("calendars_shares" => $calendars_shares, "idevent" => $idevent, "cetcalendar" => $cetcalendar, "user" => $user, "title" => $title, "description" => $description, "whole_day" => $whole_day, "idcalendar" => $idcalendar, "calendars" => $calendars, "Datestart" => $Datestart, "Timestart" => $Timestart, "Datefinish" => $Datefinish, "Timefinish" => $Timefinish));
        }
    }

    public function get_events_json1() {
        $user = $this->get_user_or_redirect();

        $full = User::get_visible_events_as_json($user);
        echo $full;
    }

    public function update_available_service() {
        $res = "true";
        if (isset($_POST["title"]) && $_POST["title"] != "" && isset($_POST["idcalendar"])) {
            $event = Event::get_event_by_title($_POST["idcalendar"], $_POST["title"]);
            if ($event != null && $event->idcalendar != $_POST["idcalendar"]) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function create_available_service() {
        $res = "true";
        if (isset($_POST["title"]) && $_POST["title"] != "" && isset($_POST["idcalendar"])) {
            $event = Event::get_event_by_title($_POST["idcalendar"], $_POST["title"]);
            if ($event != NULL) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function events_json() {
        $user = $this->get_user_or_false();
        $start = $_POST['start'];
        $end = $_POST['end'];
        echo ($user->get_events_json($user, $start, $end));
    }

}
