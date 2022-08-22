<?php

namespace App\Service;

use App\Http\Requests\User\CancelReqRequest;
use App\Models\LessonSchedule;
use App\Models\CancelReasonType;
use Carbon\Carbon;
use Auth;
use DB;
use function PHPUnit\Framework\isNull;

class CommonService
{
    public static $week_arr = array(0 => "日", 1 => "月", 2 => "火", 3 => "水", 4 => "木", 5 => "金", 6 => "土");

    public static function getAge($date)
    {
        $timestamp = strtotime($date);
        $year = date('Y', time()) - date('Y', $timestamp);
        $age = $year > 0 ? $year : 0;
        $age = (int)($age / 10);
        if ($age == 0) {
            $age = 10;
        } else if($age > 7) {
            $age = 70;
        } else {
            $age = $age * 10;
        }
        return $age.'代';
    }

    public static function getUserAvatarUrl($avatar_name) {
        $imgPath = storage_path('app/public/avatar/').$avatar_name;
        if ( is_null($avatar_name) || !file_exists($imgPath) || filetype($imgPath) != 'file' ) {
            return asset('assets/user/img/icon_02.svg');
        } else {
            return asset('storage/avatar/'.$avatar_name);
        }
    }

    public static function getLessonImgUrl($ls_img) {
        $imgPath = storage_path('app/public/lesson/').$ls_img;
        if ( is_null($ls_img) || !file_exists($imgPath) || filetype($imgPath) != 'file') {
            return asset('storage/lesson/default.png');
        } else {
            return asset('storage/lesson/'.$ls_img);
        }
    }

    public static function getLessonClassImgUrl($lesson_class_img){
        $imgPath = storage_path('app/public/class_image/').$lesson_class_img;
        if ( is_null($lesson_class_img) || !file_exists($imgPath) || filetype($imgPath) != 'file') {
            return asset('assets/user/img/icon_lesson_class_default.png');
        } else {
            return asset('storage/class_image/'.$lesson_class_img);
        }
    }

    public static function getLessonClassIconUrl($lesson_class_icon){
        $imgPath = storage_path('app/public/class_icon/').$lesson_class_icon;

        if ( is_null($lesson_class_icon) || !file_exists($imgPath) || filetype($imgPath) != 'file') {
            return asset('assets/user/img/icon_lesson_class_default.png');
        } else {
            return asset('storage/class_icon/'.$lesson_class_icon);
        }
    }

    public static function getCardCompanyIconUrl($company_icon){
        $imgPath = storage_path('app/public/company_icon/').$company_icon;

        if ( is_null($company_icon) || !file_exists($imgPath) || filetype($imgPath) != 'file') {
            return asset('assets/user/img/icon_creca.png');
        } else {
            return asset('storage/company_icon/'.$company_icon);
        }
    }

    public static function getLessonIconImgUrl($icon_class_img){
        $imgPath = storage_path('app/public/class_icon/').$icon_class_img;

        if ( is_null($icon_class_img) || !file_exists($imgPath)) {
            return asset('assets/user/img/icon_lesson_class_default.png');
        } else {
            return asset('storage/class_icon/'.$icon_class_img);
        }
    }

    // get formatted date and time
    public static function getMD($date) {
        return date('n月j日', strtotime($date));
    }

    public static function getMDH($date) {
        return date('n月j日H:i', strtotime($date));
    }

    public static function getMDAndWeek($date) {
        return date('n月j日', strtotime($date)).' ('. CommonService::$week_arr[date('w', strtotime($date))].')';
    }

    public static function getHM($date) {
        return date('H:i', strtotime($date));
    }

    public static function getHMString($time) {
        return date('H時i分', strtotime($time));
    }

    public static function getYMD($date) {
        return date('Y年n月j日', strtotime($date));
    }

    public static function getYMDAndHM($date) {
        return date('Y年n月j日 H:i', strtotime($date));
    }

    public static function getYMDAndWeek($date) {
        return date('Y年n月j日', strtotime($date)).' ('. CommonService::$week_arr[date('w', strtotime($date))].')';
    }

    public static function getStartAndEndTime($stime, $etime, $sep = '~') {
        return date('H:i', strtotime($stime)) . $sep . date('H:i', strtotime($etime));
    }

    public static function getIntervalMinute($stime, $etime) {
        return round((strtotime($etime) - strtotime($stime)) / 60);
    }

    public static function getWeekday($date)
    {
        $week= date('w',  strtotime($date));
        return self::$week_arr[$week];
    }

    public static function getAddDate($date, $diff=1) {
        return Carbon::make($date)->addDays($diff);
    }

    public static function getSexStr($val)
    {
        if ($val == "0") {
            return "";
        } else if ($val == 1) {
            return "女性";
        } else {
            return "男性";
        }
    }

    public static function getManWomanStr($man, $woman) {
        if ($man > 0 && $woman > 0)
            return '男性'.$man.'人／女性'.$woman.'人';

        if ( $man > 0 )
            return '男性'.$man.'人';

        if ( $woman > 0 )
            return '女性'.$woman.'人';
        return '0人';
    }

    public static function getFeeTypeStr($type) {
        if ( $type == 0 )
            return 'A';

        if ( $type == 1 )
            return 'B';

        return 'C';
    }

    public static function getLessonMode($mode) {
        return $mode  == 0 ? '対面' : 'オンライン';
    }

    public static function getAccountType($type) {
        if($type == 0){
            return '普通預金';
        }elseif($type == 1){
            return '当座預金';
        }else if($type == 2){
            return '貯蓄預金';
        }else{
            return '';
        }
    }

    public static function getAccountTypeId($type_name) {
        if($type_name == '普通預金'){
            return 0;
        }elseif($type_name == '当座預金'){
            return 1;
        }else if($type_name == '貯蓄預金'){
            return 2;
        }else{
            return -1;
        }
    }

    public static function getLessonAmount($amount, $from_time, $to_time) {

        $result = round($amount * (strtotime($to_time) - strtotime($from_time)) / (30 * 60));
        if($result > 0)
            return $result;
        else
            return 0;
    }

    public static function getCancelPercent($before_day) {
        return SettingService::getSetting('cancel_before_'.$before_day.'_percent', 'int');
    }

    public static function getServiceFee($amount) {
        return round($amount * self::getServicePercent() / 100);
    }

    public static function getServicePercent() {
        return SettingService::getSetting('service_fee_percent', 'int');
    }

    public static function getTotalPrice($amount, $traffic, $coupon) {
        return ($amount + self::getServiceFee($amount)) + $traffic + $coupon;
    }

    public static function getHopePrice($hope_min, $hope_max, $amount_per_30min, $man_count) {
        return self::showFormatNum($hope_min * $amount_per_30min * $man_count / 30) . '~' . self::showFormatNum($hope_max * $amount_per_30min * $man_count / 30);
    }

    public static function getunixtime($time)
    {
        $array = explode("-", $time);
        $unix_time = mktime(0, 0, 0, $array[1], $array[2], $array[0]);
        return $unix_time;
    }

    public static function timeIdToStr($time_id)
    {
        $hour = floor($time_id / 4);
        $minute = ($time_id - 4 * $hour) * 15;
        return str_pad($hour, 2, "0", STR_PAD_LEFT).':'.str_pad($minute, 2, "0", STR_PAD_LEFT);
    }

    public static function convertDatetimeToId($date) {
        $h = date('H', strtotime($date));
        $m = date('i', strtotime($date));
        return $h * 4  + (int)( $m /15 );
    }


    public static function getScheduleForDB($html_data)
    {
        $data = explode("年", $html_data);
        if(!is_array($data)){
            return false;
        }

        $year = $data[0];
        $data_tmp = substr($html_data, strlen($data[0] . '年'));

        $data = explode("月", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $month = $data[0];
        $data_tmp = substr($data_tmp, strlen($data[0] . '月'));

        $data = explode("日", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $day = $data[0];
        $data_tmp = substr($data_tmp, strlen($data[0] . '日'));

        $result['date'] = $year.'-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-'.str_pad($day, 2, "0", STR_PAD_LEFT);

        $data_tmp = substr($data_tmp, strlen($data[0]));
        $data = explode(")  ", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $data_tmp = substr($data_tmp, strlen($data[0] . ')'));
        $data = explode("~", $data_tmp);
        if(!is_array($data)){
            return false;
        }
        $result['start_time'] = $data[0].':00';
        $result['end_time'] = $data[1].':00';

        return $result;
    }

    public static function getReserveTimeForHtml($html_data)
    {
        $data = explode("年", $html_data);
        if(!is_array($data)){
            return false;
        }

        $result['year'] = $data[0];
        $data_tmp = substr($html_data, strlen($data[0] . '年'));

        $data = explode("月", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $result['month'] = $data[0];
        $data_tmp = substr($data_tmp, strlen($data[0] . '月'));

        $data = explode("日", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $result['day'] = $data[0];
        $data_tmp = substr($data_tmp, strlen($data[0] . '日'));

        $data = explode(")  ", $data_tmp);
        if(!is_array($data)){
            return false;
        }

        $day_of_week = explode("(", $data[0]); // $day_of_week
        $result['date'] = $day_of_week[1];

        $data_tmp = substr($data_tmp, strlen($data[0] . ')'));
        $data = explode("~", $data_tmp);
        if(!is_array($data)){
            return false;
        }
        $result['start_time'] = $data[0].':00';
        $result['end_time'] = $data[1].':00';

        return $result;
    }

    public static function getScheduleForCalender(/*$senpai_id, $date, $state = 0*/)
    {
        $schadule_obj = LessonSchedule::with('lesson_request_schedule');
        return $schadule_obj;

    }

    public static function _getScheduleForCalender($schedule, $cur_year, $cur_month, $day_count)
    {
        $time_count = 24 * 4;
        $interval = 60 * 15;
        for($i = 0; $i < $time_count; $i++){
            $result[$i]['title'] = self::timeIdToStr($i);
            for($j = 0; $j < $day_count; $j++){
                $result[$i]['value'][$j] = 0;
            }
        }
        if(isset($schedule) && !empty($schedule)){
            foreach ($schedule as $key => $value){
                $timmstamp = strtotime($value['ls_date']);
                $year= date('Y', $timmstamp);
                $month = date('n', $timmstamp);
                $day = date('j', $timmstamp);
                if($year == $cur_year && $month == $cur_month){
                    $from_time_id = (strtotime($value['ls_start_time']) - strtotime('00:00:00')) / $interval;
                    $to_time_id = (strtotime($value['ls_end_time']) - strtotime('00:00:00')) / $interval;
                    for($i = $from_time_id; $i < $to_time_id; $i++){
                        $result[$i]['value'][$day - 1] = 1;
                    }
                }
            }
        }
        return $result;
    }

    public static function showFormatNum($num, $sep = ',') {
        return number_format($num, 0, '.', $sep);
    }


    public static function getTimeUnit($sTime)
    {
        /*if($sTime == $eTime)
        {
            $lesson_time = $eTime;
        } else
        {
            $lesson_time = $sTime . "~" . $eTime;
        }
        return $lesson_time . "分";*/
        return $sTime. "分";
    }

    public static function getDateRemain($date)
    {
        //$date_future =  $date . " 23:59:59";
        $date_future =  Carbon::parse($date)->format('Y-m-d H:i:s');
        $date_now = date("Y-m-d H:i:s");

        if ((strtotime($date_future)-strtotime($date_now)) < 0) {
            return "";
        }

        $hour_diff= abs((int) ((strtotime($date_future)-strtotime($date_now)) / 3600));
        $day_future = (int) ($hour_diff / 24);
        $hour_future = $hour_diff % 24;

        $period_result = "";
        if($day_future != 0)
        {
            $period_result = $day_future . "日間";
        } else
        {
            $period_result .= $hour_future . "時間";
        }
        return $period_result;
    }

    public static function getCancelFee($schedule_date, $amount, $service_fee = 0, $trans_fee = 0) {
        $cancel_before_1_percent = SettingService::getSetting('cancel_before_1_percent', 'int');
        $cancel_before_0_percent = SettingService::getSetting('cancel_before_0_percent', 'int');
        $diff = Carbon::now()->diff($schedule_date, false);
        $diff_days = $diff->d;
        $invert = $diff->invert;
        if ( $diff_days > 1 && $invert) {
            return 0;
        } elseif ( $diff_days == 1 && $invert) {
            return ($amount + $service_fee) * ($cancel_before_1_percent) / 100;
        } elseif ( $diff_days == 0 || !$invert ) {
            return ($amount + $service_fee + $trans_fee) * ($cancel_before_0_percent) / 100;
        }
    }

    public static function getCancelReasonTypes($type = 0) {
        return CancelReasonType::orderBy('crt_id')->where('crt_kind', $type)->get();
    }

    public static function getCancelReasons($ids) {
        return CancelReasonType::whereIn('crt_id', $ids)->select('crt_content');
    }

    public static function createCouponCode()
    {
        return md5(date('y-m-d h-n-i'));
    }

    public static function createInviteCode()
    {
        $result = preg_replace('/[a-z]/', '', md5(time()));
        while(1)
        {
            if(strlen($result) > 24)        //26 ^ 8 => 12digits
                break;
            $result = preg_replace('/[a-z]/', '', md5($result)) . $result;
        }
        $result = substr($result, 0, 24);


        $result_code = "";
        for($i = 0 ; $i < 8;$i++)
        {
            $result_code .= chr(ord('A') + ((substr($result, $i * 3, 3)) % 26));
            if($i == 3)
            {
                $result_code .= "-";
            }
        }
        return $result_code;
    }

    public static function unserializeData($val) {

        $result = array();
        if((is_string($val) && preg_match("#^((N;)|((a|O|s):[0-9]+:.*[;}])|((b|i|d):[0-9.E-]+;))$#um", $val)))
            $result = unserialize($val);

        return $result;
    }

    public static function getLessonMoneyRange($min, $max, $origin=false) {
        $min_money = 0;
        if ($origin) {
            if ($min) {
                $min_money = $min;
            }
            $max_money = $max;
        } else {
            if ($min) {
                $min_money = self::showFormatNum($min);
            }
            $max_money = self::showFormatNum($max);
        }
        if ($max_money) {
            $max_money .= '円';
        } else {
            $max_money = "";
        }
        return $min_money == 0 ? '～'.$max_money : $min_money.'円～'.$max_money;
    }

}
