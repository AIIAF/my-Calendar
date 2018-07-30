<?php

require_once 'framework/Model.php';

class Planning extends Model {
    
    private $week_number=NULL;
    
    public function __construct($week_number) {
        $this->week_number = $week_number;
    }

    //
    public static function increment_week($week) {
        $increment_week = $week + 1;
        $planning = new Planning($increment_week);
        return $planning->week_number;
    }

    public static function decrement_week($week) {
        $decrement_week = $week - 1;
        $planning = new Planning($decrement_week);
        return $planning->week_number;
    }

    public static function current_week() {
        date_default_timezone_set('Europe/Brussels');
        $date_time = new DateTime('00:00:00');
        $current_week = (int) date("W", $date_time->getTimestamp());
        $planning = new Planning($current_week);
        return $planning->week_number;
    }

    public static function daysInWeek($week_number) {
        $result = array();
        date_default_timezone_set('Europe/Brussels');
        $date_time = new DateTime('00:00:00');
        $date_time->setISODate((int) $date_time->format('o'), $week_number, 1);
        $interval = new DateInterval('P1D');
        $week = new DatePeriod($date_time, $interval, 6);
        foreach ($week as $day) {
//            $result[] = $day->format('D d/m/Y');
            $result[] = $day->format('Y-m-d');
        }
        return $result;
    }

    
    public static function datetime_string_fullcalendar($date) {// 1900-01-30\T00:00:00
        date_default_timezone_set('Europe/Brussels');
        return $date = date('Y-m-d\TH:i:s', strtotime("$date"));
    }
    public static function date_string_day_fullcalendar($date) {//  mon 30-01-1900 
        date_default_timezone_set('Europe/Brussels');
        return $date = date('Y-m-d', strtotime("$date"));
    }
    public static function date_string($date) {// 1900-01-30
        return $date = date('Y-m-d ', strtotime("$date"));
    }
    
    

}
