<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CommonAlertRequest;
use App\Mail\Admin\SendAlertEmail;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Models\LessonRequestSchedule;
use App\Models\Proposal;
use App\Models\Recruit;
use App\Models\User;
use App\Service\AreaService;
use App\Service\CommonService;
use App\Service\EvalutionService;
use App\Service\KeijibannService;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\MessageService;
use App\Service\ProposalService;
use App\Service\SenpaiService;
use App\Service\TimeDisplayService;
use App\Service\UserService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class PatrolManagementController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $class_list = LessonClassService::getAllLessonClasses();
        return view('admin.patrol.index', [
            'class_list'=>$class_list,
        ]);
    }

    public function recruit(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.patrol.recruit', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }

        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [
            ];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = 0;
        }

        Session::put('admin.patrol.recruit', $condition);

        $recruits = KeijibannService::doSearchRecruitAdmin($condition)->paginate(20);
        $categories  = LessonClassService::getAllLessonClasses();
        //dd($condition);

        return view('admin.patrol.recruit', [
            'recruits' => $recruits,
            'categories' => $categories,
            'search_params' => $condition
        ]);
    }

    public function recruitDetail(Request $request, Recruit $recruit)
    {
        $cruitUser = $recruit['cruitUser'];
        //age
        $recruit['age'] = CommonService::getAge($cruitUser['user_birthday']);

        //sex
        $recruit["sex"] = CommonService::getSexStr($cruitUser['user_sex']);
        $recruit['date'] = CommonService::getMd($recruit['rc_date']);
        $recruit['start_end_time'] = CommonService::getStartAndEndTime($recruit['rc_start_time'], $recruit['rc_end_time']);
        $recruit['date_limit'] = CommonService::getMDH($recruit['rc_period']);

        $buy_count = LessonService::getBuyScheduleCntByKouhaiId($recruit->rc_user_id);
        $sell_count = LessonService::getSellScheduleCntBySenpaiId($recruit->rc_user_id);

        $proposals = ProposalService::getPropsFrRecruit($recruit['rc_id']);
        foreach($proposals as $key=>$val)
        {
            $proposals[$key]['age'] = CommonService::getAge($val['proposalUser']['user_birthday']);

            $proposals[$key]["sex"] = CommonService::getSexStr($val['proposalUser']['user_sex']);
            $proposals[$key]["date"] = TimeDisplayService::getDateFromDatetime($val['pro_buy_datetime']);
        }

        return view('admin.patrol.recruit_detail',
            [
                'title'=>'募集の詳細',
                'data'=>$recruit,
                'buy_count'=>$buy_count,
                'sell_count'=>$sell_count,
                'proposals' => $proposals,
                'total'=>count($proposals)
            ]);
    }

    public function recruitProposal(Request $request, Proposal $proposal)
    {
        $recruit = KeijibannService::getCruitDetails(config('const.recruit_state.all'), $proposal->pro_rc_id)->first();

        return view('admin.patrol.recruit_proposal',
            [
                'title'=>'提案内容の詳細',
                'prop_id' => $proposal->pro_id,
                'proposal'=>$proposal,
                'recruit' => $recruit
            ]);
    }

    public function stopRecruit(Request $request, Recruit $recruit)
    {
        $params = $request->all();
        $contents = "この投稿を<br>公開停止にしました。<br><div class='toukou_kanryou'><p><img class='modal-avatar' src='".CommonService::getUserAvatarUrl($recruit->cruitUser->user_avatar)."'></p><p>".$recruit->cruitUser->name."<small>さんを</small></p></div>"."ぴろしきまるに<br>しますか？";
        $modal_confrim_url = route('admin.fraud_piro.create', ['user'=>$recruit->cruitUser->id]);
        $condition = [];
        $condition['recruit_id'] = $recruit->rc_id;
        $modal_cancel_url = route('admin.patrol.recruit');
        if (!KeijibannService::stopRecruit($condition)) {
            $contents = "公開停止設定に失敗しました。";
            $modal_confrim_url = route('admin.patrol.recruit.detail', ['recruit'=>$recruit->rc_id]);
            $modal_cancel_url = "";
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url,
            'modal_cancel_url'=>$modal_cancel_url,
            'ok_label'=>'はい',
            'cancel_label'=>'いいえ',
        ]);
    }

    public function areaModal(Request $request)
    {
        $params = $request->all();
        $province_id = $params['province_id'];
        $data = AreaService::getNewLowerAreaList($province_id, 'area_pref', 3);
        $sel_area_ids = explode(',', $params['area_id_arr']);

        $user_id = 0;
        if (Auth::user()) {
            $user_id = Auth::user()->id;
        }

        foreach($data as $k => $v){
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

        return response()->json([
            'result_code' => 'success',
            'area_detail' => view('share._area_modal', [
                'data' => $data
            ])->render(),
        ]);
    }

    // リクエスト送信
    public function requestSend(Request $request)
    {
        Session::put('admin.patrol.request_send', $request->all());
        $condition = $request->input('search_params');
        $clear_condition = $request->input('clear_condition');

        if (isset($clear_condition) && $clear_condition == 1) {
            $condition = [];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if(!isset($condition['order'])) {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        $order = $request->input('order', '');
        if (isset($order) && $order) {
            $condition['order'] = $order;
        }

        $obj_lesson_requests = LessonService::getSendRequestInfo($condition)->paginate(100);

        return view('admin.patrol.request_send', [
            'obj_lesson_requests' => $obj_lesson_requests,
            'search_params' => $condition
        ]);
    }

    public function requestSendDetail(Request $request, LessonRequest $lessonRequest)
    {
        $index_params = Session::get('admin.patrol.request_send', []);

        $lesson_request_schedules = [];
        foreach ($lessonRequest->lesson_request_schedule as $lrs) {
            if ($lessonRequest->lr_state == config('const.req_state.request')) {
                $lesson_request_schedules[] = $lrs;
            } else if ($lessonRequest->lr_state == config('const.req_state.response')) {
                if ($lrs->lrs_state == config('const.schedule_state.confirm')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.reserve')) {
                if ($lrs->lrs_state < config('const.schedule_state.cancel_senpai') && $lrs->lrs_state > config('const.schedule_state.request')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.complete')) {
                if ($lrs->lrs_state == config('const.schedule_state.complete')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.cancel')) {
                $lesson_request_schedules[] = $lrs;
            }
        }

        return view('admin.patrol.request_send_detail', [
            'obj_lesson_request' => $lessonRequest,
            'index_params' => $index_params,
            'lesson_request_schedules' => $lesson_request_schedules,
        ]);
    }

    // リクエスト回答
    public function requestAnswer(Request $request)
    {
        Session::put('admin.patrol.request_answer', $request->all());
        $condition = $request->input('search_params');
        $clear_condition = $request->input('clear_condition');

        if (isset($clear_condition) && $clear_condition == 1) {
            $condition = [];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if(!isset($condition['order'])) {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        $order = $request->input('order', '');
        if (isset($order) && $order) {
            $condition['order'] = $order;
        }

        $obj_lesson_requests = LessonService::getAnswerRequestInfo($condition)->paginate(100);

        return view('admin.patrol.request_answer', [
            'obj_lesson_requests' => $obj_lesson_requests,
            'search_params' => $condition
        ]);
    }

    public function requestAnswerDetail(Request $request, LessonRequest $lessonRequest)
    {
        $index_params = Session::get('admin.patrol.request_answer', []);

        /*$lesson_request_schedules = [];
        foreach ($lessonRequest->lesson_request_schedule as $lrs) {
            if ($lessonRequest->lr_state == config('const.req_state.response')) {
                if ($lrs->lrs_state == config('const.schedule_state.confirm')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.reserve')) {
                if ($lrs->lrs_state < config('const.schedule_state.cancel_senpai') && $lrs->lrs_state > config('const.schedule_state.request')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.complete')) {
                if ($lrs->lrs_state == config('const.schedule_state.complete')) {
                    $lesson_request_schedules[] = $lrs;
                }
            } else if($lessonRequest->lr_state == config('const.req_state.cancel')) {
                $lesson_request_schedules[] = $lrs;
            }
        }*/

        $confirm_lesson_request_schedules = [];
        $cancel_lesson_request_schedules = [];

        foreach ($lessonRequest->lesson_request_schedule as $v) {
            if($v['lrs_state'] == config('const.schedule_state.cancel_senpai') || $v['lrs_state'] == config('const.schedule_state.cancel_kouhai') || $v['lrs_state'] == config('const.schedule_state.cancel_system') || $v['lrs_state'] == config('const.schedule_state.reject_senpai')) {
                $cancel_lesson_request_schedules[] = $v;
            } else {
                $confirm_lesson_request_schedules[] = $v;
            }
        }

        return view('admin.patrol.request_answer_detail', [
            'obj_lesson_request' => $lessonRequest,
            'index_params' => $index_params,
            'confirm_lesson_request_schedules' => $confirm_lesson_request_schedules,
            'cancel_lesson_request_schedules' => $cancel_lesson_request_schedules,
        ]);
    }

    public function createAlert(Request $request, $lessonRequest)
    {
        $page_type = $request->input('page_type', '');
        return view('admin.patrol.alert_create', [
            'lesson_request_id' => $lessonRequest,
            'page_type' => $page_type,
        ]);
    }

    public function requestDelete(CommonAlertRequest $request)
    {
        $params = $request->all();
        $page_type = $params['page_type'];
        $lesson_request_id = $params['lesson_request_id'];

        $admin_id = Auth::guard('admin')->user()->id;
        $contents = "送信が失敗しました。";

        $obj_lesson_request = LessonRequest::find($lesson_request_id);

        $senpai_id = $obj_lesson_request->lesson->senpai->id;
        $kouhai_id = $obj_lesson_request->user->id;
        $obj_senpai = User::find($senpai_id);
        $obj_kouhai = User::find($kouhai_id);

        if (LessonService::deleteLessonRequestBySystem($lesson_request_id, $params['alert_text'])) {
            MessageService::doCreateMsgFromAdmin(MessageService::MSG_CLASS_OWN_NEWS, $admin_id, $senpai_id, $params['alert_title']."\n".$params['alert_text']);
            MessageService::doCreateMsgFromAdmin(MessageService::MSG_CLASS_OWN_NEWS, $admin_id, $kouhai_id, $params['alert_title']."\n".$params['alert_text']);
            try {
                $mail_obj = Mail::to($obj_senpai->email);
                $mail_obj->send(new SendAlertEmail($params));
                $contents = "送信しました。";
            } catch (\Exception $exception) {
                Log::error("EMail Sending Failed：".$obj_senpai->email);
                Log::error($exception->getMessage());
            }

            try {
                $mail_obj = Mail::to($obj_kouhai->email);
                $mail_obj->send(new SendAlertEmail($params));
                $contents = "送信しました。";
            } catch (\Exception $exception) {
                Log::error("EMail Sending Failed：".$obj_kouhai->email);
                Log::error($exception->getMessage());
            }
        }

        $modal_confrim_url = route('admin.patrol.request_send');
        if ($page_type == "request_answer") {
            $modal_confrim_url = route('admin.patrol.request_answer');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }
}
