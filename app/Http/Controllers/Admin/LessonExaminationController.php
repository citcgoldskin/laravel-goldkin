<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CommonAlertRequest;
use App\Http\Requests\Admin\LessonDisagreeRequest;
use App\Mail\Admin\SendAlertEmail;
use App\Models\Lesson;
use App\Models\User;
use App\Service\CommonService;
use App\Service\EvalutionService;
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

class LessonExaminationController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.lesson_examination', []);
        $condition['lesson_state'] = config('const.lesson_state.check');

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }

        if (isset($params['order']) && $params['order']) {
            $condition['sort_type'] = $params['order'];
        } else {
            $condition['sort_type'] = 0;
        }

        if (!isset($condition['onof-line'])) {
            $condition['onof-line'] = config('const.lesson_service_browser.new');
        }

        Session::put('admin.fraud.lesson_examination', $condition);

        $lesson_classes = LessonClassService::getLessonClasses();

        $obj_lessons = LessonService::doLessonExminationSearch($condition)->paginate($this->per_page);

        return view('admin.service_manage.lesson_examination.index', [
            'lesson_classes' => $lesson_classes,
            'obj_lessons' => $obj_lessons,
            'search_params' => $condition,
        ]);
    }

    public function detail(Request $request, $lesson_id)
    {
        $page_type = $request->input('page_type', '');
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
        return view('admin.service_manage.lesson_examination.detail',
            [   'page_id' => 'detail',
                'page_id_02' => '',
                'page_type' => $page_type,
                'lesson_id' => $lesson_id,
                'data' => $data,
                'recommend_lessons' => $recommend_lessons,
                'week_start' => $week_start,
                'end_week_day' => $end_week_day
            ]);
    }

    public function agree(Request $request)
    {
        $lesson_id = $request->input('lesson_id');
        $lesson_content_title = $request->input('lesson_content_title');
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.service_manage.lesson_examination.agree');
        }

        $condition = Session::get('admin.service_manage.lesson_examination.agree', []);
        $condition['agree_type'] = config('const.agree_flag.agree');
        if (isset($lesson_id) && $lesson_id) {
            $condition['lesson_id'] = $lesson_id;
        }
        if (isset($lesson_content_title) && $lesson_content_title) {
            $condition['lesson_content_title'] = $lesson_content_title;
        }
        Session::put('admin.service_manage.lesson_examination.agree', $condition);

        return redirect()->route('admin.lesson_examination.alert_create');
        /*$params = $request->all();
        if (LessonService::doAgreeLesson($params)) {
            return redirect()->route('admin.lesson_examination.index');
        } else {
            return back();
        }*/
    }

    public function disagree(Request $request, Lesson $lesson)
    {
        $del_session = $request->input('del_session', 0);
        if ($del_session) {
            Session::forget('admin.service_manage.lesson_examination.agree');
        }

        $condition = Session::get('admin.service_manage.lesson_examination.agree', []);

        return view('admin.service_manage.lesson_examination.disagree', [
            'obj_lesson' => $lesson,
            'condition' => $condition,
        ]);
    }

    public function postReason(LessonDisagreeRequest $request)
    {
        $params = $request->all();
        if (isset($params['_token'])) {
            unset($params['_token']);
        }
        Session::put('admin.service_manage.lesson_examination.agree', $params);
        return redirect()->route('admin.lesson_examination.alert_create');
    }

    public function createAlert(Request $request)
    {
        $condition = Session::get('admin.service_manage.lesson_examination.agree', []);
        return view('admin.service_manage.lesson_examination.alert_create', [
            'condition' => $condition
        ]);
    }

    public function doConfirmAlert(CommonAlertRequest $request)
    {
        $params = $request->all();
        $staff_confirm_detail = Session::get('admin.service_manage.lesson_examination.agree', []);
        $staff_confirm_detail['alert_title'] = $params['alert_title'];
        $staff_confirm_detail['alert_text'] = $params['alert_text'];
        Session::put('admin.service_manage.lesson_examination.agree', $staff_confirm_detail);
        return redirect()->route('admin.lesson_examination.alert_confirm');
    }

    public function confirmAlert(Request $request)
    {
        $condition = Session::get('admin.service_manage.lesson_examination.agree', []);
        return view('admin.service_manage.lesson_examination.alert_confirm', [
            'condition' => $condition
        ]);
    }

    public function sendAlert(Request $request)
    {
        $condition = Session::get('admin.service_manage.lesson_examination.agree', []);
        $obj_lesson = Lesson::find($condition['lesson_id']);
        $user_id = $obj_lesson->senpai->id;
        $obj_user = User::find($user_id);

        $admin_id = Auth::guard('admin')->user()->id;

        $contents = "送信が失敗しました。";
        $lesson_state = $condition['agree_type'] == config('const.agree_flag.disagree') ? config('const.lesson_state.reject') : config('const.lesson_state.public');
        if (LessonService::updateLessonState($condition['lesson_id'], $lesson_state)) {
            MessageService::doCreateMsgFromAdmin(MessageService::MSG_CLASS_OWN_NEWS, $admin_id, $user_id, $condition['alert_text']);
            $contents = "送信しました。";
            try {
                $mail_obj = Mail::to($obj_user->email);
                // $cc_mail_arr = [config('const.yamadazaidan_email')];
                // $mail_obj->cc($cc_mail_arr);
                $mail_obj->send(new SendAlertEmail($condition));
                $contents = "送信しました。";
            } catch (\Exception $exception) {
                Log::error("EMail Sending Failed：".$obj_user->email);
                Log::error($exception->getMessage());
            }
        }

        $modal_confrim_url = route('admin.lesson_examination.index');

        Session::forget('admin.service_manage.lesson_examination.agree');

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
