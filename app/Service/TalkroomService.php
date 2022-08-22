<?php

namespace App\Service;

use App\Models\LessonRequestSchedule;
use App\Models\Talkroom;
use App\Models\TalkroomMessage;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;

class TalkroomService
{
    //senpai's menu
    public const Kouhai_Rquest_ConfirmOrChange = 1;
    public const Kouhai_Request_Cancel = 2; // 16
    public const Kouhai_Request_Buy = 3;
    public const Kouhai_Sys_Msg = 4;
    public const Kouhai_Sys_Confirm = 5;
    public const Kouhai_Sys_CancelMoney = 6; // 18
    public const Kouhai_Sys_PosShare = 7;
    public const Kouhai_Btn_LessonBuy = 8;
    public const Kouhai_Btn_Start = 9;
    public const Kouhai_Btn_PosCancel = 10;
    public const Kouhai_Btn_Evalution = 11;
    public const Kouhai_LeftMsg = 12;
    public const Kouhai_RightMsg = 13;
    public const Kouhai_Map = 14;
    public const Kouhai_Request_Change_Schedule = 15; // 2
    public const Kouhai_Request_Senpai_Cancel = 16; // 4
    public const Kouhai_Rquest_Change_req = 17; // 15
    public const Kouhai_Request_Confirm_Change = 18; // 5
    public const Kouhai_Share_Location = 19; // 17

    //kouhai's menu
    public const Senpai_Request_Response = 1;
    public const Senpai_Request_ChangeResponse = 2; //15
    public const Senpai_Request_ConfirmOrChange = 3;
    public const Senpai_Request_Cancel = 4; // 16
    public const Senpai_Request_Confirm_Change = 5; //18
    public const Senpai_Sys_Msg = 6;
    public const Senpai_Sys_RequestConfirm =7;
    public const Senpai_Sys_PosShare = 8;
    public const Senpai_Btn_LessonBuy = 9;
    public const Senpai_Btn_PosCancel = 10;
    public const Senpai_Btn_Evalution = 11;
    public const Senpai_LeftMsg = 12;
    public const Senpai_RightMsg = 13;
    public const Senpai_Map = 14;
    public const Senpai_Request_ChangeReqResponse = 15; // 17
    public const Senpai_Request_Kouhai_Cancel = 16; // 2
    public const Senpai_Share_Location = 17; // 19
    public const Senpai_Sys_CancelMoney = 18; // 6

    public static function doCreateRoom($user_id, $talk_user_id, $menu_type){

        $data['user_id']      = $user_id;
        $data['menu_type']    = $menu_type;
        $data['talk_user_id'] = $talk_user_id;
        $data['state']        = config('const.talkroom_state.talking');
        return Talkroom::create($data);
    }

    public static function getTalkroom($user_id, $talk_user_id, $menu_type) {
        $talkroom = Talkroom::where('user_id', $user_id)
            ->where('talk_user_id', $talk_user_id)
            ->where('menu_type', $menu_type)
            ->first();

        if ( is_null($talkroom) )
            $talkroom = self::doCreateRoom($user_id, $talk_user_id, $menu_type);

        return $talkroom;
    }

    public static function setTalkroomState($room_id, $state) {
        return Talkroom::where('id', $room_id)
            ->update(['state'=>$state]);
    }

    public static function getTalkLists($menu_type) {

        return Talkroom::with('talkUserInfo')
            ->with('unreadMessages')
            ->with('readMessages')
            ->where('menu_type', $menu_type)
            ->where('state', config('const.talkroom_state.talking'))
            ->where('user_id', Auth::id())
            ->get();
    }

    public static function getTalkUserInfo($room_id) {
        $talk_info = Talkroom::with('talkUserInfo')
            ->where('id', $room_id)
            ->first();

        if (!$talk_info)
            return null;

        return $talk_info['talkUserInfo'];
    }

    public static function getUnreadMsgCnt($user_id) {
        return TalkroomMessage::
            where('state', config('const.msg_state.unread'))
            ->wherehas('talkroom',
                function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
            ->count();
    }

    public static function getNewMessages($room_id) {
        $new_messages =  TalkroomMessage::where('talkroom_id', '=',  $room_id)
            ->where('state', config('const.msg_state.unread'))
            ->orderBy('id')
            ->get()
            ->toArray();

         TalkroomMessage::where('talkroom_id', $room_id)
             ->where('state', config('const.msg_state.unread'))
             ->update(['state'=>config('const.msg_state.read')]);

        return $new_messages;
    }

    public static function getPreviousMessages($room_id, $previous_id = 0, $take = 20) {
        if ($previous_id) {
            return $criteria = TalkroomMessage::where('talkroom_id', '=',  $room_id)
                ->where('state', config('const.msg_state.read'))
                ->where('id', '<', $previous_id)
                ->orderByDesc('id')
                ->limit($take)
                ->get()
                ->toArray();
        }

        $test = TalkroomMessage::where('talkroom_id', '=',  $room_id)
            ->where('state', config('const.msg_state.read'))
            ->orderByDesc('id')
            ->limit($take)
            ->get()
            ->toArray();
        return $criteria = TalkroomMessage::where('talkroom_id', '=',  $room_id)
            ->where('state', config('const.msg_state.read'))
            ->orderByDesc('id')
            ->limit($take)
            ->get()
            ->toArray();

    }

    public static function saveTalkroomData($user_id, $talk_user_id, $menu_type, $msg_type, $msg, $sch_id =0, $state = 0)
    {

        $room = self::getTalkroom($user_id, $talk_user_id, $menu_type);
        if (is_null($room))
            return false;

        self::setTalkroomState($room['id'],config('const.talkroom_state.talking'));

        $model = new TalkroomMessage();
        $model->talkroom_id         = $room['id'];
        $model->schedule_id         = $sch_id;
        $model->msg_type            = $msg_type;
        $model->message             = $msg;
        $model->state               = $state;
        if (!$model->save()) {
            return false;
        }
        return true;
    }

    public static function saveMessage($arrParam) {

        //get my room
        $myroom_id = $arrParam['id'];
        $myroom = Talkroom::findById($myroom_id);

        if (is_null($myroom))
            return false;

        //insert talk user messeage
        $msg_type = self::Kouhai_LeftMsg;
        $to_menu_type = config('const.menu_type.kouhai');
        if ($arrParam['menu_type'] == config('const.menu_type.kouhai')) {
            $msg_type = self::Kouhai_LeftMsg;
            $to_menu_type = config('const.menu_type.senpai');
        } else if ($arrParam['menu_type'] == config('const.menu_type.senpai')) {
            $msg_type = self::Senpai_LeftMsg;
            $to_menu_type = config('const.menu_type.kouhai');
        }

        if (!self::saveTalkroomData($myroom['talk_user_id'], $myroom['user_id'], $to_menu_type, $msg_type, $arrParam['message']))
            return false;

        $toroom = self::getTalkroom($myroom['talk_user_id'], $myroom['user_id'], $to_menu_type);
        self::setTalkroomState($toroom['id'],config('const.talkroom_state.talking'));

        //insert my message
        $msg_type = self::Kouhai_RightMsg;
        if ($arrParam['menu_type'] == config('const.menu_type.kouhai') )
            $msg_type = self::Kouhai_RightMsg;
        else if ($arrParam['menu_type'] == config('const.menu_type.senpai'))
            $msg_type = self::Senpai_RightMsg;

        $room = Talkroom::findById($myroom_id);
        if (!self::saveTalkroomData($myroom['user_id'], $myroom['talk_user_id'], $myroom['menu_type'], $msg_type, $arrParam['message'], 0, config('const.msg_state.read')))
            return false;

        self::setTalkroomState($myroom_id,config('const.talkroom_state.talking'));

        return true;
    }

    public static function saveSenpaiRequestResponse($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm, $req_id = 0)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエスト</h6>'.
                '<p class="talk_lesson_ttl ttl-block">'.
                '「'.$ls_title.'」'.//ランニングでダイエットしませんか？
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';

        foreach( $schedules as $k => $v ){
            $msg .= '<p class="detail_date">'.$v.'</p>'; //<p class="detail_date">1月20日（金）16:00〜17:00</p>
        }

        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'; //承認期限：1月14日（土）
        $msg .= '</div>'.
                 '<p class="talk_orange_btn arrow_mark">'.
                 '<a href="'.route('user.syutupinn.reserve_check', ['lr_id' => $req_id]).'">このリクエストに回答する</a>'. //C-22_23.php
                 '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_Response, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiRequestChangeReqResponse($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm, $req_id = 0)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエストが変更されました。</h6>'.
            '<p class="talk_lesson_ttl  ttl-block">'.
            '「'.$ls_title.'」'.//ランニングでダイエットしませんか？
            '</p>'.
            '<div class="detail_wrap">'.
            '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';

        foreach( $schedules as $k => $v ){
            $msg .= '<p class="detail_date">'.$v.'</p>'; //<p class="detail_date">1月20日（金）16:00〜17:00</p>
        }

        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'; //承認期限：1月14日（土）
        $msg .= '</div>'.
            '<p class="talk_orange_btn arrow_mark">'.
            '<a href="'.route('user.syutupinn.reserve_check', ['lr_id' => $req_id]).'">このリクエストに回答する</a>'. //C-22_23.php
            '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_ChangeReqResponse, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiRequestChangeResponse($senpai_id, $kouhai_id, $ls_title, $old_schedule, $new_schedule, $old_schedule_id, $new_schedule_id)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエストが変更されました。</h6>'.
                '<p class="talk_lesson_ttl  ttl-block">'.
                '「'.$ls_title.'」'.//「ランニングでダイエットしませんか？」
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">変更内容</p>'.
                '<p class="detail_date">'.$old_schedule.'</p>'.//<p class="detail_date">1月20日（金）16:00〜17:00</p>
                '<p class="centre pt10 pb10">↓</p>'.
                '<p class="detail_date">'.$new_schedule.'</p>'.//<p class="detail_date">1月30日（金）16:00〜17:00</p>
                '</div>'.
                '<p class="talk_orange_btn arrow_mark">'.
                '<a href="'.route('user.talkroom.requestResp', ['old_schedule_id' => $old_schedule_id, 'new_schedule_id' => $new_schedule_id]).'">変更リクエストに回答する</a>'.//C-22_23-after.php
                '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_ChangeResponse, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiRequestConfirmOrChange($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm, $req_id)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約を承認しました</h6>'.
                '<p class="talk_lesson_ttl  ttl-block">'.
                '「'.$ls_title.'」'.//ランニングでダイエットしませんか？」
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';

                foreach ($schedules as $k => $v) {
                    $msg .= '<p class="detail_date">'.$v.'</p>'; //1月20日（金）16:00〜17:00
                }

        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'. //1月15日（日）
                '</div>';
//                '<p class="talk_orange_btn arrow_mark">'.
//                '<a href="'.route('user.talkroom.requestConfirm', ['request_id'=> $req_id]).'">リクエスト内容の確認・変更</a>'. //D-29_30.php
//                '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_ConfirmOrChange, $msg))
            return false;
        return true;

    }

    public static function saveSenpaiRequestCancel($senpai_id, $kouhai_id, $reasons, $ls_title, $start_date, $ls_img )
    {
        $msg = '<h6 class="talk_kiji_ttl" >レッスンをキャンセルしました</h6 >'.
                '<div class="detail_wrap" >';
        if ( isset($reasons) && count($reasons) )
            $msg .= '<p class="detail_ttl  mark_left mark_square" >キャンセル理由</p >';
        foreach ( $reasons as $key => $reason )
            $msg .= '<p class="detail_date">'.$reason.'</p>';//<p class="detail_date" > 急用ができたため</p >

        $msg .= '<p class="detail_ttl  mark_left mark_square" >キャンセル料</p >'.
                '<p class="detail_date">なし（先輩都合のため）</p>'.
                '</div>'.
                '<div class="pickup_cancel_lesson">'.
                '<div>'.
                '<p class="ttl-block">'.$ls_title.'</p>'.
                '<p>'.$start_date.'</p>'. //2021年3月18日　16:00～
                '</div>'.
                '<div>'.
                '<img src = "'. CommonService::getLessonImgUrl($ls_img).'" alt = "" >'.
                '</div>'.
                '</div>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_Cancel, $msg))
            return false;
        return true;

    }

    public static function saveSenpaiRequestKouhaiCancel($senpai_id, $kouhai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img )
    {
        $msg = '<h6 class="talk_kiji_ttl" >レッスンをキャンセルしました</h6 >'.
            '<div class="detail_wrap" >';
        if ( isset($reasons) && count($reasons) )
            $msg .= '<p class="detail_ttl  mark_left mark_square" >キャンセル理由</p >';
        foreach ( $reasons as $key => $reason )
            $msg .= '<p class="detail_date">'.$reason.'</p>'; //<p class="detail_date" > 急用ができたため</p >

         $msg .= '<p class="detail_ttl  mark_left mark_square" >キャンセル料</p >';

        if ($cancel_amount == 0)
            $msg .='<p class="detail_date">なし</p>';
        else
            $msg .='<p class="detail_date">'.CommonService::showFormatNum($cancel_amount).'円</p>';

        $msg .= '</div>'.
            '<div class="pickup_cancel_lesson">'.
            '<div>'.
            '<p class="ttl-block">'.$ls_title.'</p>'.
            '<p>'.$start_date.'</p>'. //2021年3月18日　16:00～
            '</div>'.
            '<div>'.
            '<img src = "'. CommonService::getLessonImgUrl($ls_img).'" alt = "" >'.
            '</div>'.
            '</div>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_Kouhai_Cancel, $msg))
            return false;
        return true;

    }

    public static function saveSenpaiRequestConfirmChange($senpai_id, $kouhai_id, $ls_title, $sc_date)
    {
        $msg = '<h6 class="talk_kiji_ttl">変更を承認しました。</h6>'.
                '<p class="talk_lesson_ttl ttl-block">'.
                '「'.$ls_title.'」'.
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>'.
                '<p class="detail_date">'.$sc_date.'</p>'. //1月20日（金）16:00〜17:00
                '</div>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Request_Confirm_Change, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiSysMsg($senpai_id, $kouhai_id, $msg)
    {
        /*$msg = '<p>'.
                $senpai_name.'さん。<br>'.
                '本日のレッスンお疲れ様でした。<br>'.
                'もしよろしければ'.$senpai_name.'センパイの評価をお願いします。<br>'.
                '<br>'.
                '<small>※評価した内容は相手側には公開されません。</small>'.
                '</p>';*/

        $new_msg = '<p>'.$msg.'</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Sys_Msg, $new_msg))
            return false;
        return true;
    }

    public static function saveSenpaiSysRequestConfirm($senpai_id, $kouhai_id, $kouhai_name, $sc_id)
    {
        $msg = '<p>'.
                $kouhai_name.'コウハイとの予約が成立しました。<br>'.
                '当日のことで確認しておきたいことなどを直接質問することができます。'.
                '</p>'.
                '<p class="talk_orange_btn">'.
                '<a href="'.route('user.talkroom.subscriptionLesson', ['menu_type'=> config('const.menu_type.senpai'), 'schedule_id'=>$sc_id]).'">レッスン内容を確認する</a>'.//D-3.php
                '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Sys_RequestConfirm, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiSysPosShare($senpai_id, $kouhai_id, $minute, $lrs_id)
    {
        $msg = '<p>'.
                'レッスン開始時刻から'.$minute.'分経過しました。<br>'.
                'コウハイと合流できていない場合は、必ず自分の位置情報を共有し、到着報告を行ってください。'.
                '</p>'.
                '<p class="about_cancel">'.
                '※コウハイが現れなかった場合、待ち合わせ圏内で位置情報を共有していないとキャンセル料は受け取れません。'.
                '</p>'.
                '<p class="modal-link">'.
                '<a href="'.route('user.talkroom.cancelAbout').'">'.
                'キャンセル料が発生する場合について'.
                '</a>'.
                '</p>'.
                '<p class="talk_orange_btn">'.
                '<a href="'.route('user.talkroom.pos_info').'/'.$lrs_id.'">位置情報を共有する</a>'.//D-26.php
                '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Sys_PosShare, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiBtnCommon($senpai_id, $kouhai_id, $msg_type, $lrs_id=null)
    {
        //self::Senpai_Btn_LessonBuy
        //self::Senpai_Btn_PosCancel
        $msg = '';
        if ($msg_type == TalkroomService::Senpai_Btn_PosCancel) {
            $msg = '<a href="'.route('user.talkroom.pos_info', ['lrs_id'=>$lrs_id, 'available_cancel'=>1]).'">'.
                '<p>位置情報を共有しキャンセルする</p>'.
                '<p>※申請直後に共有オフになります</p>'.
                '</a>';
        }
        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), $msg_type, $msg, $lrs_id))
            return false;
        return true;
    }

    public static function saveSenpaiBtnEvalution($senpai_id, $kouhai_id, $req_id, $sc_id)
    {

        $room = self::getTalkroom($senpai_id, $kouhai_id, config('const.menu_type.senpai'));
        self::setTalkroomState($room['id'],config('const.talkroom_state.talking'));
        $msg = '<a href="'.
                route('user.talkroom.kouhaiEval',
                    [
                        'user_id'=>$senpai_id,
                        'req_id'=>$req_id,
                        'sch_id'=>$sc_id,
                        'room_id'=>$room['id']
                    ]).
                '"><p>評価を入力する</p></a>';//D-21_22b.php

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Btn_Evalution, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiMap($senpai_id, $kouhai_id, $map_data)
    {
        $msg = '<iframe src="'.
            $map_data. //http://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.1881663481045!2d139.70811601528163!3d35.64773538020213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b41b58cd6f5%3A0x90b5d7b83c971892!2z44CSMTUwLTAwMTMg5p2x5Lqs6YO95riL6LC35Yy65oG15q-U5a-_77yR5LiB55uu77yY4oiS77yTIOaBteavlOWvv-ODquODkOODvOOCueODiOODvOODs-ODj-OCpOODoA!5e0!3m2!1sja!2sjp!4v1638772504137!5m2!1sja!2sjp
            '" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Map, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRquestConfirmOrChange($kouhai_id, $senpai_id, $ls_title, $schedules, $until_confirm, $req_id)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエスト</h6>'.
                '<p class="talk_lesson_ttl ttl-block">'.
                '「'.$ls_title.'」'.
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';
        foreach ($schedules as $k => $v)
            $msg .= '<p class="detail_date">'.$v.'</p>'; //1月20日（金）16:00〜17:00

        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'.//1月14日（土）
                '</div>'.
                '<p class="talk_orange_btn arrow_mark">'.
                '<a href="'.route('user.talkroom.requestConfirm',['request_id'=>$req_id]).'">リクエスト内容の確認・変更</a>'.//D-29_30.php
                '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Rquest_ConfirmOrChange, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRquestChangeReq($kouhai_id, $senpai_id, $ls_title, $schedules, $until_confirm, $req_id)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエストが変更されました。</h6>'.
            '<p class="talk_lesson_ttl ttl-block">'.
            '「'.$ls_title.'」'.
            '</p>'.
            '<div class="detail_wrap">'.
            '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';
        foreach ($schedules as $k => $v)
            $msg .= '<p class="detail_date">'.$v.'</p>'; //1月20日（金）16:00〜17:00

        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'.//1月14日（土）
            '</div>'.
            '<p class="talk_orange_btn arrow_mark">'.
            '<a href="'.route('user.talkroom.requestConfirm',['request_id'=>$req_id]).'">リクエスト内容の確認・変更</a>'.//D-29_30.php
            '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Rquest_Change_req, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRquestChangeSchedule($kouhai_id, $senpai_id, $ls_title, $old_schedule, $new_schedule, $new_req_id)
    {
        $msg = '<h6 class="talk_kiji_ttl">予約リクエストが変更されました。</h6>'.
            '<p class="talk_lesson_ttl ttl-block">'.
            '「'.$ls_title.'」'.
            '</p>'.
            '<div class="detail_wrap">'.
            '<p class="detail_ttl  mark_left mark_square">変更内容</p>'.
            '<p class="detail_date">'.$old_schedule.'</p>'.//<p class="detail_date">1月20日（金）16:00〜17:00</p>
            '<p class="centre pt10 pb10">↓</p>'.
            '<p class="detail_date">'.$new_schedule.'</p>'.//<p class="detail_date">1月30日（金）16:00〜17:00</p>
            '</div>'.
            '<p class="talk_orange_btn arrow_mark">'.
            '<a href="'.route('user.talkroom.requestConfirm',['request_id'=>$new_req_id]).'">リクエスト内容の確認・変更</a>'.//D-29_30.php
            '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Request_Change_Schedule, $msg))
            return false;
        return true;

    }

    public static function saveKouhaiRequestCancel($kouhai_id, $senpai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img)
    {
         $msg = '<h6 class="talk_kiji_ttl">レッスンをキャンセルしました</h6>'.
                '<div class="detail_wrap">';
         if ( isset($reasons) && count($reasons) )
             $msg .= '<p class="detail_ttl  mark_left mark_square">キャンセル理由</p>';
         foreach ( $reasons as $reason ) {
             $msg .= '<p class="detail_date">'.$reason.'</p>';
         }

        $msg .= '<p class="detail_ttl  mark_left mark_square">キャンセル料</p>';

        if ($cancel_amount == 0)
            $msg .='<p class="detail_date">なし</p>';
        else
            $msg .='<p class="detail_date">'.CommonService::showFormatNum($cancel_amount).'円</p>';

        $msg .= '</div>'.
                '<div class="pickup_cancel_lesson">'.
                '<div>'.
                '<p class="one_line ttl-block">'.$ls_title.'</p>'.
                '<p>'.$start_date.'</p>'. //2021年3月18日　16:00～
                '</div>'.
                '<div>'.
                '<img src="'.CommonService::getLessonImgUrl($ls_img).'" alt="">'.
                '</div>'.
                '</div>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Request_Cancel, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRequestSenpaiCancel($kouhai_id, $senpai_id, $reasons, $ls_title, $start_date, $ls_img)
    {
        $msg = '<h6 class="talk_kiji_ttl">レッスンをキャンセルしました</h6>'.
            '<div class="detail_wrap">';
        if ( isset($reasons) && count($reasons) )
            $msg .= '<p class="detail_ttl  mark_left mark_square">キャンセル理由</p>';
        foreach ( $reasons as $key => $reason )
            $msg .= '<p class="detail_date">'.$reason.'</p>';

        $msg .= '<p class="detail_ttl  mark_left mark_square">キャンセル料</p>'.
            '<p class="detail_date">なし（先輩都合のため）</p>'.
            '</div>'.
            '<div class="pickup_cancel_lesson">'.
            '<div>'.
            '<p class="one_line ttl-block">'.$ls_title.'</p>'.
            '<p>'.$start_date.'</p>'. //2021年3月18日　16:00～
            '</div>'.
            '<div>'.
            '<img src="'.CommonService::getLessonImgUrl($ls_img).'" alt="">'.
            '</div>'.
            '</div>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Request_Senpai_Cancel, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRequestBuy($kouhai_id, $senpai_id, $ls_title, $lr_id, $schedules, $until_confirm)
    {
        /*$msg = '<h6 class="talk_kiji_ttl">予約を承認しました</h6>'.
                '<p class="talk_lesson_ttl ttl-block">'.
                '「'.$ls_title.'」'.
                '</p>'.
                '<div class="detail_wrap">'.
                '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>'.
                '<p class="detail_date">'.$sc_date.'</p>'.//1月20日（金）16:00〜17:00
                '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'. //承認期限：1月15日（日）
                '</div>'.
                '<p class="talk_orange_btn arrow_mark">'.
                '<a href="'.route('user.lesson.check_reserve',['lrs_id' => $sc_id]).'">レッスンを購入する</a>'. //A-24.php
                '</p>';*/

        $msg = '<h6 class="talk_kiji_ttl">予約を承認しました</h6>'.
            '<p class="talk_lesson_ttl ttl-block">'.
            '「'.$ls_title.'」'.
            '</p>'.
            '<div class="detail_wrap">'.
            '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>';

        $sc_ids = [];
        foreach( $schedules as $k => $v ){
            $msg .= '<p class="detail_date">'.$v.'</p>'; //<p class="detail_date">1月20日（金）16:00〜17:00</p>
        }
        $msg .= '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'. //承認期限：1月15日（日）
            '</div>'.
            '<p class="talk_orange_btn arrow_mark">'.
            '<a href="'.route('user.lesson.check_reserve',['lr_id' => $lr_id]).'">レッスンを購入する</a>'. //A-24.php
            '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Request_Buy, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiRequestConfirmChange($kouhai_id, $senpai_id, $ls_title, $sc_id, $sc_date, $until_confirm)
    {
        $msg = '<h6 class="talk_kiji_ttl">変更を承認しました。</h6>'.
            '<p class="talk_lesson_ttl ttl-block">'.
            '「'.$ls_title.'」'.
            '</p>'.
            '<div class="detail_wrap">'.
            '<p class="detail_ttl  mark_left mark_square">レッスン日時：</p>'.
            '<p class="detail_date">'.$sc_date.'</p>'.//1月20日（金）16:00〜17:00
            '<p class="detail_ttl  mark_left mark_square">承認期限：'.$until_confirm.'</p>'. //承認期限：1月15日（日）
            '</div>'.
            '<p class="talk_orange_btn arrow_mark">'.
            '<a href="'.route('user.lesson.check_reserve',['lrs_id' => $sc_id]).'">レッスンを購入する</a>'. //A-24.php
            '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Request_Confirm_Change, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg)
    {
        /*$msg = '<p>'.
                'レッスン開始時刻から'.$minutes.'分経過しました。<br>'.
                '<br>'.
                'レッスンのスタートおよびキャンセルが行われなかったため、このレッスンは実施扱いとなりました。'.
                '</p>';*/

        $new_msg = '<p>'.$msg.'</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Sys_Msg, $new_msg))
            return false;
        return true;
    }

    public static function saveKouhaiSysConfirm($kouhai_id, $senpai_id, $senpai_name, $sc_id)
    {
        $msg = '<p>'.
                $senpai_name.'センパイとの予約が成立しました。<br>'.
                '当日のことで確認しておきたいことなどを直接質問することができます。'.
                '</p>'.
                '<p class="talk_orange_btn">'.
                '<a href="'.
                route('user.talkroom.subscriptionLesson',['menu_type'=>config('const.menu_type.kouhai'),'schedule_id'=>$sc_id]).
                '">レッスン内容を確認する</a>'.//D-4.php
                '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Sys_Confirm, $msg))
            return false;
        return true;
    }

    public static function saveSenpaiSysCancelMoney($senpai_id, $kouhai_id)
    {


        $msg = '<p>'.
                'コウハイが現れない場合は、キャンセル申請を行ってください。<br>'.
                '自分の位置情報を共有しあなたが待ち合わせ場所の半径200m以内におり、後輩が半径200m以内にない、もしくは位置情報を共有していない場合所定のキャンセル料を受け取ることができます。'.
                '</p>'.
                '<p class="modal-link">'.
                '<a href="'.route('user.talkroom.cancelAbout').'" class="">'.
                'キャンセル料が発生する場合について'.
                '</a>'.
                '</p>';

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Sys_CancelMoney, $msg))
            return false;
        return true;

    }

    public static function saveKouhaiSysCancelMoney($kouhai_id, $senpai_id)
    {
        $msg = '<p>'.
            'センパイが現れない場合は、キャンセル申請を行ってください。<br>'.
            '自分の位置情報を共有した上であなたが待ち合わせ場所の半径200m以内にいる場合キャンセル料は発生しません。<br>'.
            '他にもキャンセル料の発生しない場合があります。'.
            '</p>'.
            '<p class="modal-link">'.
            '<a href="'.route('user.talkroom.cancelAbout').'" class="">'.
            'キャンセル料が発生する場合について'.
            '</a>'.
            '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Sys_CancelMoney, $msg))
            return false;
        return true;

    }

    public static function saveKouhaiSysPosShare($kouhai_id, $senpai_id, $minute, $lrs_id)
    {
         $msg = '<p>'.
                 'レッスン開始時刻から'.$minute.'分経過しました。<br>'.
                 'センパイと合流できていない場合は、必ず自分の位置情報を共有し、到着報告を行ってください。'.
                 '</p>'.
                 '<p class="about_cancel">'.
                 /*'※センパイが現れなかった場合、待ち合わせ圏内で位置情報を共有していないとキャンセル料が発生することがあります'.*/
                 '※レッスン時刻から15分経過後に、待ち合わせ圏内で位置情報を共有していない場合、キャンセル料が発生することがあります'.
                 '</p>'.
                 '<p class="modal-link">'.
                 '<a href="'.route('user.talkroom.cancelAbout').'" >'.
                 'キャンセル料が発生する場合について'.
                 '</a>'.
                 '</p>'.
                 '<p class="talk_orange_btn">'.
                 '<a href="'.route('user.talkroom.pos_info').'/'.$lrs_id.'">位置情報を共有する</a>'.
                 '</p>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Sys_PosShare, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiBtnCommon($kouhai_id, $senpai_id, $msg_type, $sch_id)
    {
        //self::Kouhai_Btn_LessonBuy
        //self::Kouhai_Btn_Start
        //self::Kouhai_Btn_PosCancel
        $msg = '';
        if ($msg_type == TalkroomService::Kouhai_Btn_PosCancel) {
            $msg = '<a href="'.route('user.talkroom.pos_info', ['lrs_id'=>$sch_id, 'available_cancel'=>1]).'">'.
                '<p>位置情報を共有しキャンセルする</p>'.
                '<p>※申請直後に共有オフになります</p>'.
                '</a>';
        }
        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), $msg_type, $msg, $sch_id))
            return false;
        return true;
    }

    public static function saveKouhaiBtnEvalution($kouhai_id, $senpai_id, $req_id, $sc_id)
    {
        $room = self::getTalkroom($kouhai_id, $senpai_id, config('const.menu_type.kouhai'));
        self::setTalkroomState($room['id'],config('const.talkroom_state.talking'));
        $msg = '<a href="'.
            route('user.talkroom.serviceEval',
                [
                    'user_id'=>$kouhai_id,
                    'req_id'=>$req_id,
                    'sch_id'=>$sc_id,
                    'room_id'=>$room['id']
                ]).
            '"><p>評価を入力する</p></a>';//D-21_22b.php

        if (!self::saveTalkroomData($kouhai_id, $senpai_id,config('const.menu_type.kouhai'), self::Kouhai_Btn_Evalution, $msg))
            return false;
        return true;
    }

    public static function saveKouhaiMap($kouhai_id, $senpai_id, $map_data)
    {
        $msg = '<iframe src="'.
            $map_data. //http://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.1881663481045!2d139.70811601528163!3d35.64773538020213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b41b58cd6f5%3A0x90b5d7b83c971892!2z44CSMTUwLTAwMTMg5p2x5Lqs6YO95riL6LC35Yy65oG15q-U5a-_77yR5LiB55uu77yY4oiS77yTIOaBteavlOWvv-ODquODkOODvOOCueODiOODvOODs-ODj-OCpOODoA!5e0!3m2!1sja!2sjp!4v1638772504137!5m2!1sja!2sjp
            '" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'),self::Kouhai_Map, $msg))
            return false;
        return true;
    }

    public static function setShareLocation($params)
    {
        $obj_user = User::find($params['user_id']);
        if (!is_object($obj_user)) return false;

        if (isset($params['request_schedule_id']) && $params['request_schedule_id']) {
            $obj_lrs = LessonRequestSchedule::find($params['request_schedule_id']);
            if (is_object($obj_lrs)) {
                if ($params['user_id'] == $obj_lrs->lrs_senpai_id ) { // if senpai
                    if ($params['is_share_location'] == 1) {
                        $obj_lrs->lrs_senpai_is_share_position = 0;
                    } else {
                        $obj_lrs->lrs_senpai_is_share_position = 1;
                    }
                    self::setSenpaiShareLocationMsg($params['user_id'], $obj_lrs->lrs_kouhai_id, $obj_lrs->lrs_senpai_is_share_position);
                } else {// if koupai
                    if ($params['is_share_location'] == 1) {
                        $obj_lrs->lrs_kouhai_is_share_position = 0;
                    } else {
                        $obj_lrs->lrs_kouhai_is_share_position = 1;
                    }
                    self::setKouhaiShareLocationMsg($params['user_id'], $obj_lrs->lrs_senpai_id, $obj_lrs->lrs_kouhai_is_share_position);
                }
                return $obj_lrs->save();
            }
        }
        return false;
    }

    public static function getMapLocation($condition)
    {
        $ret = [];
        $obj_user = User::find($condition['user_id']);
        if (!is_object($obj_user)) return $ret;

        if (isset($condition['request_schedule_id']) && $condition['request_schedule_id']) {
            $obj_lrs = LessonRequestSchedule::find($condition['request_schedule_id']);
            if (is_object($obj_lrs)) {
                $obj_lr = $obj_lrs->lesson_request;
                $obj_lesson = $obj_lr->lesson;
                $obj_senpai = $obj_lesson->senpai;

                $compare_datetime = Carbon::now()->addMinutes(-30)->format('H:i:s');
                $compare_date = Carbon::now()->format('Y-m-d');
                if ($compare_date == $obj_lrs->lrs_date && $obj_lrs->lrs_start_time >= $compare_datetime && $obj_lrs->lrs_state == config('const.schedule_state.reserve')) {
                    if ($condition['user_id'] == $obj_senpai->id ) { // if senpai

                        if ($obj_lrs->lrs_kouhai_is_share_position) {
                            $ret['user_location'] = UserService::getMapLocation($obj_lrs->lrs_kouhai_id);
                        }
                    } else {// if koupai
                        if ($obj_lrs->lrs_senpai_is_share_position) {
                            $ret['user_location'] = UserService::getMapLocation($obj_lrs->lrs_senpai_id);
                        }
                    }
                }
                $ret['lesson_areas'] = $obj_lesson->lesson_area;
            }
        }

        return $ret;
    }

    public static function existShareLocationByUser($user_id)
    {
        if (!$user_id) return 0;

        $ret = LessonRequestSchedule::where('lrs_state', config('const.schedule_state.reserve'));
        $ret->where(function($query) use($user_id) {
            $query->where(function($query1) use($user_id) {
                $query1->where('lrs_senpai_id', $user_id);
                $query1->where('lrs_senpai_is_share_position', 1);
            });
            $query->orWhere(function($query1) use($user_id) {
                $query1->where('lrs_kouhai_id', $user_id);
                $query1->where('lrs_kouhai_is_share_position', 1);
            });
        });
        $compare_datetime = Carbon::now()->addMinutes(-30)->format('H:i:s');
        $ret->where('lrs_start_time', '>=', $compare_datetime);
        $compare_datetime = Carbon::now()->format('Y-m-d');
        $ret->where('lrs_date', $compare_datetime);
        $ret->get();
        return is_object($ret) && $ret->count() > 0 ? 1 : 0;
    }

    public static function setKouhaiShareLocationMsg($kouhai_id, $senpai_id, $share_val=0)
    {
        $msg = '位置情報の共有をオフにしました。';
        if ($share_val == 1) {
            $msg = '位置情報の共有をオンにしました。';
        }

        $msg_senpai = '<p>後輩が'.$msg.'</p>';

        $msg = '<p>'.$msg.'</p>';
        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Share_Location, $msg))
            return false;

        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Share_Location, $msg_senpai))
            return false;

        return true;
    }

    public static function setSenpaiShareLocationMsg($senpai_id, $kouhai_id, $share_val=0)
    {
        $msg = '位置情報の共有をオフにしました。';
        if ($share_val == 1) {
            $msg = '位置情報の共有をオンにしました。';
        }

        $msg_senpai = '<p>先輩が'.$msg.'</p>';

        $msg = '<p>'.$msg.'</p>';
        if (!self::saveTalkroomData($senpai_id, $kouhai_id, config('const.menu_type.senpai'), self::Senpai_Share_Location, $msg))
            return false;

        if (!self::saveTalkroomData($kouhai_id, $senpai_id, config('const.menu_type.kouhai'), self::Kouhai_Share_Location, $msg_senpai))
            return false;

        return true;

    }

}
