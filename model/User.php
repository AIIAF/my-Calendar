<?php

require_once "framework/Model.php";
require_once "Event.php";
require_once 'Share.php';

class User extends Model {

    public $email;
    public $pseudo;
    public $full_name;
    public $password;
    public $iduser;

    public function __construct($email, $pseudo, $full_name, $password, $iduser = null) {
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->full_name = $full_name;
        $this->password = $password;
        $this->iduser = $iduser;
    }

    public static function add_User($user) {
        self::execute("INSERT INTO user(email,pseudo,full_name,password)
                       VALUES(?,?,?,?)", array($user->email, $user->pseudo, $user->full_name, $user->password));
        $user->iduser = self::lastInsertId();
        return true;
    }

    public function update() {
        self::execute("UPDATE user SET pseudo=?, email=?, full_name=? WHERE iduser =?", array($this->pseudo, $this->email, $this->full_name, $this->iduser));
        return true;
    }

    public function get_calendars() {
        return Calendar::get_calendars($this);
    }

    public function get_sharesI() {
        return Share::get_shares_iduser($this->iduser);
    }

    public function get_shares($idcalendar) {
        return Share::get_shares($idcalendar);
    }

    public function get_share($idcalendar, $iduser2) {
        return Share::get_share($idcalendar, $iduser2);
    }

    public function get_share_t($idcalendar, $iduser2, $read_only) {
        return Share::get_share_t($idcalendar, $iduser2, $read_only);
    }

    public function get_events() {
        return Event::get_events($this);
    }

    public function get_events_by_inter($start, $end) {
        return Event::get_events_by_inter($this, $start, $end);
    }

    public function get_sevents() {
        return Event::get_sevents($this);
    }

    public static function get_users() {
        $query = self::execute("select * from user", array());
        $data = $query->fetchAll();
        $users = [];
        foreach ($data as $row) {
            $users[] = new User($row['email'], $row['pseudo'], $row['full_name'], $row['password'], $row['iduser']);
        }
        return $users;
    }

    public static function get_user($email) {
        $query = self::execute("SELECT * FROM user where email = ?", array($email));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["email"], $data["pseudo"], $data["full_name"], $data["password"], $data["iduser"]);
        }
    }

    public static function get_user_by_pseudo($pseudo) {
        $query = self::execute("SELECT * FROM user where speudo = ?", array($pseudo));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["email"], $data["pseudo"], $data["full_name"], $data["password"], $data["iduser"]);
        }
    }

    //renvoie un tableau de strings en fonction des erreurs de signup.
    public static function validate($email, $pseudo, $full_name, $password, $password_confirm) {
        $errors = [];
        $User = self::get_user($email);
        if ($User) {
            $errors[] = "This user already exists.";
        } if ($email == '') {
            $errors[] = "Email is required.";
        } if ($pseudo == '') {
            $errors[] = "Pseudo is required.";
        } if ($full_name == '') {
            $errors[] = "Full name is required.";
        } if (strlen($pseudo) < 3 || strlen($pseudo) > 16) {
            $errors[] = "pseudo length must be between 3 and 16.";
        } if (strlen($full_name) < 3 || strlen($full_name) > 16) {
            $errors[] = "Full name length must be between 3 and 16.";
        } if (!(preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email))) {
            $errors[] = "email invalid.";
        } if (strlen($password) < 8 || strlen($password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        } if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    public static function validate_update($pseudo, $email, $full_name) {
        $errors = [];
        $user = self::get_user($pseudo);
        if ($user && $user->pseudo != $_SESSION['user']->pseudo) {
            $errors[] = "This user already exists.";
        } if ($pseudo == '') {
            $errors[] = "Pseudo is required.";
        } if (strlen($pseudo) < 3 || strlen($pseudo) > 16) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $pseudo)) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        } if (!preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
            $errors[] = "email invalid.";
        } if ($email == '') {
            $errors[] = "Email is required.";
        } if ($full_name == '') {
            $errors[] = "Full name is required.";
        }
        if ($errors != [] && $errors[0] === "This user already exists." && $_SESSION['user'] == User::get_user($pseudo)->pseudo) {
            unset($errors[0]);
        }
        return $errors;
    }

    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }

    //renvoie un string en fonction de l'erreur de login.
    public static function validate_login($email, $password) {
        $error = "";
        $User = User::get_user($email);
        if ($User) {
            if (!self::check_password($password, $User->password)) {
                $error = "Wrong password. Please try again.";
            }
        } else {
            $error = "Can't find a User with the email '$email'. Please sign up.";
        }
        return $error;
    }

    public function get_id_user() {
        $query = self::execute("SELECT iduser from user where pseudo=?", array($this->pseudo));
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $data[0];
        }
    }


    public function get_visible_sharesI_as_json() {

        $str = "";
        //récupère tous les messages dont le membre courant est destinataire
        $shares = $this->get_sharesI();
        //filtre les messages pour ne garder que ceux qui sont visibles
        foreach ($shares as $share) {
            $iduser = $share->iduser;
            $idcalendar = $share->idcalendar;
            $read_only = $share->read_only;

            $iduser = json_encode($iduser);
            $idcalendar = json_encode($idcalendar);
            $read_only = json_encode($read_only);

            $str .= "{\"iduser\":$iduser,\"idcalendar\":$idcalendar,\"read_only\":$read_only},";
        }


        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "[$str]";
    }

    public static function get_visible_user_as_json($user) {
        $str = "";
        $pseudo = $user->pseudo;
        $full_name = $user->full_name;
        $password = $user->password;
        $iduser = $user->iduser;

        $pseudo = json_encode($pseudo);
        $full_name = json_encode($full_name);
        $password = json_encode($password);
        $iduser = json_encode($iduser);
        $str .= "{\"iduser\":$iduser,\"pseudo\":$pseudo,\"full_name\":$full_name,\"password\":$password},";
        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "$str";
    }

    public static function get_events_json($user, $start, $end) {
        $str = "";
        $events = $user->get_events_by_inter($start, $end);
        //filtre les messages pour ne garder que ceux qui sont visibles
        foreach ($events as $event) {
            $whole_day = $event->whole_day;
            if ($whole_day == 0) {
                $start = $event->start;
                $start = Planning::datetime_string_fullcalendar($start);
                $finish = $event->finish;
                $finish = Planning::datetime_string_fullcalendar($finish);
            } else {
                $start = $event->start;
                $start = Planning::date_string_day_fullcalendar($start);
                $finish = $event->finish;
                $finish = Planning::date_string_day_fullcalendar($finish);
            }
            $title = $event->title;
            $idcalendar = $event->idcalendar;
            $description = $event->description;
            $idevent = $event->idevent;
            $calendar = Calendar::get_calendar($idcalendar);
            $color = '#' . $calendar->color;
            $erasable = true;
            $editable = true;
            $start = json_encode($start);
            $whole_day = json_encode($whole_day);
            $title = json_encode($title);
            $idcalendar = json_encode($idcalendar);
            $finish = json_encode($finish);
            $description = json_encode($description);
            $color = json_encode($color);
            $idevent = json_encode($idevent);
            $erasable = json_encode($erasable);
            $editable = json_encode($editable);
            $str .= "{\"start\":$start,\"title\":$title,\"idcalendar\":$idcalendar,\"end\":$finish,\"description\":$description,\"idevent\":$idevent,\"color\":$color,\"erasable\":$erasable,\"editable\":$editable},";
        }
        $shares = $user->get_sharesI();
        foreach ($shares as $share) {
            if ($share->read_only == 0) {
                $events = Event::get_events_by_inter_idcalendar($share->idcalendar, $start, $end);
                foreach ($events as $event) {
                    $whole_day = $event->whole_day;
                    if ($whole_day == 0) {
                        $start = $event->start;
                        $start = Planning::datetime_string_fullcalendar($start);
                        $finish = $event->finish;
                        $finish = Planning::datetime_string_fullcalendar($finish);
                    } else {
                        $start = $event->start;
                        $start = Planning::date_string_day_fullcalendar($start);
                        $finish = $event->finish;
                        $finish = Planning::date_string_day_fullcalendar($finish);
                    }
                    $title = $event->title;
                    $idcalendar = $event->idcalendar;
                    $description = $event->description;
                    $idevent = $event->idevent;
                    $calendar = Calendar::get_calendar($idcalendar);
                    $color = '#' . $calendar->color;
                    $erasable = true;
                    $editable = true;
                    $start = json_encode($start);
                    $whole_day = json_encode($whole_day);
                    $title = json_encode($title);
                    $idcalendar = json_encode($idcalendar);
                    $finish = json_encode($finish);
                    $description = json_encode($description);
                    $color = json_encode($color);
                    $idevent = json_encode($idevent);
                    $erasable = json_encode($erasable);
                    $editable = json_encode($editable);
                    $str .= "{\"start\":$start,\"title\":$title,\"idcalendar\":$idcalendar,\"end\":$finish,\"description\":$description,\"idevent\":$idevent,\"color\":$color,\"erasable\":$erasable,\"editable\":$editable},";
                }
            } else {
                $events = Event::get_events_by_inter_idcalendar($share->idcalendar, $start, $end);
                foreach ($events as $event) {
                    $whole_day = $event->whole_day;
                    if ($whole_day == 0) {
                        $start = $event->start;
                        $start = Planning::datetime_string_fullcalendar($start);
                        $finish = $event->finish;
                        $finish = Planning::datetime_string_fullcalendar($finish);
                    } else {
                        $start = $event->start;
                        $start = Planning::date_string_day_fullcalendar($start);
                        $finish = $event->finish;
                        $finish = Planning::date_string_day_fullcalendar($finish);
                    }
                    $title = $event->title;
                    $idcalendar = $event->idcalendar;
                    $description = $event->description;
                    $idevent = $event->idevent;
                    $calendar = Calendar::get_calendar($idcalendar);
                    $color = '#' . $calendar->color;
                    if ($share->read_only == 1) {
                        $erasable = false;
                        $editable = false;
                    } else {
                        $erasable = true;
                        $editable = true;
                    }
                    $start = json_encode($start);
                    $whole_day = json_encode($whole_day);
                    $title = json_encode($title);
                    $idcalendar = json_encode($idcalendar);
                    $finish = json_encode($finish);
                    $description = json_encode($description);
                    $color = json_encode($color);
                    $idevent = json_encode($idevent);
                    $erasable = json_encode($erasable);
                    $editable = json_encode($editable);
                    $str .= "{\"start\":$start,\"title\":$title,\"idcalendar\":$idcalendar,\"end\":$finish,\"description\":$description,\"idevent\":$idevent,\"color\":$color,\"erasable\":$erasable,\"editable\":$editable},";
                }
            }
        }


        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "[$str]";
    }

}
