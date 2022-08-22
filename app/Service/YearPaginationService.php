<?php

namespace App\Service;

use App\Models\UserLoginHistory;
use Session;

class YearPaginationService {

    protected static $current_date;

    protected static function setSelectedDate($date) {
        if ($date) {
            self::$current_date = date('Y-m', strtotime($date));
        } else {
            self::$current_date = date('Y-m');
        }
    }

    protected static function getPreviousMonth($date) {
        if ($date) {
            return date('Y-m', strtotime('-1 month', strtotime($date)));
        } else {
            return date('Y-m', strtotime('-1 month'));
        }
    }

    protected static function getPreviousStatus($current) {
       return $current > date('Y-m', time()) ? true : false;
    }

    protected static function getNextMonth($date) {
        if ($date) {
            return date('Y-m', strtotime('+1 month', strtotime($date)));
        } else {
            return date('Y-m', strtotime('+1 month'));
        }
    }

    protected static function getNextStatus($next) {

        return $next >= date('Y-m', strtotime('+1 month')) ? false : true;
    }

    public static function getYearPagination($date=null)
    {
        $result = [];
        self::setSelectedDate($date);
        $result['current'] = self::$current_date;
        $result['current_label'] = date('Y年n月', strtotime(self::$current_date));
        $result['cur_month'] = date('n', strtotime(self::$current_date));
        $result['cur_year'] = date('Y', strtotime(self::$current_date));


        $result['previous'] = self::getPreviousMonth($date);
        $result['previous_label'] = date('Y年m月', strtotime($result['previous']));
        $result['enable_previous'] = self::getPreviousStatus($result['current']);


        $result['next'] = self::getNextMonth($date);
        $result['next_label'] = date('Y年m月', strtotime($result['next']));
        $result['enable_next'] =  self::getNextStatus($result['next']);

        $days = date('t', strtotime(self::$current_date));
        $ym = date('Y-m', strtotime(self::$current_date));
        $week = 0;


        for ($i = 1; $i <= $days; $i++) {
            $weekday = date('w',  strtotime($ym.'-'.$i));
            $result['calendar'][$week][$weekday] = $i;
            $result['month'][$i] = $weekday;
            if($i == 1)
                $result['first_date'] = $weekday;
            if ($weekday == 6)
                $week++;

        }
        return $result;
    }

}
