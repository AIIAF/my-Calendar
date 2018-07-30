<?php

require_once 'framework/Model.php';

class Calendar extends Model {

    public $description;
    public $color;
    public $iduser;
    public $idcalendar;

    public function __construct($description, $color, $iduser, $idcalendar = NULL) {
        $this->description = $description;
        $this->color = $color;
        $this->iduser = $iduser;
        $this->idcalendar = $idcalendar;
    }

    public static function get_calendar_cop($user, $color, $description) {
        $query = self::execute("select * from calendar where iduser =:iduser and color =:color and description =:description", array("iduser" => $user->iduser, "color" => substr($color, 1), "description" => $description));
        $data = $query->fetchAll();
        $calendar = [];
        foreach ($data as $row) {
            $calendar[] = new Calendar($row['description'], $row['color'], $row['iduser'], $row["idcalendar"]);
        }
        return $calendar;
    }

    public static function get_calendars($user) {
        $query = self::execute("select * from calendar where iduser = :iduser", array("iduser" => $user->iduser));
        $data = $query->fetchAll();
        $calendars = [];
        foreach ($data as $row) {
            $calendars[] = new Calendar($row['description'], $row['color'], $row['iduser'], $row["idcalendar"]);
        }
        return $calendars;
    }

    public static function get_AllCalendars() {
        $query = self::execute("select * from calendar", array());
        $data = $query->fetchAll();
        $calendars = [];
        foreach ($data as $row) {
            $calendars[] = new Calendar($row['description'], $row['color'], $row['iduser'], $row["idcalendar"]);
        }
        return $calendars;
    }

    public static function get_calendar($idcalendar) {
        $query = self::execute("SELECT * from calendar WHERE idcalendar = ?", array($idcalendar));
        $calendar = $query->fetch();
        return new Calendar($calendar["description"], $calendar["color"], $calendar["iduser"], $calendar["idcalendar"]);
    }

    public static function add_calendar($calendar) {
        self::execute('INSERT INTO Calendar (description, color, iduser) VALUES (:description,:color,:iduser)', array(
            'description' => $calendar->description,
            'color' => $calendar->color,
            'iduser' => $calendar->iduser->iduser
        ));
        $calendar->idcalendar = self::lastInsertId();
        return true;
//        return self::get_calendar(self::lastInsertId());
    }

    public function delete($initiator) {

            self::execute('DELETE FROM Calendar WHERE idcalendar = :idcalendar', array('idcalendar' => $this->idcalendar));

            return $this;

    }

    public function updateCalendar() {
        $query = self::execute("UPDATE calendar SET description=?, color=? WHERE idcalendar = ?", array($this->description, $this->color, $this->idcalendar));
        return true;
    }

    public static function get_calendar_by_description_id($description, $idcalendar) {
        $query = self::execute("SELECT * FROM calendar where description = ? AND idcalendar != ?", array($description, $idcalendar));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0)
            return NULL;
        else
            return new calendar($data["description"], $data["color"], $data["iduser"], $data["idcalendar"]);
    }

    public static function get_calendar_by_description($description) {
        $query = self::execute("SELECT * FROM calendar where description = ?", array($description));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0)
            return NULL;
        else {
            return new calendar($data["description"], $data["color"], $data["iduser"], $data["idcalendar"]);
        }
    }

    public static function get_visible_allcalendars_as_json() {
        $str = "";
        $calendars = Calendar::get_AllCalendars();
        foreach ($calendars as $calendar) {
            $description = $calendar->description;
            $color = $calendar->color;
            $iduser = $calendar->iduser;
            $idcalendar = $calendar->idcalendar;

            $description = json_encode($description);
            $color = json_encode($color);
            $iduser = json_encode($iduser);
            $idcalendar = json_encode($idcalendar);
            $str .= "{\"description\":$description,\"color\":$color,\"iduser\":$iduser,\"idcalendar\":$idcalendar},";
        }


        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "[$str]";
    }

    public static function get_visible_calendars_as_json($user) {
        $str = "";
        $calendars = Calendar::get_calendars($user);
        foreach ($calendars as $calendar) {
            $description = $calendar->description;
            $color = $calendar->color;
            $iduser = $calendar->iduser;
            $idcalendar = $calendar->idcalendar;

            $description = json_encode($description);
            $color = json_encode($color);
            $iduser = json_encode($iduser);
            $idcalendar = json_encode($idcalendar);
            $str .= "{\"description\":$description,\"color\":$color,\"iduser\":$iduser,\"idcalendar\":$idcalendar},";
        }


        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "[$str]";
    }

    public static function get_visible_thecalendar_as_json() {
        $str = "";
        $str .= "{},";
        if ($str !== "")
            $str = substr($str, 0, strlen($str) - 1);
        return "[$str]";
    }

}
