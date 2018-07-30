<?php

require_once 'framework/Model.php';
require_once 'User.php';

class Share extends Model {

    public $iduser;
    public $idcalendar;
    public $read_only;

    public function __construct($iduser, $idcalendar, $read_only) {
        $this->iduser = $iduser;
        $this->idcalendar = $idcalendar;
        $this->read_only = $read_only;
    }

    public static function addShare($share) {
        $query = self::execute('INSERT INTO Share (iduser,idcalendar,read_only) VALUES (:iduser,:idcalendar,:read_only)', array(
                    'iduser' => $share->iduser,
                    'idcalendar' => $share->idcalendar,
                    'read_only' => $share->read_only
        ));
        return true;
    }

    public function delete($idcalendar) {
        self::execute("DELETE from share where idcalendar=:idcalendar", array('idcalendar' => $idcalendar));
        return true;
    }

    public function delete1($idcalendar, $iduser) {
        self::execute("DELETE from share where idcalendar=:idcalendar and iduser=:iduser", array('idcalendar' => $idcalendar, 'iduser' => $iduser));
        return true;
    }

    public static function get_shares($idcalendar) {
        $query = self::execute("SELECT * from Share WHERE idcalendar = ?", array($idcalendar));
        $data = $query->fetchAll();
        $shares = [];
        foreach ($data as $row) {
            $shares[] = new Share($row['iduser'], $row['idcalendar'], $row["read_only"]);
        }
        return $shares;
    }

    public static function get_shares_iduser($iduser) {
        $query = self::execute("SELECT * from Share WHERE iduser = ?", array($iduser));
        $data = $query->fetchAll();
        $shares = [];
        foreach ($data as $row) {
            $shares[] = new Share($row['iduser'], $row['idcalendar'], $row["read_only"]);
        }
        return $shares;
    }

    public static function get_share($idcalendar, $iduser) {
        $query = self::execute("SELECT * from Share WHERE idcalendar = ? and iduser =?", array($idcalendar, $iduser));
        $row = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {


            return new Share($row['iduser'], $row['idcalendar'], $row["read_only"]);
        }
    }

    public static function get_share_t($idcalendar, $iduser, $read_only) {
        $query = self::execute("SELECT * from Share WHERE idcalendar = ? and iduser =? and read_only=?", array($idcalendar, $iduser, $read_only));
        $row = $query->fetch();
        return new Share($row['iduser'], $row['idcalendar'], $row["read_only"]);
    }

    public static function get_share_iduser($idcalendar) {
        $query = self::execute("SELECT * from Share WHERE iduser =?", array($idcalendar));
        $row = $query->fetch();
        return new Share($row['iduser'], $row['idcalendar'], $row["read_only"]);
    }

    public function updateShare() {
        $query = self::execute("UPDATE share SET read_only=? WHERE idcalendar = ?", array($this->read_only, $this->idcalendar));
        return true;
    }

}
