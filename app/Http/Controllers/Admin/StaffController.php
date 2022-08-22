<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Service\EvalutionService;
use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;
use Storage;
use DB;

class StaffController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.user.search', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [
                "email" => null,
                "user_id" => null,
                "user_phone" => null,
                "user_name" => null,
                "nickname" => null,
                "from_age" => null,
                "to_age" => null,
                "user_sex" => null,
                "from_register_at" => null,
                "to_register_at" => null,
                "user_type" => null,
                "from_number_purchase" => null,
                "to_number_purchase" => null,
                "from_number_sale" => null,
                "to_number_sale" => null,
                "avg_senpai" => null,
                "avg_kouhai" => null,
                "from_period_aggregation" => null,
                "to_period_aggregation" => null,
                "from_period_register" => null,
                "to_period_register" => null
            ];
            $condition['order'] = config('const.user_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        }

        Session::put('admin.user.search', $condition);

        $obj_users = UserService::doSearchAdmin($condition)->paginate($this->per_page);

        return view('admin.staff.index', [
            'obj_users' => $obj_users,
            'search_params' => $condition
        ]);
    }

    public function profile(Request $request, User $staff)
    {
        $senpai_eval_types = EvalutionService::getEvalutionTypes(EvalutionService::SENPAIS_EVAL);
        $kouhai_eval_types = EvalutionService::getEvalutionTypes(EvalutionService::KOUHAIS_EVAL);
        return view('admin.staff.profile', [
            'obj_user'=>$staff,
            'senpai_eval_types'=>$senpai_eval_types,
            'kouhai_eval_types'=>$kouhai_eval_types,
        ]);
    }

    public function caution(Request $request)
    {
        $user_id = $request->input('caution_user_id');
        $page_type = $request->input('page_from');
        $obj_user = User::find($user_id);
        $contents = "操作が失敗しました。";
        if (UserService::doCaution($obj_user)) {
            $contents = "要注意マークをつけました。";
        }
        $modal_confrim_url = route('admin.fraud_report.detail')."/".$user_id;
        if ($page_type == "block") {
            $modal_confrim_url = route('admin.fraud_block.detail')."/".$user_id;
        } else if($page_type == "profile") {
            $modal_confrim_url = route('admin.staff.detail')."/".$user_id;
        }

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
