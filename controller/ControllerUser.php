<?php

require_once 'model/User.php';
require_once 'model/Calendar.php';
require_once 'model/Event.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {

    const UPLOAD_ERR_OK = 0;

    //gestion de l'édition du profil
    public function edit_profile() {
        $User = $this->get_user_or_redirect();
        $error = "";
        $success = "";
        if (isset($_POST['pseudo']) || isset($_POST['email']) || isset($_POST['full_name'])) {
            $pseudo = ($_POST['pseudo']);
            $email = ($_POST['email']);
            $full_name = ($_POST['full_name']);
            $errors = User::validate_update($pseudo, $email, $full_name);
            if (count($errors) == 0) {
                $User->pseudo = $pseudo;
                $User->email = $email;
                $User->full_name = $full_name;
                $User->update();
                $success = "Your profile has been successfully updated.";
            }
        }
        (new View("edit_profile"))->show(array("User" => $User, "error" => $error, "success" => $success));
    }

    //page d'accueil. 
    public function index() {
        $this->profile();
    }

    public function email_available_service() {
        $res = "true";
        if (isset($_POST["email"]) && $_POST["email"] !== "") {
            $user = User::get_user($_POST["email"]);
            if ($user) {
                $res = "false";
            }
        }
        echo $res;
    }
    public function email_available_serviceLog() {
        $res = "false";
        if (isset($_POST["email"]) && $_POST["email"] !== "") {
            $user = User::get_user($_POST["email"]);
            if ($user) {
                $res = "true";
            }
        }
        echo $res;
    }

    public function email_available_service2() {
        $user = $this->get_user_or_redirect();
        $res = "true";
        if (isset($_POST["email"]) && $_POST["email"] !== "" ) {
            $user = User::get_user($_POST["email"]);
            if ($user) {
                $res = "false";
            }
        }
        echo $res;
    }

    public function pseudo_available_service() {
        $res = "false";
        if (isset($_POST["pseudo"]) && $_POST["pseudo"] !== "") {
            $user = User::get_user_by_pseudo($_POST["pseudo"]);
            if ($user) {
                $res = "true";
            }
        }
        echo $res;
    }
 


    public function my_planning() {
        $user = $this->get_user_or_redirect();
//        $user = '';
        $calendar = User::get_calendars();
        (new View("my_planning"))->show(array("user" => $user, "calendars" => $calendar));
    }

    //profil de l'utilisateur connecté ou donné
    public function profile() {
        $User = $this->get_user_or_redirect();
        if (isset($_GET["id"]) && $_GET["id"] !== "") {
            $User = User::get_User($_GET["id"]);
        }
        (new View("profile"))->show(array("User" => $User));
    }

}
