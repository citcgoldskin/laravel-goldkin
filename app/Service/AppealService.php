<?php

namespace App\Service;

use App\Models\Appeal;
use App\Models\AppealClass;
use App\Models\AppealReport;
use Auth;
use Carbon\Carbon;
use Session;
use DB;

class AppealService
{
    public static function doSearch($condition=[]) {
        $staffs = Appeal::selectRaw('count(appeals.user_id) as cnt_appeal, MAX(appeals.reported_at) as end_reported_at, MIN(appeals.status) as exist_not_read , appeals.*')
            ->with('appeal_user')
            ->groupBy('appeals.appeal_user_id');

        $staffs->leftJoin('users', function ($join) {
            $join->on('appeals.appeal_user_id', 'users.id');
        });

        // 未読のみを表示する
        if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
            $staffs->where("status", config('const.read_status.not_read'));
        }

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $staffs->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $staffs->whereIn('appeals.appeal_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where('user_area_id', $condition['area']);
            });
        }

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $staffs->whereDate('appeals.reported_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $staffs->whereDate('appeals.reported_at', '<=', $condition['to_date']);
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $staffs->whereRaw("name LIKE ?", '%'.$condition['nickname'].'%');
        }

        // 性別
        if (isset($condition['user_sex']) && $condition['user_sex']) {
            $staffs->where("user_sex", $condition['user_sex']);
        }

        // 会員種別
        if (isset($condition['user_type']) && !is_null($condition['user_type'])) {
            $staffs->where("user_is_senpai", $condition['user_type']);
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.report_sort_code.register_new')) {
                $staffs->orderByDesc('end_reported_at');
            } else if($condition['order'] == config('const.report_sort_code.register_old')) {
                $staffs->orderBy('end_reported_at');
            }
            if($condition['order'] == config('const.report_sort_code.report_count')) {
                $staffs->orderByDesc('cnt_appeal');
            }
        }

        // ぴろしきまるのみ表示
        if(isset($condition['punishment']) && $condition['punishment']) {
            $staffs->whereIn('appeals.appeal_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where(function($q) {
                        $q->where('punishment_cnt', '>', 0);
                        $q->orWhere('caution_cnt', '>', 0);
                    });
            });
        }

        return $staffs;
    }

    public static function getAppealClasses()
    {
        return AppealClass::orderBy('sort')
            ->get();
    }

    public static function getAppealClassNameByType($type)
    {
        $ret = AppealClass::where('id', $type)->first();
        if (is_object($ret)) {
            return $ret->name;
        }
        return "";
    }

    public static function doAppeals($user_id, $params)
    {
        $ret = new Appeal();
        $ret->user_id = $user_id;
        $ret->appeal_user_id = $params['appealId'];
        $ret->note = $params['note'];
        $ret->status = config('const.msg_state.unread');
        $ret->reported_at = Carbon::now()->format('Y-m-d H:i:s');
        $ret->save();
        return $ret;
    }

    public static function saveAppealsReport($appeal_id, $vals){

        $data['appeal_id'] = $appeal_id;

        foreach($vals as $k => $v) {
            if ( $v == "false")
                continue;

            $data['type'] = $k;

            $ret = AppealReport::create($data);
            if (!$ret)
                return false;
        }
        return true;
    }

    public static function getAppealUserInfo($obj_user, $condition=[]) {
        $ret = Appeal::where('appeal_user_id', $obj_user->id);

        // 未読ステータス
        if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
            $ret->where('status', config('const.read_status.not_read'));
        }

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $ret->where('reported_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $ret->where('reported_at', '<=', $condition['to_date']);
        }

        /*$ret = AppealReport::select('appeal_reports.*')
            ->orderByDesc('reported_at')
            ->leftJoin('appeals', function($join) {
                $join->on('appeal_reports.appeal_id', 'appeals.id');
            })
            ->where('appeals.appeal_user_id', $obj_user->id);*/
        return $ret->orderByDesc('reported_at')->get();
    }

    public static function getAppealCountByCondition($condition=[]) {

        $search_condition = Session::get('admin.fraud.report', []);
        if (isset($search_condition['chk_not_read']) && $search_condition['chk_not_read']) {
            $condition['chk_not_read'] = $search_condition['chk_not_read'];
        }

        $ret = AppealReport::select('appeal_reports.*');
        if (isset($condition['user_id']) && $condition['user_id']) {
            $ret->leftJoin('appeals', function ($join) {
                $join->on('appeal_reports.appeal_id', 'appeals.id');
            });
            $ret->where('appeals.appeal_user_id', $condition['user_id']);
        }

        // 期間
        if(isset($search_condition['from_date']) && $search_condition['from_date']) {
            $ret->whereDate('reported_at', '>=', $search_condition['from_date']);
        }
        if(isset($search_condition['to_date']) && $search_condition['to_date']) {
            $ret->whereDate('reported_at', '<=', $search_condition['to_date']);
        }

        if (isset($condition['type']) && $condition['type']) {
            $ret->where('appeal_reports.type', $condition['type']);
        }
        if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
            $ret->where('appeals.status', config('const.read_status.not_read'));
        }

        return $ret->get()->count();
    }

    public static function getUnreadAppeal($appeal_user_id=null) {
        $count = Appeal::orderByDesc('updated_at');
        if (!is_null($appeal_user_id)) {
            $count = Appeal::where('appeal_user_id', $appeal_user_id);
        }
        $count->where(function ($query) {
            $query->where('status', config('const.msg_state.unread'));
            $query->orWhereNull('status');
        });
        $count = $count->get()->count();
        return $count;
    }

    public static function doAppealReportRead($obj_appeal) {
        $obj_appeal->status = config('const.msg_state.read');
        return $obj_appeal->save();
    }

    public static function doAppealRead($obj_user, $status) {
        return Appeal::where('appeal_user_id', $obj_user->id)->update('status', $status);
    }

    public static function getAppealClassName($class_id) {
        $obj_appeal_class = AppealClass::find($class_id);
        return is_object($obj_appeal_class) && $obj_appeal_class->name ? $obj_appeal_class->name : '';
    }

    public static function doSetNotRead($appeal_ids) {
        return Appeal::whereIn('id', $appeal_ids)
            ->update([
                'status'=>config('const.read_status.not_read')
            ]);
    }
}
