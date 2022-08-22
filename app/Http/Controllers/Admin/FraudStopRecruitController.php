<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recruit;
use App\Service\CommonService;
use App\Service\FavouriteService;
use App\Service\KeijibannService;
use App\Service\LessonClassService;
use App\Service\LessonService;
use App\Service\TimeDisplayService;
use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class FraudStopRecruitController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.stop_recruit', []);

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

        Session::put('admin.fraud.stop_recruit', $condition);

        $obj_recruits = KeijibannService::doStopRecruitSearch($condition)->paginate($this->per_page);
        $categories  = LessonClassService::getAllLessonClasses();

        return view('admin.fraud.stop_recruit.index', [
            'obj_recruits' => $obj_recruits,
            'categories'=>$categories,
            'search_params' => $condition
        ]);
    }

    public function detail(Request $request, Recruit $recruit)
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

        return view('admin.fraud.stop_recruit.detail', [
            'data'=>$recruit,
            'buy_count' => $buy_count,
            'sell_count' => $sell_count,
        ]);
    }

    public function cancel(Request $request, $recruit_id) {
        return view('admin.fraud.stop_recruit.cancel', [
            'recruit_id' => $recruit_id
        ]);
    }

    public function doCancel(Request $request)
    {
        $params = $request->all();
        $contents = "設定を完了しました。";
        $modal_confrim_url = route('admin.fraud_stop_recruit.index');
        if (!KeijibannService::stopRecruitCancel($params)) {
            $contents = "設定に失敗しました。";
            $modal_confrim_url = route('admin.fraud_stop_recruit.cancel', ['recruit_id'=>$params['recruit_id']]);
        }

        return view('admin.layouts.modal_ok',[
                'contents'=>$contents,
                'modal_confrim_url'=>$modal_confrim_url
            ]);
    }

}
