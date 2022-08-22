<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CommonAlertRequest;
use App\Mail\Admin\SendAlertEmail;
use App\Models\Lesson;
use App\Models\LessonRequestSchedule;
use App\Models\Recruit;
use App\Models\User;
use App\Service\CommonService;
use App\Service\EvalutionService;
use App\Service\KeijibannService;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\MessageService;
use App\Service\SenpaiService;
use App\Service\UserService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class LessonHistoryManagementController extends AdminController
{
    public function lesson(Request $request)
    {

        $params = $request->all();
        $condition = Session::get('admin.lesson_history_management.lesson', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [
                "senpai_user_id" => null,
                "senpai_nickname" => null,
                "kouhai_user_id" => null,
                "kouthai_nickname" => null
            ];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        }
        if (!isset($condition['order'])) {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        Session::put('admin.lesson_history_management.lesson', $condition);

        $obj_lesson_request_schedules = LessonService::doLessonHistorySearch($condition)->paginate(100);

        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.service_manage.lesson_history.lesson', [
            'obj_lesson_request_schedules' => $obj_lesson_request_schedules,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function recruit(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.lesson_history_management.recruit', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        Session::put('admin.lesson_history_management.recruit', $condition);

        $obj_recruits = KeijibannService::doRecruitHistorySearch($condition)->paginate($this->per_page);
        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.service_manage.lesson_history.recruit', [
            'obj_recruits' => $obj_recruits,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function lessonRequestScheduleDetail(Request $request, $lesson_request_schedule)
    {
        $lesson_request_schedule = LessonRequestSchedule::find($lesson_request_schedule);
        $lesson = $lesson_request_schedule->lesson_request->lesson;
        $senpai_id = $lesson_request_schedule->lrs_senpai_id;
        /*$data['schedule_count'] = LessonService::getScheduleCntByLessonId($lesson->lesson_id);
        $data['evalution_count'] = EvalutionService::getLessonEvalutionCount($lesson->lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['evalution'] = EvalutionService::getLessonEvalutionPercentByType($lesson->lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['senpai'] = SenpaiService::getSenpaiInfo($senpai_id);
        $data['lesson'] = LessonService::getLessonInfo($lesson->lesson_id);
        $data['lesson_conds'] = LessonService::getLessonCondNames($data['lesson']);
        $data['lesson_images'] = CommonService::unserializeData($data['lesson']['lesson_image']);*/

        return view('admin.service_manage.lesson_history.lesson_detail', [
            'lesson_request_schedule' => $lesson_request_schedule,
            'obj_lesson' => $lesson,
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

        $buy_count = LessonService::getBuyScheduleCntByKouhaiId($recruit['rc_user_id']);
        $sell_count = LessonService::getSellScheduleCntBySenpaiId($recruit['rc_user_id']);

        // 履歴
        $recruit_histories = KeijibannService::getRecruitActHistories($recruit->rc_id);

        return view('admin.service_manage.lesson_history.recruit_detail', [
            'recruit'=>$recruit,
            'buy_count' => $buy_count,
            'sell_count' => $sell_count,
            'recruit_histories' => $recruit_histories,
        ]);
    }

    public function createAlert(Request $request, $lesson_request_schedule)
    {
        return view('admin.service_manage.lesson_history.alert_create', [
            'lesson_request_schedule' => $lesson_request_schedule
        ]);
    }

    public function createRecruitAlert(Request $request, $recruit)
    {
        return view('admin.service_manage.lesson_history.alert_create', [
            'page_type' => 'recruit',
            'recruit' => $recruit
        ]);
    }

    public function doConfirmAlert(CommonAlertRequest $request)
    {
        $params = $request->all();
        $page_type = $params['page_type'];

        $admin_id = Auth::guard('admin')->user()->id;
        $contents = "送信が失敗しました。";

        if ($page_type == "recruit") { // 掲示板
            $obj_recruit = Recruit::find($params['recruit']);

            $senpai_id = null;
            $obj_senpai = null;
            if (isset($obj_recruit->proposed_senpai) && $obj_recruit->proposed_senpai) {
                $obj_senpai = $obj_recruit->proposed_senpai->proposalUser;
                $senpai_id = $obj_senpai->id;
            }
            $kouhai_id = $obj_recruit->rc_user_id;
            $obj_kouhai = User::find($kouhai_id);

            if (KeijibannService::breakRecruitBySystem($params['recruit'], $params['alert_text'])) {
                if ($senpai_id) {
                    MessageService::doCreateMsgFromAdmin(MessageService::MSG_CLASS_OWN_NEWS, $admin_id, $senpai_id, $params['alert_title']."\n".$params['alert_text']);

                    try {
                        $mail_obj = Mail::to($obj_senpai->email);
                        $mail_obj->send(new SendAlertEmail($params));
                        $contents = "送信しました。";
                    } catch (\Exception $exception) {
                        Log::error("EMail Sending Failed：".$obj_senpai->email);
                        Log::error($exception->getMessage());
                    }

                }

                MessageService::doCreateMsgFromAdmin(MessageService::MSG_CLASS_OWN_NEWS, $admin_id, $kouhai_id, $params['alert_title']."\n".$params['alert_text']);

                try {
                    $mail_obj = Mail::to($obj_kouhai->email);
                    $mail_obj->send(new SendAlertEmail($params));
                    $contents = "送信しました。";
                } catch (\Exception $exception) {
                    Log::error("EMail Sending Failed：".$obj_kouhai->email);
                    Log::error($exception->getMessage());
                }
            }

            $modal_confrim_url = route('admin.lesson_history_management.recruit');

        } else { // レッスン
            $obj_lesson_request_schedule = LessonRequestSchedule::find($params['lesson_request_schedule']);

            $senpai_id = $obj_lesson_request_schedule->lrs_senpai_id;
            $kouhai_id = $obj_lesson_request_schedule->lrs_kouhai_id;
            $obj_senpai = User::find($senpai_id);
            $obj_kouhai = User::find($kouhai_id);

            if (LessonService::cancelScheduleBySystem($params['lesson_request_schedule'], $params['alert_text'])) {
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

            $modal_confrim_url = route('admin.lesson_history_management.lesson');
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
