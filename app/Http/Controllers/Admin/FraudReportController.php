<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appeal;
use App\Models\AppealReport;
use App\Service\AppealService;
use App\Service\PunishmentService;
use App\Service\UserService;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use DB;
use Session;

class FraudReportController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.report', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $tmp_chk_not_read = null;
            if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
                $tmp_chk_not_read = $condition['chk_not_read'];
            }
            $tmp_order = null;
            if (isset($condition['order']) && $condition['order']) {
                $tmp_order = $condition['order'];
            }
            $condition = [
                "user_type" => null,
                "user_sex" => null,
                "user_id" => null,
                "nickname" => null
            ];
            if (!is_null($tmp_chk_not_read)) {
                $condition['chk_not_read'] = $tmp_chk_not_read;
            }
            if (!is_null($tmp_order)) {
                $condition['order'] = $tmp_order;
            }
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        }

        if (isset($params['chk_not_read'])) {
            $condition['chk_not_read'] = $params['chk_not_read'];
        }

        Session::put('admin.fraud.report', $condition);

        $obj_users = AppealService::doSearch($condition)->paginate($this->per_page);

        // check ソート
        /*if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.report_sort_code.register_new')) {
                $obj_users->setCollection(
                    $obj_users->sortByDesc('last_updated_at')
                );
            } else if ($condition['order'] == config('const.report_sort_code.register_old')) {
                $obj_users->setCollection(
                    $obj_users->sortBy('last_updated_at')
                );
            }
        }*/

        // check 期間 => 最終更新日
        /*if((isset($condition['from_date']) && $condition['from_date']) || (isset($condition['to_date']) && $condition['to_date'])) {
            $obj_users->setCollection(
                $obj_users->filter(function ($obj_user) use($condition) {
                    if ((isset($condition['from_date']) && $condition['from_date']) && (isset($condition['to_date']) && $condition['to_date'])) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') >= Carbon::parse($condition['from_date'])->format('Y-m-d') && Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') <= Carbon::parse($condition['to_date'])->format('Y-m-d');
                    } else if(isset($condition['from_date']) && $condition['from_date']) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') >= Carbon::parse($condition['from_date'])->format('Y-m-d');
                    } else if(isset($condition['to_date']) && $condition['to_date']) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') <= Carbon::parse($condition['to_date'])->format('Y-m-d');
                    }
                })
            );
        }*/

        return view('admin.fraud.report.index', [
            'obj_users' => $obj_users,
            'search_params' => $condition
        ]);
    }

    public function detail(Request $request, User $user)
    {
        $condition = Session::get('admin.fraud.report', []);
        $appeal_users = AppealService::getAppealUserInfo($user, $condition);
        $appealClasses = AppealService::getAppealClasses();

        $all_appeal_count = AppealService::getAppealCountByCondition(['user_id'=>$user->id]);
        $appeal_count_color_info = [];
        foreach ($appealClasses as $key=>$ele) {
            $individual = [];
            $appeal_count = AppealService::getAppealCountByCondition(['user_id'=>$user->id, 'type'=>$ele->id]);
            $individual['color'] = config('const.appeal_class_color.'.($key+1));
            $individual['percent'] = 100 * $appeal_count/$all_appeal_count;
            $appeal_count_color_info[$key + 1] = $individual;
        }

        $punishment_history = PunishmentService::getPunishmentHistoryByUser($user->id);
        return view('admin.fraud.report.detail', [
            'own_user' => $user,
            'appeal_users' => $appeal_users,
            'appeal_classes' => $appealClasses,
            'punishment_history' => $punishment_history,
            'appeal_count_color_info' => json_encode($appeal_count_color_info),
        ]);
    }

    public function getDetail(Request $request)
    {
        $params = $request->all();
        $obj_appeal = Appeal::find($params['appeal_id']);
        if ($obj_appeal->status != config('const.msg_state.read')) {
            AppealService::doAppealReportRead($obj_appeal);
        }

        return response()->json([
            'result_code' => 'success',
            'report_detail' => view('admin.fraud.report._report_detail', ['obj_appeal'=>$obj_appeal])->render(),
        ]);
    }

    public function doSetNotRead(Request $request)
    {
        $params = $request->all();
        $appeal_ids = $params['appeal_id'];
        if (AppealService::doSetNotRead($appeal_ids)) {
            return redirect()->route('admin.fraud_report.index');
        }
        return back();
    }

}
