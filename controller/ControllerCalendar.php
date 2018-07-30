<?php

require_once 'framework/Controller.php';
require_once 'model/Calendar.php';
require_once 'model/User.php';
require_once 'model/Share.php';

class ControllerCalendar extends Controller {

    public function index() {
        $user = $this->get_user_or_redirect();
        $user_json = User::get_visible_user_as_json($user);
        $thecalendar = '';
        $editshare = false;
        $thecalendar_json = Calendar::get_visible_thecalendar_as_json();
        if (isset($_POST['description']) && $_POST['description'] != '') {
            $description = $_POST['description'];
            $color = $_POST['color'];
            $calendarcop = Calendar::get_calendar_cop($user, $color, $description);
            if ($calendarcop != []) {
                if ($calendarcop[0]->color != substr($_POST['color'], 1) && $calendarcop[0]->description != $_POST['description']) {
                    $this->post($user);
                }
            } else {
                $this->post($user);
            }
        }
        $allCalendars = Calendar::get_AllCalendars();
        $allCalendars_json = Calendar::get_visible_allcalendars_as_json();
        $shares = $user->get_sharesI();
        $shares_json = $user->get_visible_sharesI_as_json();
        $calendars = $user->get_calendars();
        $calendars_json = Calendar::get_visible_calendars_as_json($user);
        (new View("my_calendar"))->show(array("editshare" => $editshare, "thecalendar_json" => $thecalendar_json, "user_json" => $user_json, "calendars_json" => $calendars_json, "shares_json" => $shares_json, "allCalendars_json" => $allCalendars_json, "allCalendars" => $allCalendars, "shares" => $shares, "thecalendar" => $thecalendar, "user" => $user, "calendars" => $calendars));
//        (new View("test"))->show();
    }

    public function share_delete() {
        $idcalendar = $_POST['idcalendar'];
        $iduser2 = $_POST['iduser2'];
        $share = Share::get_share_iduser($iduser2);
        $share->delete1($idcalendar, $iduser2);
        $this->share();
    }

    public function share_create() {
        $user = $this->get_user_or_redirect();
        $iduser = $_POST['iduser'];
        $read_only = isset($_POST['read_only']) ? 1 : 0;
        $idcalendar = $_POST['idcalendar'];
        $sharecop = Share::get_share_iduser($iduser);

        if ($iduser != $user->iduser && $idcalendar != $sharecop->idcalendar) {
            $share = new Share($iduser, $idcalendar, $read_only);
            $share->addShare($share);
        }
    }

    private function post($iduser) {
        if (isset($_POST['description']) && $_POST['description'] != '') {
            $description = $_POST['description'];
            $color = substr(($_POST['color']), 1);
            $calendar = new Calendar($description, $color, $iduser);

            $calendar->add_calendar($calendar);

            return true;
        } else {
            return false;
        }
    }

    public function share_edit() {
        $errors = [];
        $success = "";

        $users = User::get_users();
        $user = $this->get_user_or_redirect();

        if (isset($_POST['iduser2'])) {
            $idcalendar = $_POST['idcalendar'];
            $iduser2 = $_POST['iduser2'];
            $theshare = Share::get_share($idcalendar, $iduser2);
            $read_only = $theshare->read_only;
            $shares = $user->get_shares($idcalendar);
            (new View("share_calendar"))->show(array("read_only" => $read_only, 'iduser2' => $iduser2, "idcalendar" => $idcalendar, "theshare" => $theshare, "shares" => $shares, "user" => $user, "users" => $users));
        }
    }

    function unsetValue(array $array, $value, $strict = TRUE) {
        if (($key = array_search($value, $array, $strict)) !== FALSE) {
            unset($array[$key]);
        }
        return $array;
    }

    public function share() {
        $forShareUser = []; // utilisateurs sharables
        $user = $this->get_user_or_redirect();
        $users = User::get_users(); //tous les users 
        $theshare = '';
        $idcalendar = $_POST['idcalendar'];
        $shareduser = []; // utilisateurs non sharables
        foreach ($users as $user1) {
            if (Share::get_share($idcalendar, $user1->iduser) == false) { //si le calendrier est partagé avec ce suser

                $forShareUser[] = $user1; // un tableau de users partageable /!\ enlever le user courant
            }
        }
        $shares = $user->get_shares($idcalendar);  // retourne un tableau de share correspondant à ce calendrier
        if (isset($_POST['iduser']) && $_POST['iduser'] != 'Select pseudo') {
            $share_calendar = $_POST['share_calendar'];
            $read_only = isset($_POST['read_only']) ? 1 : 0;
            $tShare = $user->get_share_t($idcalendar, $_POST['iduser'], $read_only);
            if ($tShare->idcalendar == null && $tShare->iduser == null && $tShare->read_only == null) {
                $this->share_create();
            }
            $this->redirect("calendar", "index", $user->pseudo);
        } else {


            (new View("share_calendar"))->show(array('forShareUser' => $forShareUser, "theshare" => $theshare, "shares" => $shares, "idcalendar" => $idcalendar, "user" => $user, "users" => $users));
        }
    }

    public function share_update() {
        $iduser2 = $_POST['iduser2'];
        $idcalendar = $_POST['idcalendar'];
        $read_only = isset($_POST['read_only']) ? 1 : 0;
        $theshare = Share::get_share($idcalendar, $iduser2);
        $theshare->read_only = $read_only;
        $theshare->updateShare();
        $this->redirect("calendar", "index", $user->pseudo);
    }

    public function update() {
        $user = $this->get_user_or_redirect();
        $idcalendar = ($_POST['idcalendar']);
        $thecalendar = Calendar::get_calendar($idcalendar);
        if (isset($_POST['description']) && isset($_POST['color'])) {
            $description = ($_POST['description']);
            $color = substr(($_POST['color']), 1);
            $thecalendar->description = $description;
            $thecalendar->color = $color;
            $thecalendar->updateCalendar();
            $success = "Your calendar has been successfully updated.";
        }
        $_POST['description'] = '';
        $this->redirect("calendar", "index", $user->pseudo);
    }

    public function edit() {
        $errors = [];
        $success = "";
        $allCalendars = Calendar::get_AllCalendars();
        $idcalendar = $_POST['idcalendar'];
        $user = $this->get_user_or_redirect();
        $shares = $user->get_shares($idcalendar);
        if (isset($_POST['idcalendar'])) {
            $idcalendar = ($_POST['idcalendar']);
            $calendars = $user->get_calendars();
            $editshare = true;
            $thecalendar = Calendar::get_calendar($idcalendar);
            (new View("my_calendar"))->show(array("editshare" => $editshare, "allCalendars" => $allCalendars, "shares" => $shares, "thecalendar" => $thecalendar, "user" => $user, "calendars" => $calendars, "errors" => $errors, "success" => $success));
        }
    }

    public function deleteView() {
        if (isset($_POST['idcalendar'])) {
            $idcalendar = $_POST['idcalendar'];
            (new View("delete_calendar"))->show(array("idcalendar" => $idcalendar));
        }
    }

    public function deleteDirect() {
        if ($_POST['direct'] == 'direct') {
            $this->delete();
        } elseif ($_POST['direct'] == 'nodirect') {
            $this->deleteView();
        }
    }

    public function delete() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST['delete'])) {
            $calendar = $this->remove_calendar();

            if ($calendar) {
                $this->redirect("calendar", "index", $user->pseudo);
            } else {
                throw new Exception("Wrong/missing ID or action no permitted");
            }
        } else {
            $this->redirect("calendar", "index", $user->pseudo);
        }
    }

    private function remove_calendar() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST['idcalendar']) && $_POST['idcalendar'] != "") {
            $idcalendar = $_POST['idcalendar'];
            $calendar = Calendar::get_calendar($idcalendar);
            $events = Event::get_events_idcalendar($idcalendar);
            $shares = Share::get_shares($idcalendar);
            if ($calendar) {
                foreach ($shares as $share) {
                    $share->delete($idcalendar);
                }
                foreach ($events as $event) {

                    $idevent = $event->idevent;
                    $event->delete($idevent);
                }
                return $calendar->delete($user);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function description_available_service_edit() {
        $user = $this->get_user_or_redirect();
        $res = "true";
        if (isset($_POST["description"]) && $_POST["description"] != "" && isset($_POST["idcalendar"])) {
            $calendar = Calendar::get_calendar_by_description_id($_POST["description"], $_POST["idcalendar"]);
            if ($calendar != null) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function description_available_service() {
        $user = $this->get_user_or_redirect();
        $res = "true";
        if (isset($_POST["description"]) && $_POST["description"] != "") {
            $calendar = Calendar::get_calendar_by_description($_POST["description"]);
            if ($calendar != null) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function delete_service() {
        $calendar = $this->remove_calendar();
        if ($calendar) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function get_visible_calendars_service() {
        $user = $this->get_user_or_redirect();
        $calendars_json = Calendar::get_visible_calendars_as_json($user);
        echo $calendars_json;
    }

}
