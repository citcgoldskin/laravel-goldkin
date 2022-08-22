<?php

namespace App\Service;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use Auth;
use DB;

class SettingService
{
    public static function getSetting($key, $type) {
        $value = Cache::get($key);
        if ($value)
            return  $value;

        $result = Setting::where('name', $key)
            ->first();

        if (is_null($result)) {
            return '';
        }

        $res = $result['value'];
        if ($type == 'int') {
            $res =  (int)$res;
        } else if($type == 'float') {
            $res =  sprintf("%.1f", $res);
        }
        Cache::put($key, $res);
        return $res;
    }

    public static function setSetting($key, $value, $type='string') {
        if ($type == 'int') {
            $value =  (int)$value;
        } else if($type == 'float') {
            $value =  sprintf("%.1f", $value);
        }
       $result = Setting::where('name', $key)
            ->update(['value'=>$value]);
       if($result) {
           Cache::set($key, $value);
       }

        return $value;
    }
}
