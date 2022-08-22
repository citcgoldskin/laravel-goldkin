<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Recruit;
use App\Service\CommonService;
use App\Service\EvalutionService;
use App\Service\KeijibannService;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\SenpaiService;
use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use DB;

class FraudCancelReserveController extends AdminController
{
    public function lesson(Request $request)
    {

        $params = $request->all();
        $condition = [];
        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        $obj_lessons = LessonService::doCancelStopLessonSearch($condition)->paginate($this->per_page);
        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.fraud.cancel_reserve.lesson', [
            'obj_lessons' => $obj_lessons,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function recruit(Request $request)
    {
        $params = $request->all();
        $condition = [];

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        $obj_recruits = KeijibannService::doCancelStopRecruitSearch($condition)->paginate($this->per_page);
        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.fraud.cancel_reserve.recruit', [
            'obj_recruits' => $obj_recruits,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function lessonDetail(Request $request, Lesson $lesson)
    {
        $lesson_id = $lesson->lesson_id;
        $senpai_id = LessonService::getSenpaiIdFromLesson($lesson_id);
        $data['schedule_count'] = LessonService::getScheduleCntByLessonId($lesson_id);
        $data['evalution_count'] = EvalutionService::getLessonEvalutionCount($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['evalution'] = EvalutionService::getLessonEvalutionPercentByType($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['senpai'] = SenpaiService::getSenpaiInfo($senpai_id);
        $data['lesson'] = LessonService::getLessonInfo($lesson_id);
        $data['lesson_conds'] = LessonService::getLessonCondNames($data['lesson']);
        $data['lesson_images'] = CommonService::unserializeData($data['lesson']['lesson_image']);

        return view('admin.fraud.cancel_reserve.lesson_detail', [
            'data' => $data,
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

        return view('admin.fraud.cancel_reserve.recruit_detail', [
            'data'=>$recruit,
            'buy_count' => $buy_count,
            'sell_count' => $sell_count,
        ]);
    }

    public function lessonDelete(Request $request)
    {
        $params = $request->all();
        $contents = "予約を削除しました。";
        $modal_confrim_url = route('admin.fraud_cancel_reserve.lesson');
        if (!LessonService::cancelStopLessonCancel($params)) {
            $contents = "予約の削除に失敗しました。";
            $modal_confrim_url = route('admin.fraud_cancel_reserve.lesson_detail', ['lesson'=>$params['lesson_id']]);
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

    public function recruitDetete(Request $request)
    {
        $params = $request->all();
        $contents = "予約を削除しました。";
        $modal_confrim_url = route('admin.fraud_cancel_reserve.recruit');
        if (!KeijibannService::cancelStopRecruitCancel($params)) {
            $contents = "予約の削除に失敗しました。";
            $modal_confrim_url = route('admin.fraud_cancel_reserve.recruit_detail', ['recruit'=>$params['recruit_id']]);
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
