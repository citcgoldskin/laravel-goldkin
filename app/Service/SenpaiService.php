<?php

namespace App\Service;

use App\Models\Senpai;
use App\Models\User;
use Auth;
use DB;

class SenpaiService
{
    public static function doCreateSenpai($data){
        $user_obj = User::where('id', Auth::user()->id)->first();
        if (is_object($user_obj)) {
            if(isset( $data['user_firstname']) && !is_null( $data['user_firstname'])) $user_obj->user_firstname = $data['user_firstname'];
            if(isset( $data['user_lastname']) && !is_null( $data['user_lastname'])) $user_obj->user_lastname = $data['user_lastname'];
            if(isset( $data['user_sei']) && !is_null( $data['user_sei'])) $user_obj->user_sei = $data['user_sei'];
            if(isset( $data['user_mei']) && !is_null( $data['user_mei'])) $user_obj->user_mei = $data['user_mei'];
            if(isset( $data['birthday_year']) && isset( $data['birthday_month']) && isset( $data['birthday_day'])) $user_obj->user_birthday = $data['birthday_year'].'-'. $data['birthday_month'].'-'. $data['birthday_day'];
            if(isset( $data['senpai_mail']) && !is_null( $data['senpai_mail'])) $user_obj->user_mail = $data['senpai_mail'];
            if(isset( $data['senpai_area_id']) && !is_null( $data['senpai_area_id'])) $user_obj->user_area_id = $data['senpai_area_id'];
            if(isset( $data['senpai_county']) && !is_null( $data['senpai_county'])) $user_obj->user_county = $data['senpai_county'];
            if(isset( $data['senpai_village']) && !is_null( $data['senpai_village'])) $user_obj->user_village = $data['senpai_village'];
            if(isset( $data['senpai_mansyonn']) && !is_null( $data['senpai_mansyonn'])) $user_obj->user_mansyonn = $data['senpai_mansyonn'];
            $user_obj->user_is_senpai = 1;
            return $user_obj->save();
        }else{
            return false;
        }
    }

//    public static function getUserId($senpai_id)
//    {
//        $senpai_info = Senpai::where('senpai_id', $senpai_id)->select('senpai_user_id')->first();
//        if(is_null($senpai_info)){
//            return 0;
//        }else{
//            return $senpai_info['senpai_user_id'];
//        }
//    }

//    public static function getSenapiId($user_id)
//    {
//        $senpai_info = Senpai::wherehas('user', function ($query) use ($user_id){
//            $query->where('id', $user_id);
//        })->first();
//        if(!is_null($senpai_info)){
//            return $senpai_info['senpai_id'];
//        }else{
//            return 0;
//        }
//    }

    public static function getSenpaiInfo($senpai_id)
    {
        $user_info = User::where('user_is_senpai', 1)
            ->where('id', $senpai_id)
            ->with('userConfirm')
            ->first();
        return $user_info;

    }


}
