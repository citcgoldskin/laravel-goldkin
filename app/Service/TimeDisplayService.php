<?php
namespace App\Service;
use Carbon\Carbon;
class TimeDisplayService{

    public static function TalkroomTimeDisplay($date)
    {
        $curtime = strtotime($date);
        $today = strtotime(Carbon::today());
        $yestoday = strtotime(Carbon::yesterday());
        $cur_year = strtotime(new Carbon(date('Y-01-01', $curtime)));

        if ( $today < $curtime ) {
            return date('H:i', $curtime);
        }else if ( $yestoday < $curtime ) {
            return '昨日';
        }else if ($cur_year < $curtime ) {
            return date('n/j', $curtime);
        } else {
            return date('Y/n/j', $curtime);
        }
        return null;
    }

    public static function timeDiffFromDate($date)
    {
        $date_future =  $date . " 23:59:59";
        $date_now = date("Y-m-d H:i:s");
        $hour_diff= (int) ((strtotime($date_future)-strtotime($date_now)) / 3600);
        $day_future = abs((int) ($hour_diff / 24));
        $hour_future = abs($hour_diff % 24);

        $period_result = "";
        if($day_future != 0)
        {
            $period_result = $day_future . "日";
        }
        if($hour_future != 0)
            $period_result .= $hour_future . "時間";
        if($period_result == "")
            $period_result = "1時間";
        return $period_result;
    }


    public static function timeDiffFromDatetime($datetime)
    {
        $date_future =  $datetime;
        $date_now = date("Y-m-d H:i:s");
        $hour_diff= (int) ((strtotime($date_future)-strtotime($date_now)) / 3600);
        $day_future = abs((int) ($hour_diff / 24));
        $hour_future = abs($hour_diff % 24);

        $period_result = "";
        if($day_future != 0)
        {
            $period_result = $day_future . "日";
        }
        if($hour_future != 0)
            $period_result .= $hour_future . "時間";
        if($period_result == "")
            $period_result = "1時間";
        return $period_result;
    }

    //from : 2022-02-23 09:23:00
    //to   : 2022/02/23
    public static function getDateFromDatetime($datetime)
    {
        return date("Y/m/d", strtotime($datetime));
    }

}
