<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Service\CommonService;
use App\Service\EvalutionService;
use App\Service\LessonClassService;
use App\Service\LessonService;
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

class FraudStopLessonController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.stop_lesson', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [
                "user_id" => null,
                "nickname" => null
            ];
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.stop_lesson_sort_code.register_new');
        }


        Session::put('admin.fraud.stop_lesson', $condition);


        $obj_lessons = LessonService::doStopLessonSearch($condition)->paginate($this->per_page);
        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.fraud.stop_lesson.index', [
            'obj_lessons' => $obj_lessons,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function search(Request $request) {
        return view('admin.fraud.stop_lesson.search', [
        ]);
    }

    public function detail(Request $request, Lesson $lesson) {

        $lesson_id = $lesson->lesson_id;
        $senpai_id = LessonService::getSenpaiIdFromLesson($lesson_id);
        $data['schedule_count'] = LessonService::getScheduleCntByLessonId($lesson_id);
        $data['evalution_count'] = EvalutionService::getLessonEvalutionCount($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['evalution'] = EvalutionService::getLessonEvalutionPercentByType($lesson_id, EvalutionService::KOUHAIS_EVAL);
        $data['senpai'] = SenpaiService::getSenpaiInfo($senpai_id);
        $data['lesson'] = LessonService::getLessonInfo($lesson_id);
        $data['lesson_conds'] = LessonService::getLessonCondNames($data['lesson']);
        $data['lesson_images'] = CommonService::unserializeData($data['lesson']['lesson_image']);

        return view('admin.fraud.stop_lesson.detail', [
            'data' => $data,
            'obj_lesson' => $lesson,
        ]);
    }

    public function cancel(Request $request, $lesson_id) {
        return view('admin.fraud.stop_lesson.cancel', [
            'lesson_id' => $lesson_id
        ]);
    }

    public function doCancel(Request $request)
    {
        $params = $request->all();
        $contents = "設定を完了しました。";
        $modal_confrim_url = route('admin.fraud_stop_lesson.index');
        if (!LessonService::stopLessonCancel($params)) {
            $contents = "設定に失敗しました。";
            $modal_confrim_url = route('admin.fraud_stop_lesson.cancel', ['lesson_id'=>$params['lesson_id']]);
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
