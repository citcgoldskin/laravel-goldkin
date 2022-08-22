<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LessonConditionRequest;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\LessonRequestSchedule;
use App\Models\LessonClass;
use App\Models\Senpai;
use App\Models\User;
use App\Service\CouponService;
use App\Service\CreditCardService;
use App\Service\LessonAccessHistoryService;
use App\Service\SquarePaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\User\LessonReserveRequest;
use App\Http\Requests\User\LessonAttendRequest;
use App\Http\Requests\User\CardCreditRequest;
use App\Service\CommonService;
use App\Service\CalendarService;
use App\Service\AreaService;
use App\Service\LessonService;
use App\Service\LessonClassService;
use App\Service\UserService;
use App\Service\SenpaiService;
use App\Service\FavouriteService;
use App\Service\EvalutionService;
use App\Service\MessageService;
use App\Service\BankService;
use App\Service\TalkroomService;

use Auth;
use Session;

const LESSON_SEARCH_PER_PAGE = 10;

class LessonController extends Controller
{
    public function search(Request $request, $class_id, $province_id = 0)
    {
        $class_obj = LessonClassService::getClass($class_id);
        $class_name = is_null($class_obj) ? '' : $class_obj->class_name;

        if($province_id == 0){
            $province_id = $request->getSession()->get('province_id');
            if(!$province_id){
                if(Auth::User()){
                    $province_id = Auth::User()->user_area_id;
                }else{
                    $province_id = $request->cookie('area_id');
                }
            }
        }
        if($province_id > 0){
            if($province_id != $request->getSession()->get('province_id')){
                $request->session()->forget('area_id_arr');
                $request->session()->forget('area_name_arr');
            }
            $request->session()->put('province_id', $province_id);
            $province_name = AreaService::getOneAreaFullName($province_id);
        }else{
            $province_name = 'すべて';
        }

        if($class_id != $request->getSession()->get('class_id')){
            $request->session()->put('class_id', $class_id);
            $search_params['order_type'] = 1;
            $search_params['is_attend'] = 1;
            $request->session()->put('is_attend', 1);
            $request->session()->put('order_type', 1);
        }else{
            $search_params = $request->getSession()->get('search_params');
            $search_params['is_attend'] = $request->getSession()->get('is_attend');
            $search_params['order_type'] = $request->getSession()->get('order_type');
            $search_params['area_id_arr'] = $request->getSession()->get('area_id_arr');
        }
        $search_params['class_id'] = $class_id;
        if($province_id > 0)
            $search_params['province_id'] = $province_id;

        // 出品者本人のサービスが表示されていないので表示をしてほしい。但し、自分ではそのレッスンには予約リクエスト・出勤リクエストできないようにする。
        /*if (Auth::user()) {
            $search_params['except_user_id'] = Auth::user()->id;
        }*/

        $lessons = LessonService::doLessonSearch($search_params);
        $lesson_count = count($lessons->get());
        $lesson_pages = $lessons->paginate(LESSON_SEARCH_PER_PAGE);
        return view('user.lesson.search',
            [   'page_id' => 'search',
                'page_id_02' => '',
                'class_name' => $class_name,
                'province_id' => $province_id,
                'province_name' => $province_name,
                'class_id' => $class_id,
                'lesson_pages' => $lesson_pages,
                'lesson_count' => $lesson_count,
                'is_attend' => $search_params['is_attend'],
                'order_type' => $search_params['order_type']
            ]);
    }

    public function setMainSearch(LessonConditionRequest $request)
    {
        $params = $request->all();
        $request->session()->put('search_params', $params);
        $class_id = $request->getSession()->get('class_id');
        return redirect()->route('user.lesson.search', ['class_id' => $class_id]);
    }

    public function setSearchOrder(Request $request)
    {
        $params = $request->all();
        $request->session()->put('is_attend', $params['is_attend']);
        $request->session()->put('order_type', $params['order_type']);
        $class_id = $request->getSession()->get('class_id');
        return redirect()->route('user.lesson.search', ['class_id' => $class_id]);
    }

    public function setArea(Request $request)
    {
        $params = $request->all();
        $area_id_arr = array();
        $area_name_arr = '';
        $key = 0;
        /*for($i = 0; $i < $params['area_count']; $i++){
            if(isset($params['area_'.$i.'_0']) && $params['area_'.$i.'_0'] == 1){
                for($j = 1; $j <= $params['area_'.$i.'_count']; $j++){
                    $area_id_arr[$key++] = intval($params['area_'.$i.'_'.$j.'_id']);
                }
                $area_name_arr = $area_name_arr.$params['area_'.$i.'_name'].', ';
            }else{
                if (isset($params['area_'.$i.'_count'])) {
                    for($j = 1; $j <= $params['area_'.$i.'_count']; $j++){
                        if(isset($params['area_'.$i.'_'.$j]) && $params['area_'.$i.'_'.$j] == 1){
                            $area_id_arr[$key++] = intval($params['area_'.$i.'_'.$j.'_id']);
                            $area_name_arr = $area_name_arr.$params['area_'.$i.'_'.$j.'_name'].', ';
                        }

                    }
                }
            }
        }*/

        for($i = 1; $i <= $params['area_count']; $i++){
            if(isset($params['area_'.$i]) && $params['area_'.$i] == 1){
                $area_id_arr[$key++] = intval($params['area_'.$i.'_id']);
                $area_name_arr = $area_name_arr.$params['area_'.$i.'_name'].', ';
            }
        }
        if(!empty($area_name_arr)){
            $area_name_arr = substr($area_name_arr, 0, -2);
        }
        $request->session()->forget('area_id_arr');
        $request->session()->put('area_id_arr', $area_id_arr);
        $request->session()->put('area_name_arr', $area_name_arr);

        $lesson_count = $request->getSession()->get('lesson_count');
        return redirect()->route('user.lesson.search_condition', ['lesson_count' => $lesson_count]);
    }

    public function searchCondition(Request $request, $count, $province_id = 0)
    {
        $request->session()->put('lesson_count', $count);
        $search_params = $request->getSession()->get('search_params');
        if($province_id == 0){
            $province_id = $request->getSession()->get('province_id');
        }else if($province_id != $request->getSession()->get('province_id')){
            $request->session()->forget('area_name_arr');
            $request->session()->put('province_id', $province_id);
        }
        $search_params['province_id'] = $province_id;

        $province_name = AreaService::getOneAreaFullName($province_id);

        $area_name = '';
        $area_name = $request->getSession()->get('area_name_arr');
        /*if (!$area_name && $request->getSession()->get('search_province_id')) {
            $area_name = AreaService::getOneAreaFullName($request->getSession()->get('search_province_id'));
        }*/

        return view('user.lesson.search_condition',
            [   'page_id' => 'search_condition',
                'page_id_02' => '',
                'lesson_count' => $count,
                'province_id' => $province_id,
                'province_name' => $province_name,
                'search_params' => $search_params,
                'area_name' => $area_name
            ]);
    }

    public function area(Request $request, $province_id)
    {
        // $area_count_arr = AreaService::getLessonCountListByArea(4);
        // $data = AreaService::getLowerAreaList($province_id);
        $data = AreaService::getNewLowerAreaList($province_id, 'area_pref', 3);
        $sel_area_ids = $request->getSession()->get('area_id_arr');

        /*foreach($data as $key => $value){
            $all_selected = true;
            $data[$key]['selected'] = 0;
            if (in_array($value['area_id'], $sel_area_ids)) {
                $data[$key]['selected'] = 1;
            }
            $data[$key]['area'] = AreaService::getNewLowerAreaList($value['area_id'], 'area_pref', 3);
            foreach ($data[$key]['area'] as $k => $v){
                foreach($area_count_arr as $_k => $_v){
                    if($v['area_id'] == $_v){
                        $data[$key]['area'][$k]['lesson_count'] = $_v['public_lesson_count'];
                    }
                }
                $data[$key]['area'][$k]['selected'] = 0;
                if(!empty($sel_area_ids)){
                    foreach ($sel_area_ids as $_k => $_v){
                        if($v['area_id'] == $_v){
                            $data[$key]['area'][$k]['selected'] = 1;
                        }
                    }
                }
            }
            foreach ($data[$key]['area'] as $k => $v){
                if($v['selected'] == 0){
                    $all_selected = false;
                }
            }
            if($all_selected){
                $data[$key]['selected'] = 1;
            }
        }*/

        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        foreach($data as $k => $v){
            /*foreach($area_count_arr as $_k => $_v){
                if($v['area_id'] == $_v){
                    $data[$k]['lesson_count'] = $_v['public_lesson_count'];
                }
            }*/
            $data[$k]['lesson_count'] = AreaService::getLessonCountByArea($v['area_id'], $user_id);
            $data[$k]['selected'] = 0;
            if(!empty($sel_area_ids)){
                foreach ($sel_area_ids as $_k => $_v){
                    if($v['area_id'] == $_v){
                        $data[$k]['selected'] = 1;
                    }
                }
            }

        }
        return view('user.lesson.area', [
            'page_id' => 'area',
            'page_id_02' => '',
            'data' => $data
        ]);
    }

    public function province(Request $request, $prev_url_id, $lesson_count = 0)
    {
        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }
        $arr_lesson_cnt = AreaService::getLessonCountListByArea(config('const.area_deep_code.pref'), $user_id);
        $region_prefectures = AreaService::getRegionAndPrefectures();
        $class_id = $request->getSession()->get('class_id');

        return view('user.lesson.province',
            [   'page_id' => 'province',
                'page_id_02' => '',
                'class_id' => $class_id,
                'prev_url_id' => $prev_url_id,
                'arr_lesson_cnt' => $arr_lesson_cnt,
                'region_prefectures' => $region_prefectures
            ]);
    }

    public function lessonView(Request $request, $lesson_id){
        LessonService::increaseLessonClick($lesson_id);
        if (Auth::guard('web')->check()) {
            LessonAccessHistoryService::create([
                'user_id'=>Auth::user()->id,
                'token' => $request->session()->get('_token'),
                'lesson_id'=>$lesson_id
            ]);
        }
        return redirect()->route('user.lesson.detail', ['lesson_id' => $lesson_id]);
    }

    public function detail(Request $request, $lesson_id)
    {

        $senpai_id = LessonService::getSenpaiIdFromLesson($lesson_id);
        $data['schedule_count'] = LessonService::getScheduleCntByLessonId($lesson_id);
        $data['evalution_count'] = EvalutionService::getLessonEvalutionCount($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['evalution'] = EvalutionService::getLessonEvalutionPercentByType($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['senpai'] = SenpaiService::getSenpaiInfo($senpai_id);
        $data['lesson'] = LessonService::getLessonInfo($lesson_id);
        $data['lesson_conds'] = LessonService::getLessonCondNames($data['lesson']);
        $data['lesson_images'] = CommonService::unserializeData($data['lesson']['lesson_image']);

        $class_id = $request->getSession()->get('class_id');
        $recommend_lessons = LessonService::getRecommendLessons($class_id, $lesson_id);

        $week_start = date('Y-m-d', strtotime('-1 weeks  Sunday'));

        $data['valid'] = 0;
        $valid_code = LessonService::getLessonValidCode($lesson_id);
        if($valid_code == 'self_lesson'){
            $data['valid'] = 1;
        }

        $end_week_day = Carbon::parse($week_start)->addWeeks(8);
        return view('user.lesson.detail',
            [   'page_id' => 'detail',
                'page_id_02' => '',
                'lesson_id' => $lesson_id,
                'data' => $data,
                'recommend_lessons' => $recommend_lessons,
                'week_start' => $week_start,
                'end_week_day' => $end_week_day
            ]);
    }

    public function getWeekSchedule(Request $request)
    {
        $params = $request->only('lesson_id', 'date');
        // $result['schedule'] = json_encode(LessonService::getWeekScheduleState($params['lesson_id'], $params['date']), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $result['schedule'] = LessonService::getWeekScheduleState($params['lesson_id'], $params['date']);
        $result['cur_date'] = $params['date'];
        $result['prev_date'] =date('Y-m-d', strtotime($params['date'].' -7 days'));
        $result['next_date'] =date('Y-m-d', strtotime($params['date'].' +7 days'));
        $time = CalendarService::getCalendarInfos(date('Y-m', strtotime($params['date'])));
        $result['ym'] = $time['current_label'];
        return response()->json(
            [
                "result_code" => "success",
                "week_info" => $result
            ]);
    }

    public function selfCheck(Request $request)
    {
        return view('user.lesson.self_check', ['page_id' => 'self_check', 'page_id_02' => '']);
    }

    public function settingReserveRequest(Request $request, $lesson_id, $lr_id = 0)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }
        $params = $request->all();
        // from 出勤スケジュール
        if (isset($params['lesson_id']) && $params['lesson_id']) {
            $lesson_id = $params['lesson_id'];
        }

        // validate block
        $valid_code = LessonService::getLessonValidCode($lesson_id);
        if($valid_code == 'no_self_conf' || $valid_code == 'self_block' || $valid_code == 'other_block'){
            return view('user.lesson.self_check',
                [   'page_id' => 'self_check',
                    'page_id_02' => '',
                    'lesson_id' => $lesson_id,
                    'valid_code' => $valid_code
                ]);
        }

        // validate 本人確認必須
        /*$valid_code = LessonService::getLessonValidCode($lesson_id);
        if($valid_code == 'self_block' || $valid_code == 'other_block'){
            return view('user.lesson.self_check',
                [   'page_id' => 'self_check',
                    'page_id_02' => '',
                    'lesson_id' => $lesson_id,
                    'valid_code' => $valid_code
                ]);
        }*/

        $lesson = LessonService::getLessonInfo($lesson_id);

        $schedule = LessonService::getLessonSchedules($lesson_id);
        $time = CalendarService::getCalendarInfos(date('Y-m'));
        $time['day_count'] = count($time['month']);
        $time['info'] = CommonService::_getScheduleForCalender($schedule, $time['cur_year'], $time['cur_month'], $time['day_count']);

        // from 出勤スケジュール
        if (isset($params['cur_ym']) && $params['cur_ym']) {
            $time['current'] = $params['cur_ym'];
        }

        //request
        $request_info = LessonService::getLessonRequestInfo($lr_id);
        return view('user.lesson.setting_reserve_request',
            [   'page_id' => 'setting_reserve_request',
                'page_id_02' => '',
                'lesson' => $lesson,
                'time' => $time,
                'request_info' => $request_info
            ]);
    }

    public function ajaxGetSchedule(Request $request)
    {
        $params = $request->only('lesson_id', 'ym');
        $schedule = LessonService::getLessonSchedules($params['lesson_id']);

        $time = CalendarService::getCalendarInfos($params['ym']);
        $time['day_count'] = count($time['month']);
        $time['info'] = CommonService::_getScheduleForCalender($schedule, $time['cur_year'], $time['cur_month'], $time['day_count']);
        return response()->json(
            [
                "result_code" => "success",
                "time" => $time
            ]);
    }

    public function addReserveRequest(LessonReserveRequest $request)
    {
        $params = $request->all();
        $schedule_count = $params['reserve_count'];
        if($schedule_count < 0)
            return false;
        $params['user_id'] = Auth::user()->id;
        $lesson_request_obj = LessonService::doCreateRequest($params);
        $req_id = $lesson_request_obj->lr_id;

        for($i = 0; $i < $schedule_count; $i++){
            $schedule[$i] = CommonService::getScheduleForDB($params['reserve_ok_'.$i]);
            $schedule[$i]['30min_fees'] = intval($params['30min_fees']) * intval($params['man_num'] + $params['woman_num']);
            $schedule[$i]['lr_id'] = $lesson_request_obj->lr_id;
            $schedule[$i]['lrs_kouhai_id'] = $params['user_id'];
            $schedule[$i]['lrs_senpai_id'] = $lesson_request_obj->lesson->senpai->id;
            $schedule_obj[$i] = LessonService::doCreateRequestSchedule($schedule[$i]);

            $reserve_time[$i] = CommonService::getReserveTimeForHtml($params['reserve_ok_'.$i]);

            //for Talkroom message
            $data = explode("年", $params['reserve_ok_'.$i]);
            $schedule_for_talkroom[$i] = $data[1];
        }

        //insert message
        $senpai_id = LessonService::getSenpaiIdFromLesson($params['lesson_id']);
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "reserve_arrive");

        //Talkroom message
        $reg_info = LessonService::getRequestInfo($req_id);
        $until_confirm = CommonService::getMDAndWeek($params['until_confirm']);
        TalkroomService::saveKouhaiRquestConfirmOrChange($params['user_id'], $senpai_id, $reg_info['lesson']['lesson_title'], $schedule_for_talkroom, $until_confirm, $req_id);
        TalkroomService::saveSenpaiRequestResponse($senpai_id, $params['user_id'], $reg_info['lesson']['lesson_title'], $schedule_for_talkroom, $until_confirm, $req_id);

        return view('user.lesson.reserve_request_comp', ['page_id' => 'reserve_request_comp', 'page_id_02' => '', 'reserve_time' => $reserve_time]);
    }

    public function settingAttendRequest(Request $request, $lesson_id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }
        $valid_code = LessonService::getLessonValidCode($lesson_id);
        if($valid_code == 'no_self_conf' || $valid_code == 'self_block' || $valid_code == 'other_block'){
            return view('user.lesson.self_check',
                [   'page_id' => 'self_check',
                    'page_id_02' => '',
                    'lesson_id' => $lesson_id,
                    'valid_code' => $valid_code
                ]);
        }
        $lesson = LessonService::getLessonInfo($lesson_id);
        return view('user.lesson.setting_attend_request', ['page_id' => 'setting_attend_request', 'page_id_02' => '', 'lesson' => $lesson]);
    }

    public function addAttendRequest(LessonAttendRequest $request)
    {
        $params = $request->all();
        $params['user_id'] = Auth::user()->id;
        $lesson_attend_obj = LessonService::doCreateRequest($params);
        $req_id = $lesson_attend_obj->lr_id;
        $data['hope_mintime'] = $params['hope_mintime'];
        $data['hope_maxtime'] = $params['hope_maxtime'];
        foreach ($params['date'] as $key => $value){
            $from_time = $params['from_hour'][$key].':'.$params['from_minute'][$key].':00';
            $to_time = $params['to_hour'][$key].':'.$params['to_minute'][$key].':00';

            $schedule[$key]['date'] = $value;
            $schedule[$key]['start_time'] = CommonService::getHM($from_time);
            $schedule[$key]['end_time'] = CommonService::getHM($to_time);
            $schedule[$key]['30min_fees'] = intval($params['30min_fees']) * intval($params['man_num'] + $params['woman_num']);
            $schedule[$key]['lr_id'] = $lesson_attend_obj->lr_id;
            $schedule[$key]['lrs_kouhai_id'] = $params['user_id'];
            $schedule[$key]['lrs_senpai_id'] = $lesson_attend_obj->lesson->senpai->id;

            $schedule_obj[$key] = LessonService::doCreateRequestSchedule($schedule[$key]);

            $data['attend_time'][$key]['date'] = $value;
            $data['attend_time'][$key]['time'] = CommonService::getStartAndEndTime($from_time, $to_time, '~');

            //for Talkroom message
            $data_for_talkroom = CommonService::getMDAndWeek($value);
            $time_for_talkroom = CommonService::getStartAndEndTime($from_time, $to_time, '~');
            $schedule_for_talkroom[$key] = $data_for_talkroom.$time_for_talkroom;
        }

        //insert message
        $senpai_id = LessonService::getSenpaiIdFromLesson($params['lesson_id']);
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "attend_arrive");

        //Talkroom message
        $reg_info = LessonService::getRequestInfo($req_id);
        $until_confirm = CommonService::getMDAndWeek($params['until_confirm']);
        TalkroomService::saveKouhaiRquestConfirmOrChange($params['user_id'], $senpai_id, $reg_info['lesson']['lesson_title'], $schedule_for_talkroom, $until_confirm, $req_id);
        TalkroomService::saveSenpaiRequestResponse($senpai_id, $params['user_id'], $reg_info['lesson']['lesson_title'], $schedule_for_talkroom, $until_confirm, $req_id);


        return view('user.lesson.attend_request_comp', ['page_id' => 'attend_request_comp', 'page_id_02' => '', 'data' => $data]);
    }

    /*public function checkReserve(Request $request, $lrs_id)*/
    public function checkReserve(Request $request, $lr_id)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }

        /*$schedule_info = LessonService::getScheduleInfoById($lrs_id);
        $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
        $user_id = Auth::user()->id;
        $is_coupon = $schedule_info['lesson_request']['lesson']['lesson_coupon'];
        $choose_result = CouponService::chooseCoupon($senpai_id, $user_id, $schedule_info['lrs_amount']);
        if($is_coupon > 0){
            if($choose_result['code'] == 'new'){
                $coupon_info['state'] = 'new';
                $coupon_info['coupon'] = $choose_result['obj'];
                $coupon_info['valid_cnt'] = $choose_result['valid_cnt'];
                $coupon_info['cup_id'] = $choose_result['obj']['cup_id'];
                $coupon_info['cpu_id'] = 0;
            }else if($choose_result['code'] == 'exist'){
                $coupon_info['state'] = 'exist';
                $coupon_info['coupon'] = $choose_result['obj']['coupon'];
                $coupon_info['valid_cnt'] = $choose_result['valid_cnt'];
                $coupon_info['cup_id'] = 0;
                $coupon_info['cpu_id'] = $choose_result['obj']['cpu_id'];
            }else{
                $coupon_info['state'] = 'none';
                $coupon_info['cup_id'] = 0;
                $coupon_info['cpu_id'] = 0;
            }
        }else{
            $coupon_info['state'] = 'none';
            $coupon_info['cup_id'] = 0;
            $coupon_info['cpu_id'] = 0;
        }

        $cancel_before_1_percent = CommonService::getCancelPercent(1);
        $cancel_before_0_percent = CommonService::getCancelPercent(0);

        $default_card = CreditCardService::getDefaultCard($user_id);

        return view('user.lesson.check_reserve',
            [
                'page_id' => 'check_reserve',
                'page_id_02' => '',
                'schedule_info' => $schedule_info,
                'coupon_info' => $coupon_info,
                'cancel_before_1_percent' => $cancel_before_1_percent,
                'cancel_before_0_percent' => $cancel_before_0_percent,
                'default_card' => $default_card
            ]);*/

        $schedules_info = LessonService::getSchedulesByLessonRequestId($lr_id);

        $user_id = Auth::user()->id;

        $obj_lesson_request = $schedules_info[0]['lesson_request'];
        $obj_lesson = $obj_lesson_request['lesson'];

        $cancel_before_1_percent = CommonService::getCancelPercent(1);
        $cancel_before_0_percent = CommonService::getCancelPercent(0);

        $default_card = CreditCardService::getDefaultCard($user_id);

        return view('user.lesson.check_reserve',
            [
                'page_id' => 'check_reserve',
                'page_id_02' => '',
                'lr_id' => $lr_id,
                'schedules_info' => $schedules_info,
                'obj_lesson' => $obj_lesson,
                'obj_lesson_request' => $obj_lesson_request,
                'cancel_before_1_percent' => $cancel_before_1_percent,
                'cancel_before_0_percent' => $cancel_before_0_percent,
                'default_card' => $default_card,
                'user_id' => $user_id
            ]);
    }

    // public function checkReserveComp(Request $request, $lrs_id, $cup_id, $cpu_id)
    public function checkReserveComp(Request $request, $lr_id, $cup_id, $cpu_id)
    {
        // --------- payment for one schedule ---------

        /*$obj_user = Auth::user();
        $user_id = $obj_user->id;
        $result = LessonService::updateScheduleState($lrs_id,  config('const.schedule_state.reserve'));
        //coupon
        if($cup_id > 0){
            CouponService::setCouponNew($cup_id, $user_id, $lrs_id);
        }
        if($cpu_id > 0){
            CouponService::setCouponUpdate($cpu_id, $lrs_id, config('const.coupon_state.used'));
        }
        //insert message
        $schedule_info = LessonService::getScheduleInfoById($lrs_id);
        $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "lesson_buy");
        //talkroom message
        TalkroomService::saveSenpaiBtnCommon($senpai_id, $user_id, TalkroomService::Senpai_Btn_LessonBuy, $lrs_id);
        TalkroomService::saveKouhaiBtnCommon($user_id, $senpai_id, TalkroomService::Kouhai_Btn_LessonBuy, $lrs_id);

        //request confirm message
        $obj_senpai = User::find($senpai_id);
        TalkroomService::saveKouhaiSysConfirm($user_id, $senpai_id, $obj_senpai->name, $lrs_id);
        TalkroomService::saveSenpaiSysRequestConfirm($senpai_id, $user_id, $obj_user->name, $lrs_id);

        return view('user.keijibann.recruit_book_comp', ['page_id' => 'keijibann', 'page_id_02' => '', 'title'=>'', 'sub_title'=>'']);*/

        // --------- payment for all schedules ---------

        $obj_user = Auth::user();
        $user_id = $obj_user->id;
        if(LessonService::updateRequestAndScheduleState($lr_id,  config('const.schedule_state.reserve'))) {
            //coupon
            if($cup_id > 0){
                // CouponService::setCouponNew($cup_id, $user_id, $lrs_id);
            }
            if($cpu_id > 0){
                // CouponService::setCouponUpdate($cpu_id, $lrs_id, config('const.coupon_state.used'));
            }

            // payment code
        }

        //insert message
        $obj_first_lrs = LessonService::getFirstRequestSchedule($lr_id, config('const.schedule_state.reserve'));
        $lesson_request = LessonRequest::find($lr_id);
        $senpai_id = $lesson_request->lesson->lesson_senpai_id;
        MessageService::doCreateMsg(MessageService::MSG_CLASS_SALE, $senpai_id, "lesson_buy");

        //talkroom message
        TalkroomService::saveSenpaiBtnCommon($senpai_id, $user_id, TalkroomService::Senpai_Btn_LessonBuy, $obj_first_lrs->lrs_id);
        TalkroomService::saveKouhaiBtnCommon($user_id, $senpai_id, TalkroomService::Kouhai_Btn_LessonBuy, $obj_first_lrs->lrs_id);

        //request confirm message
        $obj_senpai = User::find($senpai_id);
        TalkroomService::saveKouhaiSysConfirm($user_id, $senpai_id, $obj_senpai->name, $obj_first_lrs->lrs_id);
        TalkroomService::saveSenpaiSysRequestConfirm($senpai_id, $user_id, $obj_user->name, $obj_first_lrs->lrs_id);

        return view('user.keijibann.recruit_book_comp', ['page_id' => 'keijibann', 'page_id_02' => '', 'title'=>'', 'sub_title'=>'']);
    }

    public function selectPayMethod(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('welcome.back');
        }
        $user_id = Auth::user()->id;
        $cards = CreditCardService::getCreditCards($user_id);
        $default_card = CreditCardService::getDefaultCard($user_id);
        return view('user.lesson.select_pay_method', [
            'page_id' => 'select_pay_method',
            'page_id_02' => '',
            'cards' => $cards,
            'default_card'=>$default_card
        ]);
    }

    public function setPayMethod(Request $request)
    {

    }

    public function creditCard(Request $request)
    {
        $companyList = BankService::getCompanyList();
        return view('user.lesson.credit_card', ['page_id' => 'credit_card', 'page_id_02' => '', 'company' => $companyList]);
    }

    public function addCreditCard(Request $request)
    {
        $nonce = $request->input('nonce');
        $card_holder = $request->input('card_holder');

        $square_payment = new SquarePaymentService();
        $result = $square_payment->addCreditCard(Auth::user(), $nonce, $card_holder);

        if($result) {

        } else {

        }
    }

    public function change(Request $request)
    {
        $request_list = LessonService::getRequestOrConfirmSchedulesByUserId(Auth::user()->id);
        return view('user.lesson.change', ['page_id' => 'talkroom', 'page_id_02' => '', 'title'=>'リクエスト中のレッスン', 'sub_title'=>'', 'request_list'=>$request_list]);
    }
}
