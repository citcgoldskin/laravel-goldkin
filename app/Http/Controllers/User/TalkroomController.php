<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

use App\Http\Requests\User\EvalutionRequest;
use App\Http\Requests\User\LessonReserveUpdateRequest;
use App\Models\Evalution;
use App\Models\LessonRequest;
use App\Models\LessonRequestSchedule;
use App\Models\Talkroom;
use App\Models\User;
use App\Service\AppealService;
use App\Service\BlockService;
use App\Service\EvalutionService;
use App\Service\InformService;
use App\Service\SettingService;
use App\Service\MessageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Service\CalendarService;
use App\Service\TalkroomService;
use App\Service\LessonService;
use App\Models\Lesson;
use App\Service\SenpaiService;
use App\Service\UserService;
use App\Service\CommonService;
use App\Http\Requests\User\CancelReqRequest;
use Auth;
use multitypetest\model\Car;

class TalkroomController extends Controller
{
    protected $page_limit = 20;

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function list(Request $request, $type = 0/*config('const.menu_type.kouhai')*/)
    {
        //test code
        /*$schedules[0] = '3月20日（金）16:00〜17:00';
        $schedules[1] = '3月20日（金）16:00〜17:00';
        $until_confirm = '3月14日（土）';
        $old_schedule = '3月20日（金）16:00〜17:00';
        $new_schedule = '3月21日（金）16:00〜17:00';
        $sc_date = '1月20日（金）16:00〜17:00';
        $sc_id = 1;
        $senpai_id = 2;
        $kouhai_id = 1;
        $ls_title = "titletitletitletitletitletitletitletitletitletitletitletitle";
        $reason = "reasonreasonreasonreasonreasonreasonreasonreason";
        $start_date = '2021年3月18日　16:00～';
        $ls_img = null;
        $cancel_amount = 5000;
        $msg = '美久さん。<br>本日のレッスンお疲れ様でした。<br>もしよろしければ○○○センパイの評価をお願いします。<br><br>※評価した内容は相手側には公開されません。';
        $kouhai_name = 'kouhai';
        $senpai_name = 'senpai';
        $minute = 10;
        $req_id = 1;
        $map_data = '1234567890-23456789';
        TalkroomService::saveSenpaiRequestResponse($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm);
        TalkroomService::saveSenpaiRequestChangeResponse($senpai_id, $kouhai_id, $ls_title, $old_schedule, $new_schedule);
        TalkroomService::saveSenpaiRequestConfirmOrChange($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm, $req_id);
        TalkroomService::saveSenpaiRequestCancel($senpai_id, $kouhai_id, $reason, $cancel_amount, $ls_title, $start_date, $ls_img );
        TalkroomService::saveSenpaiRequestConfirm($senpai_id, $kouhai_id, $ls_title, $sc_id, $sc_date);
        TalkroomService::saveSenpaiSysMsg($senpai_id, $kouhai_id, $msg);
        TalkroomService::saveSenpaiSysRequestConfirm($senpai_id, $kouhai_id, $kouhai_name, $sc_id);
        TalkroomService::saveSenpaiSysPosShare($senpai_id, $kouhai_id, $minute);
        TalkroomService::saveSenpaiBtnCommon($senpai_id, $kouhai_id, TalkroomService::Senpai_Btn_LessonBuy);
        TalkroomService::saveSenpaiBtnCommon($senpai_id, $kouhai_id, TalkroomService::Senpai_Btn_PosCancel);
        TalkroomService::saveSenpaiBtnEvalution($senpai_id, $kouhai_id, $req_id, $sc_id);
        TalkroomService::saveSenpaiMap($senpai_id, $kouhai_id, $map_data);

        TalkroomService::saveKouhaiRquestConfirmOrChange($kouhai_id, $senpai_id, $ls_title, $schedules, $until_confirm, $req_id);
        TalkroomService::saveKouhaiRequestCancel($kouhai_id, $senpai_id, $reason, $cancel_amount, $ls_title, $start_date, $ls_img);
        TalkroomService::saveKouhaiRequestBuy($kouhai_id, $senpai_id, $ls_title, $sc_id, $sc_date, $until_confirm);
        TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);
        TalkroomService::saveKouhaiSysConfirm($kouhai_id, $senpai_id, $senpai_name, $sc_id);
        TalkroomService::saveKouhaiSysCancelMoney($kouhai_id, $senpai_id);
        TalkroomService::saveKouhaiSysPosShare($kouhai_id, $senpai_id$minute);
        TalkroomService::saveKouhaiBtnCommon($kouhai_id, $senpai_id, TalkroomService::Kouhai_Btn_LessonBuy);
        TalkroomService::saveKouhaiBtnCommon($kouhai_id, $senpai_id, TalkroomService::Kouhai_Btn_Start);
        TalkroomService::saveKouhaiBtnCommon($kouhai_id, $senpai_id, TalkroomService::Kouhai_Btn_PosCancel);
        TalkroomService::saveKouhaiBtnEvalution($kouhai_id, $senpai_id, $req_id, $sc_id);
        TalkroomService::saveKouhaiMap($kouhai_id, $senpai_id, $map_data);*/

        return view('user.talkroom.list',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'type' => $type
            ]);
    }

    public function subscriptionCal(Request $request, $cur_ym)
    {
        $infos = CalendarService::getCalendarInfos($cur_ym);
        $user_id = Auth::user()->id;
        $sdate = $infos['current'].'-01';
        $edate =  $infos['current'].'-'.$infos['days'];
        $kouhais = LessonService::getSchedulesInKouhaiMenu($user_id, $sdate, $edate);
        $senpais = LessonService::getSchedulesInSenpaiMenu($user_id, $sdate, $edate);
        return view('user.talkroom.subscriptionCal',
            [
             'page_id' => 'talkroom',
             'page_id_02' => '',
             'infos' => $infos,
             'kouhais' => $kouhais,
             'senpais' => $senpais
            ]);
    }

    public function getScheduleInfo(Request $request)
    {
        $selDay = $request->selDay;
        $selYM = $request->selYM;
        $user_id = Auth::user()->id;
        $selDate = date('Y-m-d', strtotime($selYM.'-'.$selDay));
        $kouhais = LessonService::getSchedulesInKouhaiMenu($user_id, $selDate, $selDate);
        $senpais = LessonService::getSchedulesInSenpaiMenu($user_id, $selDate, $selDate);
        $weekday_name = config('const.week_days')[Carbon::parse($selDate)->weekday()];
        $lb_sel_date = Carbon::parse($selDate)->locale('ja')->format('n月j日')."（".$weekday_name."）";
        return response()->json([
            'kouhais'=>( isset($kouhais) && isset($kouhais[$selDate]) )?$kouhais[$selDate]:[],
            'senpais' =>( isset($senpais) && isset($senpais[$selDate]) )?$senpais[$selDate]:[],
            'selDate' => Carbon::createFromDate($selDate),
            'lb_sel_date' => $lb_sel_date
        ]);

    }

    public function subscriptionLesson(Request $request, $menu_type, $schedule_id)
    {
        $schedule_info = LessonService::getScheduleInfoById($schedule_id);
        if ($schedule_info == NULL)
            return redirect()->route('user.talkroom.list');

        if ($menu_type == config('const.menu_type.kouhai')) {
            $senpai_id =$schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
            $user_id = $senpai_id;
        } else {
            $user_id = $schedule_info['lesson_request']['lr_user_id'];
        }

        $req_info = $schedule_info['lesson_request'];
        $req_info['man_woman']      = CommonService::getManWomanStr($schedule_info['lesson_request']['lr_man_num'], $schedule_info['lesson_request']['lr_woman_num']);
        $req_info['amount']         = CommonService::showFormatNum($schedule_info['lrs_amount']);
        $req_info['total_amount']   = CommonService::showFormatNum($schedule_info['lrs_amount'] - $schedule_info['lrs_fee']);
        $req_info['fee_type']       = CommonService::getFeeTypeStr($schedule_info['lrs_fee_type']);
        $req_info['service_fee']    = CommonService::showFormatNum($schedule_info['lrs_service_fee']);
        $req_info['reserve_date']   = CommonService::getYMDAndHM($schedule_info['lrs_reserve_date']);
        $req_info['lesson_day']     = CommonService::getYMD($schedule_info['lrs_date']);
        $req_info['lesson_stime']   = CommonService::getHM($schedule_info['lrs_start_time']);
        $req_info['lesson_etime']   = CommonService::getHM($schedule_info['lrs_end_time']);

        if ( !$user_id )
            return redirect()->route('user.talkroom.list');

        $user_info = $schedule_info['lesson_request']['user'];
        if ($menu_type == config('const.menu_type.kouhai')) {
            $user_info = User::find($user_id);
        }
        if (!empty($user_info)) {
            $user_info['user_avatar'] = CommonService::getUserAvatarUrl($user_info['user_avatar']);
            $user_info['age'] = CommonService::getAge($user_info['user_birthday']);
            $user_info['sex'] = CommonService::getSexStr($user_info['user_sex']);
        }

        return view('user.talkroom.subscriptionLesson',
            [
            'page_id' => 'talkroom',
            'page_id_02' => '',
            'menu_type' => $menu_type,
            'req_info' => $req_info,
            'user_info' => $user_info,
            'sch_id' =>   $schedule_id
            ]);
    }

    public function requestResp(Request $request, $old_schedule_id, $new_schedule_id)
    {
        $old_schedule_info = LessonService::getScheduleInfoById($old_schedule_id);
        $new_schedule_info = LessonService::getScheduleInfoById($new_schedule_id);

        // user validation
        $user_id = Auth::user()->id;
        if ( $new_schedule_info['lesson_request']['lesson']['lesson_senpai_id'] != $old_schedule_info['lesson_request']['lesson']['lesson_senpai_id'] ||
            $new_schedule_info['lesson_request']['lesson']['lesson_senpai_id'] != $user_id ) {
            return redirect()->route('user.talkroom.list');
        }
        return view('user.talkroom.requestResp', [
            'old_schedule_info' => $old_schedule_info,
            'new_schedule_info' => $new_schedule_info,
            'page_id' => 'listing', 'page_id_02' => '']);
    }

    public function respComplete(Request $request)
    {
        $approval = $request['approval'];
        if ( $approval == 0 ) {
            return redirect()->route('user.talkroom.list');
        }

        $schedule_id = $request['schedule_id'];
        $res = LessonService::updateScheduleState($schedule_id, config('const.schedule_state.confirm'));
        if ( !$res ) {
            return redirect()->route('user.talkroom.list');
        }

        $schedule_info = LessonService::getScheduleInfoById($schedule_id);

        // send talkroom message
        $senpai_id = Auth::user()->id;
        $kouhai_id = $schedule_info['lesson_request']['lr_user_id'];
        $ls_title = $schedule_info['lesson_request']['lesson']['lesson_title'];
        $sc_date = CommonService::getMDAndWeek($schedule_info['lrs_date']) . ' ' .
                    CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']);

        $until_confirm = CommonService::getMDAndWeek($schedule_info['lesson_request']['lr_until_date']);
        TalkroomService::saveKouhaiRequestConfirmChange($kouhai_id, $senpai_id, $ls_title, $schedule_id, $sc_date, $until_confirm);
        TalkroomService::saveSenpaiRequestConfirmChange($senpai_id, $kouhai_id, $ls_title, $sc_date);

        // send todo_list message
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "reserve_confirm");

        $talkroom_id = TalkroomService::getTalkroom($senpai_id, $kouhai_id, config('const.menu_type.senpai'));
        return view('user.talkroom.respComplete', [
            'talkroom_id' => $talkroom_id,
            'page_id' => 'talkroom', 'page_id_02' => '']);
    }

    public function talkData(Request $request, $menu_type, $room_id)
    {
        $talk_user_info = TalkroomService::getTalkUserInfo($room_id);
        $obj_user = Auth::user();

        $reserves = null;
        if ( $menu_type == config('const.menu_type.kouhai') ) {
            $reserves = LessonService::getReserveSchedulesInKouhaiMenu($talk_user_info['id'], $obj_user->id);
        } else if ( $menu_type == config('const.menu_type.senpai') ) {
            $reserves = LessonService::getReserveSchedulesInSenpaiMenu($talk_user_info['id'], $obj_user->id);
        }

        if (is_null($reserves))
            return redirect()->route('user.talkroom.list');

        foreach( $reserves as $k => $v) {
            $reserves[$k]['reserve'] = date('Y/n/j', strtotime($v['lrs_date'])).' '.date('H:i', strtotime($v['lrs_start_time'])).'～'.date('H:i', strtotime($v['lrs_end_time']));
        }

        return view('user.talkroom.talkData',
            [
             'page_id' => 'talkroom',
             'page_id_02' => '',
             'room_id' => $room_id,
             'menu_type' => $menu_type,
             'talk_user_info' =>  $talk_user_info,
             'reserves' => $reserves
            ]);
    }

    public function setting(Request $request, $type, $from_user_id)
    {
        $state = config('const.schedule_state.complete');
        if ($type == config('const.menu_type.kouhai')) {
            $sch_list = LessonService::getScheduleListBySenpaiId($from_user_id, $state)->paginate($this->page_limit);
        } else {
            $sch_list = LessonService::getScheduleListByKouhaiId($from_user_id, $state)->paginate($this->page_limit);
        }
        $isInformUser = InformService::isInformUser($from_user_id);
        $isBlockUser = BlockService::isBlockUser($from_user_id);

        $obj_from_user = User::find($from_user_id);

        $login_user_id = Auth::User() ? Auth::User()->id : 0;

        return view('user.talkroom.setting',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'isInformUser' => $isInformUser,
                'isBlockUser' => $isBlockUser,
                'fromUserId' => $from_user_id,
                'obj_from_user' => $obj_from_user,
                'login_user_id' => $login_user_id,
                'schList'=> $sch_list,
                'type' => $type
            ]);
    }

    public function setInformState(Request $request)
    {
        $informUserId = $request->get('informUserId');
        $state = $request->get('state')=='true'? true:false;
        $result['state'] = InformService::setInformState($informUserId, $state);
        return response()->json($result);
    }

    public function setBlockState(Request $request)
    {
        $blockUserId = $request->get('blockUserId');
        $state = $request->get('state')=='true'? true:false;
        $obj_user = Auth::user();
        $result['isTransferUser'] = count(LessonService::getReserveSchedulesInKouhaiMenu($blockUserId, $obj_user->id)) > 0 ? true: false;
        if (!$result['isTransferUser']) {
            $result['state'] = BlockService::setBlockState($blockUserId, $state);
        }
        return response()->json($result);
    }

    public function appeal(Request $request, $type, $from_user_id)
    {
        $appealClasses = AppealService::getAppealClasses();
        return view('user.talkroom.appeal',
            [
                'appClass' => $appealClasses,
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'type' => $type,
                'fromUserId' => $from_user_id
            ]);
    }

    public function sendAppeals(Request $request) {

        $params = $request->all();
        $user_id = Auth::User()->id;

        $status = false;
        if ($obj_appeal=AppealService::doAppeals($user_id, $params)) {
            $status = AppealService::saveAppealsReport($obj_appeal->id, $params['vals']);
        }

        $appealUserInfo = UserService::getUserByID($params['appealId']);
        $result['state'] = $status;
        $result['name'] = $appealUserInfo['user_firstname'] .  $appealUserInfo['user_lastname'];
        return response()->json($result);
    }

    public function clickStartBtn(Request $request) {
        $sch_id = (int)$request->get('schId');
        $result['state'] = LessonService::updateScheduleState($sch_id, config('const.schedule_state.start'));
        return response()->json($result);
    }

    public function serviceEval(Request $request)
    {
        if (!isset($request['user_id']) ||
            !isset($request['req_id']) ||
            !isset($request['sch_id']) ||
            !isset($request['room_id']))
            return redirect()->route('user.talkroom.list');

        $user_id = $request['user_id'];
        $req_id =  $request['req_id'];
        $sch_id = $request['sch_id'];
        $room_id = $request['room_id'];

        // check exist evalution
        $exist_eval = EvalutionService::isExistEvalution($user_id, $sch_id);

        $eval_types = EvalutionService::getEvalutionTypes(EvalutionService::KOUHAIS_EVAL);

        return view('user.talkroom.serviceEval',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'user_id' => $user_id,
                'req_id' => $req_id,
                'sch_id' => $sch_id,
                'room_id' => $room_id,
                'exist_eval' => $exist_eval,
                'eval_types' => $eval_types
            ]);
    }

    public function postEval(Request $request)
    {
        $req_id = $request->get('req_id');
        $req_info = LessonService::getLessonRequestInfo($req_id);
        if ($req_info) {
            $types = $request->get('types');

            if (is_array($types)) {
                $data['eval_user_id'] = $request->get('user_id');
                $data['eval_kind'] = $request->get('kind');
                $data['eval_lesson_id'] = $req_info['lr_lesson_id'];
                if ($data['eval_kind'] == EvalutionService::SENPAIS_EVAL) {
                    $data['target_user_id'] = $req_info['lr_user_id'];
                } else if($data['eval_kind'] == EvalutionService::KOUHAIS_EVAL) {
                    $data['target_user_id'] = LessonService::getSenpaiIdFromLesson($req_info['lr_lesson_id']);
                }
                $data['eval_lesson_request_id'] = $req_id;
                $data['eval_schedule_id'] = $request->get('sch_id');

                foreach ($types as $k => $v) {
                    $data['eval_type_id'] = $k;
                    $data['eval_val'] = $v;
                    EvalutionService::doCreate($data);
                }
                $result = ['state'=> true];
            }
        }

        if (!isset($result))
            $result = ['state'=> false];

        return response()->json($result);
    }

    public function kouhaiEval(Request $request)
    {
        if (!isset($request['user_id']) ||
            !isset($request['req_id']) ||
            !isset($request['sch_id']) ||
            !isset($request['room_id']))
            return redirect()->route('user.talkroom.list');

        $user_id = $request['user_id'];
        $req_id =  $request['req_id'];
        $sch_id = $request['sch_id'];
        $room_id = $request['room_id'];

        // check exist evalution
        $exist_eval = EvalutionService::isExistEvalution($user_id, $sch_id);

        $eval_types = EvalutionService::getEvalutionTypes(EvalutionService::SENPAIS_EVAL);
        return view('user.talkroom.kouhaiEval',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'user_id' => $user_id,
                'req_id' => $req_id,
                'sch_id' => $sch_id,
                'room_id' => $room_id,
                'exist_eval' => $exist_eval,
                'eval_types' => $eval_types
            ]);
    }

    public function pos_info(Request $request, $lrs_id)
    {
        // from 位置情報を共有しキャンセルする
        $available_cancel = $request->input('available_cancel', null);

        $obj_lrs = LessonRequestSchedule::find($lrs_id);
        if (!is_object($obj_lrs)) {
            $obj_lrs = null;
        }

        return view('user.talkroom.pos_info',
            [
                'page_id' => 'talkroom',
                'obj_lrs' => $obj_lrs,
                'page_id_02' => 'location',
                'available_cancel' => $available_cancel
            ]);
    }

    public function requestConfirm(Request $request, $requset_id)
    {

        $req_info = LessonService::getRequestOriginInfo($requset_id);

        if ($req_info == NULL)
            return redirect()->route('user.talkroom.list');

        return view('user.talkroom.requestConfirm',
            [
                'page_id' => 'talkroom',
                'page_id_02' => '',
                'req_info' => $req_info
            ]
        );
    }

    public function requestEdit(Request $request, $req_id)
    {
        // $req_info = LessonService::getRequestInfo($req_id);
        $req_info = LessonService::getRequestAllInfo($req_id);

        if ( $req_info == NULL ){
            return redirect()->route('user.talkroom.list');
        }

        // user validation
        $user_id = Auth::user()->id;
        if ( $req_info['lr_user_id'] != $user_id ) {
            return redirect()->route('user.talkroom.list');
        }

        return view('user.talkroom.requestEdit', [
            'req_info' => $req_info,
            'page_id' => 'talkroom',
            'page_id_02' => ''
        ]);
    }

    public function updateRequest(LessonReserveUpdateRequest $request) {
        $params = $request->all();

        $condition = array();
        $condition['lr_id'] = $params['req_id'];

        // user validation
        $kouhai_id = Auth::user()->id;
        $req_info = LessonRequest::where('lr_id', $params['req_id'])
                                    ->with('lesson')
                                    ->first();
        if ( $req_info['lr_user_id'] != $kouhai_id ) {
            return redirect()->route('user.talkroom.list');
        }

        $update_params = array();
        $update_params['lr_man_num'] = $params['lr_man_num'];
        $update_params['lr_woman_num'] = $params['lr_woman_num'];
        $update_params['lr_until_confirm'] = $params['until_confirm'];
        $update_params['lr_area_id'] = $params['area_id'];
        $update_params['lr_address'] = $params['address'];
        $update_params['lr_address_detail'] = $params['address_detail'];
        $update_params['lr_pos_discuss'] = $params['pos_discuss'];
        $update_params['lr_target_reserve'] = $params['target_reserve'];

        // todo update parameters for lesson area
        /////////////////////////////////////////

        // if lr_state = 回答（1）, set lr_state = 送信（0）and lesson_request_schedules lrs_state=送信（0）
        if ($req_info['lr_state'] == config('const.req_state.response')) {
            // set lr_state = 送信（0）
            $update_params['lr_state'] = config('const.req_state.request');
            // set lesson_request_schedules lrs_state=送信（0）
            LessonService::doConvertRequestScheduleToRequestStatus($req_info['lr_id']);
        }

        $res = LessonService::doUpdateRequest($update_params, $condition);

        if ( !$res ) {
            return response()->json(["result_code" => "failed"]);
        }

        // schedules for talkroom
        $schedules = array();

        if (isset($params['sel_schedule_html'])) {
            // update schedule data
            LessonService::doDeleteSchedulesByReqId($params['req_id']);

            foreach ( $params['sel_schedule_html'] as $key => $value ) {
                $schedule_params = CommonService::getScheduleForDB($value);

                $schedule_params['lr_id'] = $params['req_id'];
                $schedule_params['30min_fees'] = $params['30min_fees'] * ($params['lr_man_num'] + $params['lr_woman_num']);
                $schedule_params['no_confirm'] = config('const.no_confirm.cancel_request');

                // for talkroom
                $schedules[] = CommonService::getMDAndWeek($schedule_params['date']) .
                    CommonService::getStartAndEndTime($schedule_params['start_time'], $schedule_params['end_time']);

                $schedule_params['lrs_kouhai_id'] = $kouhai_id;
                $schedule_params['lrs_senpai_id'] = $req_info['lesson']['lesson_senpai_id'];

                $res = LessonService::doCreateRequestSchedule($schedule_params);
                if (!$res) {
                    return response()->json(["result_code" => "failed"]);
                }
            }
        }

        // send talkroom message
        $senpai_id = $req_info['lesson']['lesson_senpai_id'];
        $ls_title = $req_info['lesson']['lesson_title'];
        $until_confirm = $params['until_confirm'];
        $req_id = $params['req_id'];
        TalkroomService::saveKouhaiRquestChangeReq($kouhai_id, $senpai_id, $ls_title, $schedules, $until_confirm, $req_id);
        TalkroomService::saveSenpaiRequestChangeReqResponse($senpai_id, $kouhai_id, $ls_title, $schedules, $until_confirm, $req_id);

        // send todo_list message
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "request_change");

        // return response()->json(["result_code" => "success"], 200);
        $contents = "リクエストを変更しました。";
        $modal_confrim_url = route('user.talkroom.list');

        return view('user.layouts.modal_ok',
            ['page_id' => 'talkroom',
                'page_id_02' => '',
                'contents'=>$contents,
                'url'=>$modal_confrim_url]);
    }

    public function requestCancel(Request $request, $req_id)
    {
        $req_info = LessonService::getRequestAllInfo($req_id);
        if ( !$req_info ) {
            return redirect()->route('user.talkroom.list');
        }

        // user validation
        $user_id = Auth::user()->id;
        if ( $req_info['lr_user_id'] != $user_id ) {
            return redirect()->route('user.talkroom.list');
        }

        return view('user.talkroom.requestCancel', [
            'req_info' => $req_info,
            'page_id' => 'talkroom', 'page_id_02' => '']);
    }

    public function cancelConfirm(CancelReqRequest $request)
    {
        $params = $request->all();

        $cancel_list = array();
        foreach ( $params['cancel_list'] as $key => $schedule_id ) {
            $cancel_list[] = LessonService::getScheduleInfoById($schedule_id);
        }

        return view('user.talkroom.cancelConfirm', [
            'cancel_list' => $cancel_list,
            'req_id' => $params['req_id'],
            'page_id' => 'talkroom', 'page_id_02' => '']);
    }

    public function cancelSchedules(Request $request) {
        $params = $request->all();

        $state = config('const.schedule_state.cancel_kouhai');
        foreach ( $params['cancel_list'] as $key => $value ) {
            $res = LessonService::updateScheduleState($value, $state);
            if ( !$res ) {
                return response()->json(["result_code" => "failed"]);
            }

            // send talkroom message
            $kouhai_id = Auth::user()->id;
            $schedule_info = LessonService::getScheduleInfoById($value);
            $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
            $reasons = array();
            $ls_title = $schedule_info['lesson_request']['lesson']['lesson_title'];
            $start_date = CommonService::getYMD($schedule_info['lrs_date']) . ' ' .
                date('H:i') . '~';
            $ls_img = CommonService::unserializeData($schedule_info['lesson_request']['lesson']['lesson_image'])[0];
            $cancel_amount = 0;

            TalkroomService::saveKouhaiRequestCancel($kouhai_id, $senpai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img);
            TalkroomService::saveSenpaiRequestKouhaiCancel($senpai_id, $kouhai_id, $reasons, $cancel_amount, $ls_title, $start_date, $ls_img );
        }

        return response()->json(["result_code" => "success"], 200);
    }

    public function cancelAbout(Request $request)
    {
        return view('user.talkroom.cancelAbout', ['page_id' => 'talkroom', 'page_id_02' => '']);
    }

    public function getMessages(Request $request) {
        $previous_messages = [];
        $new_messages = [];
        $room_id = $request->id;
        if ($request->get_method == 'old') {
            $previous_messages = TalkroomService::getPreviousMessages($room_id, $request->previous_id);
        } else {
            $new_messages = TalkroomService::getNewMessages($room_id);
        }
        return response()->json(['previous_messages'=>$previous_messages, 'new_messages' => $new_messages]);
    }

    public function sendMessage(Request $request)
    {
        $result = [];
        if($request->has('id')) {
            $result = TalkroomService::saveMessage($request->all());
            if($result) {
                $result['result_code'] = 'success';
                $result['message'] = $result->toArray();
                return response()->json($result);
            }
        }
        $result['result_code'] = 'fail';
        return response()->json($result);
    }

    public function getMapLocation(Request $request) {
        $params = $request->all();
        if (isset($params['user_id']) && $params['user_id']) {
            $result = TalkroomService::getMapLocation($params);
            return response()->json([
                "result" => "success",
                "area" => $result
            ]);
        }
        return response()->json(["result" => "failed"]);
    }

    public function setShareLocation(Request $request) {
        $params = $request->all();
        if (isset($params['user_id']) && $params['user_id']) {
            if (TalkroomService::setShareLocation($params)) {
                return response()->json([
                    "result" => "success"
                ]);
            }
        }
        return response()->json(["result" => "failed"]);
    }

    // 位置情報を共有しキャンセルする
    public function lessonCancelByPositionShare(Request $request) {
        $params = $request->all();

        $lrs_id = $params['lrs_id'];
        $obj_lrs = LessonRequestSchedule::find($lrs_id);

        $own_user_id = $params['user_id'];
        $other_user_id = $own_user_id == $obj_lrs->lrs_senpai_id ? $obj_lrs->lrs_kouhai_id : $obj_lrs->lrs_senpai_id;

        $menu_type = 0;
        if ($own_user_id == $obj_lrs->lrs_senpai_id) {
            $menu_type = 1;
        }

        $obj_room = Talkroom::where('user_id', $own_user_id)
            ->where('menu_type', $menu_type)
            ->where('talk_user_id', $other_user_id)
            ->where('state', config('const.talkroom_state.talking'))
            ->first();

        $room_id = 0;
        if(is_object($obj_room)) {
            $room_id = $obj_room->id;
        }

        // cancel payment code
        $cancel_money = $params['cancel_money'];

        return redirect()->route('user.talkroom.talkData', [
            'menu_type' => $menu_type,
            'room_id' => $room_id
        ]);
    }

}
