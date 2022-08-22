<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LessonRequestSchedule;
use App\Service\CalendarService;
use App\Service\CommonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Service\LessonService;
use App\Service\LessonClassService;
use App\Service\SettingService;
use App\Service\TalkroomService;
use App\Service\EvalutionService;
use App\Service\MessageService;
use App\Models\CancelReasonType;
use Illuminate\Support\Facades\Auth;

class SyutupinnController extends Controller
{
    protected $per_page = 10;
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function lessonList(Request $request)
    {
        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));

        $params = $request->all();
        $lessons_obj = LessonService::getLessonDetailList(config('const.lesson_tab.public'));
        $lesson_list1 = $lessons_obj->paginate($this->per_page);
        $pages1 = $lesson_list1->lastPage();

        $lessons_obj = LessonService::getLessonDetailList(config('const.lesson_tab.checking'));
        $lesson_list2 = $lessons_obj->paginate($this->per_page);
        $pages2 = $lesson_list2->lastPage();

        $lessons_obj = LessonService::getLessonDetailList(config('const.lesson_tab.draft'));
        $lesson_list3 = $lessons_obj->paginate($this->per_page);
        $pages3 = $lesson_list3->lastPage();

        $req_count = LessonService::getCountRequestBySenpaiId(Auth::user()->id);
        $req_count = $req_count > 0 ? CommonService::showFormatNum($req_count) : '';
        $var_params = [
            'page_id' => 'syutupinn',
            'page_id_02' => '',
            'req_count'=>$req_count,
            'lesson_list1'=>$lesson_list1, 'pages1'=>$pages1,
            'lesson_list2'=>$lesson_list2, 'pages2'=>$pages2,
            'lesson_list3'=>$lesson_list3, 'pages3'=>$pages3
        ];

        if (isset($params['page_from'])) {
            $var_params['page_from'] = $params['page_from'];
        }
        return view('user.syutupinn.lesson_list', $var_params);
    }

    public function getAjaxList(Request $request, $tab=1)
    {
        $lessons_obj = LessonService::getLessonDetailList($tab);
        $lesson_list = $lessons_obj->paginate($this->per_page);
        $pages = $lesson_list->lastPage();
        foreach ($lesson_list as $k=>$v){
            $v['lesson_image'] = CommonService::unserializeData($v['lesson_image']);
        }
        return response()->json(
            [
                'state' => "success",
                'lesson_list' => $lesson_list,
                'pages' => $pages
            ]
        );
    }

    public static function regLesson(Request $request, $type = 0, $lesson_id = 0 )
    {
        $view = 'user.syutupinn.consult_add';
        if ($type == config('const.lesson_type.online'))
            $view = 'user.syutupinn.online_add';

        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));
        $lesson_classes = LessonClassService::getLessonClasses();
        $lesson_cond = LessonService::getLessonConditions();
        $lesson_info = null;
        if($lesson_id > 0){ //edit
            $lesson_info = LessonService::getLessonInfo($lesson_id);
            if($lesson_info->count()){
                $lesson_info['lesson_image'] = CommonService::unserializeData($lesson_info['lesson_image']);
            }else{
                $request->session()->flash('error', 'レッスン情報が存在しないです.');
                return app('redirect')->back();
            }
        }

        return view($view,
            [
                'page_id' => 'syutupinn',
                'page_id_02' => '',
                'lesson_cond' => $lesson_cond,
                'lesson_classes' => $lesson_classes,
                'lesson_info'=>$lesson_info
            ]);

    }

    public static function schedule(Request $request)
    {
        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));

        $params = $request->all();

        $req_count = LessonService::getCountRequestBySenpaiId(Auth::user()->id);
        $req_count = $req_count > 0 ? CommonService::showFormatNum($req_count) : '';

        $week_start = date('Y-m-d', strtotime('-1 weeks  Sunday'));

        $end_week_day = Carbon::parse($week_start)->addWeeks(8);

        $var_params = [
            'page_id' => 'syutupinn',
            'page_id_02' => '',
            'week_start' => $week_start,
            'end_week_day' => $end_week_day,
            'req_count'=>$req_count
        ];
        if (isset($params['page_from'])) {
            $var_params['page_from'] = $params['page_from'];
        }

        return view('user.syutupinn.schedule', $var_params);
    }

    public static function getSchedule(Request $request){
        $param = $request->post();
        if ( isset($param['senpai_id']) ) {
            $senpai_id = $param['senpai_id'];
        } else {
            $senpai_id = Auth::user()->id;
        }
        $schedule_list = LessonService::getSchedulesForSenpai($senpai_id, $param['year'], $param['month']);
        return response()->json(['state' => true, 'list' => $schedule_list]);
    }

    public static function getWeekSchedule(Request $request){
        $param = $request->post();
        if ( isset($param['senpai_id']) ) {
            $senpai_id = $param['senpai_id'];
        } else {
            $senpai_id = Auth::user()->id;
        }

        $result['cur_date'] = $param['date'];
        $result['prev_date'] =date('Y-m-d', strtotime($param['date'].' -7 days'));
        $result['next_date'] =date('Y-m-d', strtotime($param['date'].' +7 days'));
        $time = CalendarService::getCalendarInfos(date('Y-m', strtotime($param['date'])));
        $result['ym'] = $time['current_label'];

        $schedule_list = LessonService::getWeekSchedulesForSenpai($senpai_id, $param['date']);
        $calendar_header_list = LessonService::getWeekDateInfo($param['date']);

        return response()->json([
            'state' => true,
            'list' => $schedule_list,
            'week_info' => $result,
            'calendar_header_list' => $calendar_header_list
        ]);
    }

    public static function saveSchedule(Request $request)
    {
        $param = $request->post();
        $senpai_id = Auth::user()->id;
        $result = true;
        $new_schedule = explode(',', $param['new_schedule']);
        $cancel_schedule = explode(',', $param['cancel_schedule']);
        if(!is_null($param['cancel_schedule'])){
            foreach ($cancel_schedule as $v){
                $data = array();
                $data['ls_senpai_id'] = $senpai_id;
                $temp_arr1 = explode('=>', $v);
                $data['ls_date'] = $temp_arr1[0];
                $temp_arr2 = explode('~', $temp_arr1[1]);
                $data['ls_start_time'] = $temp_arr2[0];
                $data['ls_end_time'] = $temp_arr2[1];
                $rs = LessonService::doCancelLessonSchedule($data);
                if(!$rs) $result = false;
            }
            if(!$result) return response()->json(['state' => $result]);
        }
        if(!is_null($param['new_schedule'])){
            foreach ($new_schedule as $v){
                $data = array();
                $data['ls_senpai_id'] = $senpai_id;
                $temp_arr1 = explode('=>', $v);
                $data['ls_date'] = $temp_arr1[0];
                $temp_arr2 = explode('~', $temp_arr1[1]);
                $data['ls_start_time'] = $temp_arr2[0];
                $data['ls_end_time'] = $temp_arr2[1];
                $rs = LessonService::doCreateLessonSchedule($data);
                if(!$rs) $result = false;
            }
            if(!$result) return response()->json(['state' => $result]);
        }
        return response()->json(['state' => $result]);
    }

    public function requestList(Request $request, $type = 0)
    {
        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));

        $params = $request->all();
        $page_from = isset($params['page_from']) ? $params['page_from'] : null;
        $result1 = self::getRequestDatas(config('const.request_type.reserve'), $page_from);
        $result2 = self::getRequestDatas(config('const.request_type.attend'), $page_from);
        $count1 = $result1['total_count'] > 0 ? CommonService::showFormatNum($result1['total_count']) : '';
        $count2 = $result2['total_count'] > 0 ? CommonService::showFormatNum($result2['total_count']) : '';

        $req_count = LessonService::getCountRequestBySenpaiId(Auth::user()->id);
        $req_count = $req_count > 0 ? CommonService::showFormatNum($req_count) : '';

        $var_params = [
            'page_id' => 'syutupinn',
            'page_id_02' => '',
            'req_count'=>$req_count,
            'req_list1'=>$result1['req_list'],
            'pages1'=>$result1['pages'],
            'count1'=>$count1,
            'req_list2'=>$result2['req_list'],
            'pages2'=>$result2['pages'],
            'count2'=>$count2,
            'type'=>$type
        ];

        if ($page_from) {
            $var_params['page_from'] = $page_from;
        }
        return view('user.syutupinn.request', $var_params);
    }

    public function getAjaxRequestList(Request $request, $type = 0)
    {
        $result = self::getRequestDatas($type);
        return response()->json(
            [
                'state' => "success",
                'req_list'=>$result['req_list'],
                'pages'=>$result['pages']
            ]
        );
    }

    public function getRequestDatas($type, $page_from=null)
    {
        $req_obj= LessonService::getRequestListBySenpaiId(Auth::user()->id, $type, $page_from);
        $total_count = $req_obj->get()->count();
        $tem_list = $req_obj->paginate($this->per_page);
        $pages = $tem_list->lastPage();
        $req_list = array();
        foreach ($tem_list as $k=>$v){
            $req_list[$v['lr_id']]['lr_id'] = $v['lr_id'];
            $req_list[$v['lr_id']]['lesson_discuss_area'] = $v->discuss_lesson_area;
            $req_list[$v['lr_id']]['kouhai_id'] = intval($v['user']['id']);
            $req_list[$v['lr_id']]['kouhai_name'] = trim($v['user']['name']);
            $req_list[$v['lr_id']]['kouhai_avatar'] = CommonService::getUserAvatarUrl(trim($v['user']['user_avatar']));
            $req_list[$v['lr_id']]['kouhai_old'] = CommonService::getAge($v['user']['user_birthday']);
            $req_list[$v['lr_id']]['kouhai_sex'] = intval($v['user']['user_sex']);
            $req_list[$v['lr_id']]['lesson_id'] = intval($v['lesson']['lesson_id']);
            $req_list[$v['lr_id']]['lesson_title'] = trim($v['lesson']['lesson_title']);
            $req_list[$v['lr_id']]['lesson_pos_discuss'] = intval($v['lr_pos_discuss']);
            $req_list[$v['lr_id']]['lesson_class_icon'] = CommonService::getLessonClassIconUrl(trim($v['lesson']['lesson_class']['class_icon']));
            $req_list[$v['lr_id']]['until_confirm'] = CommonService::getMD($v['lr_until_confirm']);
            $req_list[$v['lr_id']]['lesson'] = $v['lesson'];
            if($type == config('const.request_type.attend')){
                $persons = intval($v['lr_man_num']) + intval($v['lr_woman_num']);
                $cost = intval($v['lesson']['lesson_30min_fees']);
                $min_for_lesson_cost = SettingService::getSetting('min_for_lesson_cost','int');
                $min_price = intval($v['lr_hope_mintime']) / $min_for_lesson_cost * $cost * $persons;
                $max_price = intval($v['lr_hope_maxtime']) / $min_for_lesson_cost * $cost * $persons;
                $min_price = CommonService::showFormatNum($min_price);
                $max_price = CommonService::showFormatNum($max_price);
                $req_list[$v['lr_id']]['hope_time'] = intval($v['lr_hope_mintime']).'~'. intval($v['lr_hope_maxtime']).'分 /'.$min_price.'~'.$max_price.'円';
            }
            foreach ($v['lesson_request_schedule'] as $k1=>$v1){
                if(intval($v1['lrs_state']) == config('const.schedule_state.request')){
                    $req_list[$v['lr_id']]['req'][] = CommonService::getMD($v1['lrs_date']).'|'.CommonService::getStartAndEndTime($v1['lrs_start_time'], $v1['lrs_end_time']).'|'.CommonService::showFormatNum($v1['lrs_amount']);
                }
                if(intval($v1['lrs_state']) == config('const.schedule_state.confirm')){
                    $confirm_date = CommonService::getMD($v1['lrs_confirm_date']);
                    $req_list[$v['lr_id']]['confirm'][$confirm_date][] = $confirm_date.'|'.CommonService::getMD($v1['lrs_date']).'|'.CommonService::getStartAndEndTime($v1['lrs_start_time'], $v1['lrs_end_time']).'|'.CommonService::showFormatNum($v1['lrs_amount']);
                }
            }
        }
        return  array('req_list'=>$req_list, 'pages'=>$pages, 'total_count'=>$total_count);
    }

    public static function reserveCheck(Request $request, $lr_id=0)
    {
        if ( intval($lr_id) == 0 )
            return redirect()->route('user.syutupinn.request', ['type' => config('const.request_type.reserve')]);
        $req_info = LessonService::getRequestInfo($lr_id);
        if ($req_info == NULL)
            return  redirect()->route('user.syutupinn.request', ['type' => config('const.request_type.reserve')]);
        $req_info['evalution_count'] = EvalutionService::getLessonEvalutionCount($req_info['lr_lesson_id'], EvalutionService::SENPAIS_EVAL);
        $req_info['evalution'] = EvalutionService::getLessonEvalutionPercentByType($req_info['lr_lesson_id'], EvalutionService::SENPAIS_EVAL);
        $cancel_reason_types = CommonService::getCancelReasonTypes(config('const.cancel_kind.senpai'));
        return view('user.syutupinn.reserve_check',
            [
                'page_id' => 'syutupinn',
                'page_id_02' => '',
                'req_info' => $req_info,
                'cancel_reason_types' => $cancel_reason_types
            ]
        );
    }

    public static function attendCheck(Request $request, $lr_id)
    {
        if ( intval($lr_id) == 0 )
            return redirect()->route('user.syutupinn.request', ['type' => config('const.request_type.attend')]);
        $req_info = LessonService::getRequestInfo($lr_id);
        if ($req_info == NULL)
            return  redirect()->route('user.syutupinn.request', ['type' => config('const.request_type.attend')]);
        $cancel_reason_types = CommonService::getCancelReasonTypes(config('const.cancel_kind.senpai'));
        return view('user.syutupinn.attend_check',
            [
                'page_id' => 'syutupinn',
                'page_id_02' => '',
                'req_info' => $req_info,
                'cancel_reason_types' => $cancel_reason_types
            ]
        );
    }

    public static function reserveReqSave(Request $request)
    {
        $param = $request->post();
        $data = array();
        $condition = array();
        $result = true;
        $is_msg = false;
        $kouhai_id = intval($param['kouhai_id']);
        $until_confirm = $param['until_confirm'];
        $ls_title = $param['ls_title'];
        $schedules = array();
        $reasons = array();
        if(isset($param['approval_ids']) && intval($param['lr_id']) > 0){
            foreach ($param['approval_ids'] as $k=>$v){
                $condition['lrs_id'] = intval($v);
                $data['lrs_state'] = config('const.schedule_state.confirm');
                $data['lrs_confirm_date'] = date('Y-m-d h:i:s');
                $rs = LessonService::doUpdateRequestSchedule($data, $condition);
                if(!$rs){
                    $result = false;
                }else{
                    $is_msg = true;
                    $lrs_info = LessonRequestSchedule::where('lrs_id', $v)->first();
                    $schedules[$v] = CommonService::getMDAndWeek($lrs_info['lrs_date']).CommonService::getStartAndEndTime($lrs_info['lrs_start_time'], $lrs_info['lrs_end_time']);
                }
            }

            if(count($schedules) > 0) {
                TalkroomService::saveKouhaiRequestBuy($kouhai_id, Auth::user()->id, $ls_title, intval($param['lr_id']), $schedules, $until_confirm);
                TalkroomService::saveSenpaiRequestConfirmOrChange(Auth::user()->id, $kouhai_id, $ls_title, $schedules, $until_confirm, intval($param['lr_id']));
            }

            if($is_msg){
                MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "reserve_confirm");
            }
        }

        if(isset($param['cancel_ids']) && intval($param['lr_id']) > 0){

            foreach ($param['cancel_ids'] as $k=>$v){
                $condition['lrs_id'] = intval($v);
                $data['lrs_state'] = config('const.schedule_state.reject_senpai');
                if(isset($param['crt_ids']) && is_array($param['crt_ids'])){
                    if (isset($param['crt_ids']) && isset($param['crt_ids'][$condition['lrs_id']])) {
                        $data['lrs_cancel_reason'] = serialize($param['crt_ids'][$condition['lrs_id']]);
                        if(in_array(config('const.senpai_cancel_other_reason_id'), $param['crt_ids'][$condition['lrs_id']])) {
                            if (isset($param['cancel_note']) && isset($param['cancel_note'][$condition['lrs_id']]) && $param['cancel_note'][$condition['lrs_id']])
                            $data['lrs_cancel_note'] = trim($param['cancel_note'][$condition['lrs_id']]);
                        }
                    }
                    $data['lrs_confirm_date'] = date('Y-m-d h:i:s');
                    $rs = LessonService::doUpdateRequestSchedule($data, $condition);
                }else{
                    $rs = false;
                }
                if(!$rs){
                    $result = false;
                }else{
                    $is_msg = true;
                    $lrs_info = LessonRequestSchedule::where('lrs_id', $v)->first();
                    $start_date = CommonService::getYMD($lrs_info['lrs_date']) . ' ' .date('H:i') . '~';
                    $ls_img = $param['lesson_img'];

                    $reasons = [];
                    if ($param['crt_ids'] && isset($param['crt_ids'][$condition['lrs_id']]) && is_array($param['crt_ids'][$condition['lrs_id']])) {
                        $obj_reasons = CancelReasonType::whereIn('crt_id', $param['crt_ids'][$condition['lrs_id']])->get();
                        foreach ($obj_reasons as $obj_reason) {
                            if ( $obj_reason['crt_id'] == config('const.senpai_cancel_other_reason_id') ||
                                $obj_reason['crt_id'] == config('const.kouhai_cancel_other_reason_id') ) {
                                if (isset($param['cancel_note']) && isset($param['cancel_note'][$condition['lrs_id']]) && $param['cancel_note'][$condition['lrs_id']])
                                    $reasons[] = trim($param['cancel_note'][$condition['lrs_id']]);
                                // $reasons[] = $request['other_reason'];
                                continue;
                            }
                            $reasons[] = $obj_reason['crt_content'];
                        }
                    }

                    if(count($reasons) > 0){
                        TalkroomService::saveKouhaiRequestSenpaiCancel($kouhai_id, Auth::user()->id, $reasons, $ls_title, $start_date, $ls_img);
                        TalkroomService::saveSenpaiRequestCancel(Auth::user()->id, $kouhai_id, $reasons, $ls_title, $start_date, $ls_img );
                    }
                }
            }

            if($is_msg){
                MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "reserve_cancel");
            }
        }
        /*else{
            $result = false;
        }*/

        // lesson_requests => update lr_state
        $condition = [
            'lr_id' => intval($param['lr_id'])
        ];
        $data = [
            'lr_state' => config('const.req_state.response'),
            'lr_response_date' => date('Y-m-d h:i:s')
        ];
        LessonService::doUpdateRequest($data, $condition);

        if(!$result)
            return response()->json(['state' => false]);
        else
            return response()->json(['state' => true]);
    }

    public static function attendReqSave(Request $request)
    {
        $param = $request->post();
        $data = array();
        $condition = array();
        $result = false;
        $kouhai_id = intval($param['kouhai_id']);
        $until_confirm = $param['until_confirm'];
        $ls_title = $param['ls_title'];
        $schedules = array();
        $reasons = array();

        // update lesson request
        if(isset($param['is_allow']) && intval($param['is_allow']) == 1 && intval($param['lrs_id']) > 0){
            LessonService::updateAttendRequestState($param['lr_id'], config('const.req_state.response'), $param['reject_lrs_ids']);
        } else {
            LessonService::updateAttendRequestState($param['lr_id'], config('const.req_state.reject'), $param['reject_lrs_ids']);
        }

        $lrs_info = LessonRequestSchedule::where('lrs_id', intval($param['lrs_id']))->first();
        // 承認
        if(isset($param['is_allow']) && intval($param['is_allow']) == 1 && intval($param['lrs_id']) > 0){
            $condition['lrs_id'] = intval($param['lrs_id']);
            $data['lrs_state'] = config('const.schedule_state.confirm');
            $data['lrs_confirm_date'] = date('Y-m-d h:i:s');
            $result = LessonService::doUpdateRequestSchedule($data, $condition);
            if($result){
                $schedules[] = CommonService::getMDAndWeek($lrs_info['lrs_date']).CommonService::getStartAndEndTime($lrs_info['lrs_start_time'], $lrs_info['lrs_end_time']);
                TalkroomService::saveKouhaiRequestBuy($kouhai_id, Auth::user()->id, $ls_title, intval($param['lrs_id']), [$schedules[0]], $until_confirm);
                TalkroomService::saveSenpaiRequestConfirmOrChange(Auth::user()->id, $kouhai_id, $ls_title, $schedules, $until_confirm, intval($param['lr_id']));

                MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "attend_confirm");
            }

        }

        // 辞退
        if(isset($param['is_allow']) && intval($param['is_allow']) == 0 && intval($param['lr_id']) > 0){
            $obj_reasons = CancelReasonType::whereIn('crt_id', $param['crt_ids'])->get();
            foreach ($obj_reasons as $obj_reason) {
                if ( $obj_reason['crt_id'] == config('const.senpai_cancel_other_reason_id') ||
                    $obj_reason['crt_id'] == config('const.kouhai_cancel_other_reason_id') ) {
                    $reasons[] = $request['cancel_note'];
                    continue;
                }
                $reasons[] = $obj_reason['crt_content'];
            }

            $condition['lrs_lr_id'] = intval($param['lr_id']);
            $data['lrs_state'] = config('const.schedule_state.reject_senpai');
            if(isset($param['crt_ids']) && is_array($param['crt_ids'])){
                $data['lrs_cancel_reason'] = serialize($param['crt_ids']);
                if(in_array(config('const.senpai_cancel_other_reason_id'), $param['crt_ids']))
                    $data['lrs_cancel_note'] = trim($param['cancel_note']);
                $data['lrs_cancel_date'] = date('Y-m-d h:i:s');
                $result = LessonService::doUpdateRequestSchedule($data, $condition);

                if($result){
                    $start_date = CommonService::getYMD($lrs_info['lrs_date']) . ' ' .date('H:i') . '~';
                    $ls_img = $param['lesson_img'];
                    if(count($reasons) > 0){
                        TalkroomService::saveKouhaiRequestSenpaiCancel($kouhai_id, Auth::user()->id, $reasons, $ls_title, $start_date, $ls_img);
                        TalkroomService::saveSenpaiRequestCancel(Auth::user()->id, $kouhai_id, $reasons, $ls_title, $start_date, $ls_img );
                    }
                    MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $kouhai_id, "attend_cancel");
                }
            }
        }

        if(!$result)
            return response()->json(['state' => false]);
        else
            return response()->json(['state' => true]);
    }

    public static function manual(Request $request)
    {
        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));

        return view('user.syutupinn.manual', ['page_id' => 'syutupinn', 'page_id_02' => '']);
    }

    public static function onlineAddConfirm(Request $request)
    {
        if ( !Auth::user()->user_is_senpai )
            return redirect(route('user.myaccount.reg_senpai'));

        return view('user.syutupinn.online_add_confirm', ['page_id' => 'syutupinn', 'page_id_02' => '']);
    }

    public static function getAreaIdsByMapAddress($map_address = ''){
        return array(1, 2, 3, 66, 67);
    }

    public static function setDataForLessonSave($data)
    {
        $condition = array();
        $updateData = array();
        $condition['lesson_id'] = intval($data['lesson_id']);
        $updateData['lesson_senpai_id'] = Auth::User()->id;
        $updateData['lesson_type'] = intval($data['lesson_type']);
        $updateData['lesson_class_id'] = intval($data['lesson_class_id']);
        $updateData['lesson_title'] = trim($data['lesson_title']);
        if(isset($data['lesson_image'])) $updateData['lesson_image'] = serialize($data['lesson_image']);
        $updateData['lesson_wish_sex'] = intval($data['lesson_wish_sex']) ;
        $updateData['lesson_wish_minage'] = intval($data['lesson_wish_minage']);
        $updateData['lesson_wish_maxage'] = intval($data['lesson_wish_maxage']);
        $updateData['lesson_min_hours'] = intval($data['lesson_min_hours']);
        $updateData['lesson_30min_fees'] = intval($data['lesson_30min_fees']);
        $updateData['lesson_person_num'] = intval($data['lesson_person_num']);

        if(isset($data['lesson_able_with_man']))
            $updateData['lesson_able_with_man'] = intval($data['lesson_able_with_man']);
        else
            $updateData['lesson_able_with_man'] = 0;

        if(isset($data['lesson_accept_without_map']))
            $updateData['lesson_accept_without_map'] = intval($data['lesson_accept_without_map']);
        else
            $updateData['lesson_accept_without_map'] = 0;

        if( isset($data['lesson_latitude']) && trim($data['lesson_latitude']) != '') $updateData['lesson_latitude']=  floatval($data['lesson_latitude']);
        if( isset($data['lesson_longitude']) && trim($data['lesson_longitude']) != '') $updateData['lesson_longitude'] =  floatval($data['lesson_longitude']) ;
        /*if( isset($data['lesson_map_address']) && trim($data['lesson_map_address']) != '') $updateData['lesson_map_address'] =  trim($data['lesson_map_address']);*/

        if( isset($data['lesson_pos_detail']) && trim($data['lesson_pos_detail']) != '') $updateData['lesson_pos_detail'] = trim($data['lesson_pos_detail']);
        if( isset($data['lesson_discuss_pos_detail']) && trim($data['lesson_discuss_pos_detail']) != '') $updateData['lesson_discuss_pos_detail'] = trim($data['lesson_discuss_pos_detail']);

        if(isset($data['lesson_able_discuss_pos']))
            $updateData['lesson_able_discuss_pos'] = intval($data['lesson_able_discuss_pos']);
        else
            $updateData['lesson_able_discuss_pos'] = 0;

        if( isset($data['lesson_service_details']) && trim($data['lesson_service_details']) != '') $updateData['lesson_service_details'] = trim($data['lesson_service_details']);
        if( isset($data['lesson_other_details']) && trim($data['lesson_other_details']) != '') $updateData['lesson_other_details'] =  trim($data['lesson_other_details']);
        if( isset($data['lesson_buy_and_attentions']) && trim($data['lesson_buy_and_attentions']) != '') $updateData['lesson_buy_and_attentions']= trim($data['lesson_buy_and_attentions']);

        if(isset($data['lesson_accept_attend_request']))
            $updateData['lesson_accept_attend_request'] = intval($data['lesson_accept_attend_request']);
        else
            $updateData['lesson_accept_attend_request'] = 0;

        for ( $i = 1; $i <= config('const.lesson_cond_cnt') ; $i++) {
            $str_key = 'lesson_cond_'.$i;
            if(isset($data['lesson_cond_'.$i]))
                $updateData[$str_key] = intval($data['lesson_cond_'.$i]);
            else
                $updateData[$str_key] = 0;
        }
        $updateData['lesson_state'] = intval($data['lesson_state']);
        $updateData['lesson_coupon'] = intval($data['lesson_coupon']);
        return array('condition'=>$condition, 'data'=>$updateData);
    }

    public static function saveLesson(Request $request){
        $params = $request->post();
        $senpai_id = Auth::user()->id;
        $prefix = 'lesson_pic_s'.$senpai_id.'_d'.strtotime("now");
        /*$area_ids = array();
        if(isset($params['lesson_map_address'])) $area_ids = self::getAreaIdsByMapAddress($params['lesson_map_address']);*/

        // map address
        $area_info = isset($params['map_location']) ? json_decode($params['map_location']) : [];

        // map discuss address
        $area_discuss_info = isset($params['map_discuss_location']) ? json_decode($params['map_discuss_location']) : [];

        if(intval($params['lesson_id']) > 0){ //edit
            $filename_arr = explode(',', $params['old_filename']);
            for ($i=1; $i < 11; $i++){
                $k = $i - 1;
                if($params['temp_file_name'][$k] != 'old'){
                    if($request->file('lesson_pic_'.$i)){
                        if(isset($filename_arr[$k])){
                            $old_file_path = storage_path('app\public\lesson\\').$filename_arr[$k];
                            if(is_file($old_file_path) && file_exists($old_file_path))
                                unlink($old_file_path);
                        }

                        $uploaded_file = $request->file('lesson_pic_'.$i);
                        $new_filename = $prefix.'_'.$i.'.'.$uploaded_file->getClientOriginalExtension();
                        $rs = $uploaded_file->storeAs('public/lesson', $new_filename);
                        if($rs) $filename_arr[$k] = $new_filename;
                    }else{
                        $filename_arr[$k] = NULL;
                    }
                }
            }
            $params['lesson_image'] = $filename_arr;
            $updateData = self::setDataForLessonSave($params);
            if (LessonService::doUpdateLesson($updateData['condition'], $updateData['data']))
            {
                $lesson_id = intval($params['lesson_id']);
                LessonService::doDeleteLessonArea($lesson_id);
                if(count($area_info) > 0){
                    foreach ($area_info as $v){
                        LessonService::doCreateLessonArea($lesson_id, $v);
                    }
                }
                LessonService::doDeleteLessonDiscussArea($lesson_id);
                if(count($area_discuss_info) > 0){
                    foreach ($area_discuss_info as $v){
                        LessonService::doCreateLessonDiscussArea($lesson_id, $v);
                    }
                }
                return response()->json(["state" => "success"]);
            } else {
                return response()->json(["state" => "error"]);
            }
        }else{ //add
            for ($i=1; $i < 11; $i++){
                if($request->file('lesson_pic_'.$i)){
                    $uploaded_file = $request->file('lesson_pic_'.$i);
                    $new_filename = $prefix.'_'.$i.'.'.$uploaded_file->getClientOriginalExtension();
                    $rs = $uploaded_file->storeAs('public/lesson', $new_filename);
                    if($rs) $params['lesson_image'][] = $new_filename;
                }else{
                    $params['lesson_image'][] = NULL;
                }
            }
            $obj = LessonService::doCreateLesson($params);
            if ($obj)
            {
                $lesson_id = intval($obj['lesson_id']);
                if(count($area_info) > 0){
                    foreach ($area_info as $v){
                        LessonService::doCreateLessonArea($lesson_id, $v);
                    }
                }
                if(count($area_discuss_info) > 0){
                    foreach ($area_discuss_info as $v){
                        LessonService::doCreateLessonDiscussArea($lesson_id, $v);
                    }
                }
                LessonService::doUpdateLessonLoad($lesson_id, config('const.load_state.unable'));
                return response()->json(["state" => "success"]);
            } else {
                return response()->json(["state" => "error"]);
            }
        }
    }

    public static function delLesson(Request $request){
        $params = $request->post();
        if(LessonService::doDeleteLesson($params)){
            return response()->json(["state" => "success"]);
        }else{
            return response()->json(["state" => "error"]);
        }
    }
}
