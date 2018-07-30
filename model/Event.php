<?php

require_once "framework/Model.php";
require_once "User.php";

class Event extends Model {

    public $idevent;
    public $start;
    public $finish;
    public $whole_day;
    public $title;
    public $description;
    public $idcalendar;

    public function __construct($start, $whole_day, $title, $idcalendar, $finish = null, $description = null, $idevent = null) {

        $this->start = $start;
        $this->whole_day = $whole_day;
        $this->title = $title;
        $this->idcalendar = $idcalendar;
        $this->finish = $finish;
        $this->description = $description;
        $this->idevent = $idevent;
    }

    public static function get_event_cop($start, $finish, $whole_day, $title, $description, $idcalendar) {
        $query = self::execute("select * from event where start =:start and finish=:finish and whole_day=:whole_day and title=:title and description=:description and idcalendar=:idcalendar", array("start" => $start, "finish" => $finish, "whole_day" => $whole_day, "title" => $title, "description" => $description, "idcalendar" => $idcalendar));  
        $data = $query->fetchAll();
        $event = [];
        foreach ($data as $row) {
            $event[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description']);
        }
        return $event;
    }

    public static function get_event($idevent) {
        $query = self::execute("SELECT * from Event WHERE idevent = ?", array($idevent));
        $row = $query->fetch();
        return new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
    }

    public static function get_events_idcalendar($idcalendar) {
        $query = self::execute("SELECT * FROM Event where Event.idcalendar=?", array($idcalendar));
        $data = $query->fetchAll();
        $events = [];
        foreach ($data as $row) {
            $events[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
        }
        return $events;
    }


    public static function get_events($user) {
        $query = self::execute("SELECT * FROM Event, Calendar where Calendar.idcalendar=Event.idcalendar and Calendar.iduser=?", array($user->iduser));
        $data = $query->fetchAll();
        $events = [];
        foreach ($data as $row) {
            $events[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
        }
        return $events;
    }
    public static function get_events_by_inter($user,$start,$end) {
        $query = self::execute("SELECT * FROM Event, Calendar where Calendar.idcalendar=Event.idcalendar and Calendar.iduser= ".$user->iduser." and( (Event.start between '".$start."' and '".$end."' ) or (Event.finish between '".$start."' and '".$end."'))",array($user->iduser,$start,$end));
        $data = $query->fetchAll();
        $events = [];
        foreach ($data as $row) {
            $events[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
        }
        return $events;
    }
    public static function get_events_by_inter_idcalendar($idcalendar,$start,$end) {
        $query = self::execute("SELECT * FROM Event, Calendar where Calendar.idcalendar=Event.idcalendar and Calendar.idcalendar= ".$idcalendar." and( (Event.start between '".$start."' and '".$end."' ) or (Event.finish between '".$start."' and '".$end."'))",array($idcalendar,$start,$end));
        $data = $query->fetchAll();
        $events = [];
        foreach ($data as $row) {
            $events[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
        }
        return $events;
    }

    public static function get_sevents($user) { // on récupère tous les events shares dont de l'utilisateur 
        $query = self::execute("SELECT * FROM Event, Share where Event.idcalendar=Share.idcalendar and Share.iduser=?", array($user->iduser));
        $data = $query->fetchAll();
        $events = [];
        foreach ($data as $row) {
            $events[] = new Event($row['start'], $row['whole_day'], $row['title'], $row['idcalendar'], $row["finish"], $row['description'], $row['idevent']);
        }
        return $events;
    }

    public static function add_event($event) {
        self::execute('INSERT INTO Event (start, finish, whole_day,title,description,idcalendar) VALUES (:start, :finish, :whole_day,:title,:description,:idcalendar)', array(
            'start' => $event->start,
            'finish' => $event->finish,
            'whole_day' => $event->whole_day,
            'title' => $event->title,
            'description' => $event->description,
            'idcalendar' => $event->idcalendar
        ));
        $event->idevent = self::lastInsertId();
        return true;
    }

    public function delete($idevent) {

        self::execute("DELETE from Event where idevent=:idevent", array('idevent' => $idevent));
        return true;
    }

    public function updateEvent() {
        self::execute("UPDATE event SET start=?, finish=?, whole_day=?, title=?, description=?, idcalendar=? WHERE idevent = ?", array($this->start, $this->finish, $this->whole_day, $this->title, $this->description, $this->idcalendar, $this->idevent));
        return true;
    }

    public static function get_event_by_title($idcalendar, $title) {
        $query = self::execute("SELECT idevent, start, finish, whole_day, title, event.description, event.idcalendar
                                FROM event, calendar WHERE title = :title AND event.idcalendar = calendar.idcalendar 
                                AND event.idcalendar = :idcalendar", array('idcalendar' => $idcalendar,
                    'title' => $title));
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return NULL;
        } else {
            return new Event($data['start'], $data['whole_day'], $data['title'], $data['idcalendar'], $data["finish"], $data['description'], $data['idevent']);
        }
    }


}
