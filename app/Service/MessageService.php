<?php

namespace App\Service;

use App\Models\MsgClass;
use App\Models\MsgSetting;
use App\Models\MsgTpl;
use App\Models\Msg;
use App\Service\LessonService;
use App\Service\InformService;
use Auth;
use DB;

class MessageService
{
    public const MSG_STATE_CLOSE = 0;
    public const MSG_STATE_OPEN = 1;

    public const MSG_CLASS_SALE = 1;
    public const MSG_CLASS_MESSAGE = 2;
    public const MSG_CLASS_FUNCTION = 3;
    public const MSG_CLASS_SERVICE = 4;
    public const MSG_CLASS_NEWS = 5;
    public const MSG_CLASS_NOTICE = 6;
    public const MSG_CLASS_OWN_NEWS = 7;

    //msg_class
    public static function getMsgClassAndSettingList($user_id) {
        return MsgClass::orderBy('mc_sort', 'asc')
            ->with('msg_setting', function ($query) use($user_id) {
                $query
                    ->where('ms_user_id', $user_id);
            })->get();
    }
    public static function getMsgClassId($mc_name) {
        $msg_class =  MsgClass::where('mc_name', $mc_name)
            ->first();
        if(count($msg_class) > 0){
            return $msg_class['mc_id'];
        }else{
            return 0;
        }
    }

    //msg_setting
    public static function insertMsgSetting($data) {
        $msg_setting_info =  MsgSetting::where('ms_user_id', $data['ms_user_id'])
            ->where('ms_mc_id', $data['ms_mc_id'])
            ->get();

        if(count($msg_setting_info) > 0){
            foreach ($msg_setting_info as $key => $value){
                //$data['ms_id'] = $value['ms_id'];
                MsgSetting::where('ms_id', $value['ms_id'])
                    ->update($data);
            }
        }else{
            MsgSetting::create($data);
        }
    }
    //msg_tpl
    public static function getMsgTpl($mc_code) {
        return  MsgTpl::where('mt_code', $mc_code)
            ->first();
    }

    //msg
    public static function doCreateMsg($msg_class_id, $to_user_id, $msg_note) {
        if(!InformService::isInformUser($to_user_id)){
            return null;
        }
        $user_id = Auth::user()->id;
        $data['msg_mc_id'] = $msg_class_id;
        $data['msg_from_user_id'] = $user_id;
        $data['msg_to_user_id'] = $to_user_id;
        if($msg_class_id == self::MSG_CLASS_SALE){
            $data['msg_mt_code'] = $msg_note;
        }else{
            $data['msg_content'] = $msg_note;
        }

        if($obj_msg = Msg::create($data))
        {
            return $obj_msg;
        } else {
            return null;
        }
    }

    public static function doCreateMsgFromAdmin($msg_class_id, $admin_id, $to_user_id, $msg_content)
    {
        $data['msg_mc_id'] = $msg_class_id;
        $data['msg_from_user_id'] = $admin_id;
        $data['msg_to_user_id'] = $to_user_id;
        $data['msg_content'] = $msg_content;
        if($obj_msg = Msg::create($data))
        {
            return $obj_msg;
        } else {
            return null;
        }
    }

    public static function getMsg($user_id, $msg_class_id) {
        return Msg::orderByDesc('created_at')
            ->where('msg_to_user_id', $user_id)
            ->where('msg_mc_id', $msg_class_id)
            ->with('user')
            ->with('msg_tpl');
    }

    public static function updateMsgOpen($msg_id) {
        return Msg::where('msg_id', $msg_id)
            ->update(['msg_open' => self::MSG_STATE_OPEN]);
    }

    public static function getNewMsgCnt($user_id, $msg_class_id) {
        return Msg::where('msg_to_user_id', $user_id)
            ->where('msg_mc_id', $msg_class_id)
            ->where('msg_open', self::MSG_STATE_CLOSE)
            ->count();
    }

}
