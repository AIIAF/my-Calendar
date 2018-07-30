<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller {

    //si l'utilisateur est conecté, redirige vers son User.
    //sinon, produit la vue d'accueil.
    public function index() {
        if ($this->user_logged()) {
            $this->redirect("User","profile");
        } else {
            (new View("index"))->show();
        }
    }

    //gestion de la connexion d'un utilisateur
    public function login() {
        $email = '';
        $password = '';
        $error = '';
        if (isset($_POST['email']) && isset($_POST['password'])) { //note : pourraient contenir
        //des chaînes vides
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error = User::validate_login($email,$password);
            if ($error === "") {
                $this->log_user(User::get_User($email));
             
            }
        }
        (new View("login"))->show(array("email" => $email, "password" => $password, "error" => $error));
    }

    //gestion de l'inscription d'un utilisateur
    public function signup() {
        $email = '';
        $pseudo = '';
        $full_name ='';
        $password = '';
        $password_confirm = '';
        $errors = [];   
        if(isset($_POST['pseudo']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['password_confirm'])  && isset($_POST['email']) ) {
            $email = trim($_POST['email']);
            $pseudo = trim($_POST['pseudo']);
            $full_name = trim($_POST['full_name']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];


            $errors = User::validate($email,$pseudo,$full_name, $password, $password_confirm);

            if (count($errors) == 0) {

                $User = new User($email,$pseudo,$full_name, Tools::my_hash($password));
                User::add_User($User);
                $this->log_user($User);
            }
        }
        (new View("signup"))->show(array("email" => $email,"pseudo" => $pseudo,"full_name" => $full_name, "password" => $password, "password_confirm" => $password_confirm, "errors" => $errors));
    }
    public function edit() {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            $description = $_POST["description"];
            (new View("edit_calendar"))->show(array("msg" => "Edit - $id - $description"));
        }
    }
    
    public function delete() {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            (new View("delete_calendar"))->show(array("msg" => "Delete - $id"));
        }
    }
    public function email_available_service(){
        $res = "true";
        if(isset($_POST["email"]) && $_POST["email"] != ""){
            $member = User::get_user_by_email($_POST["email"]);
            if($member != null){
                $res = "false";
            }
        }
        echo $res;
    }
    
    

}
