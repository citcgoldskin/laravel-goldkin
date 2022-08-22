<?php

namespace App\Service;

use App\Models\Appeal;
use App\Models\AppealClass;
use App\Models\AppealReport;
use App\Models\Punishment;
use Auth;
use Carbon\Carbon;
use DB;

class PunishmentService
{
    public static function doSearch($condition=[]) {

        $staffs = Punishment::selectRaw('punishments.*')->with('user');
        $staffs->leftJoin('users', function ($join) {
            $join->on('punishments.user_id', 'users.id');
        });

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $staffs->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $staffs->whereIn('punishments.user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where('user_area_id', $condition['area']);
            });
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

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $staffs->whereDate('decided_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $staffs->whereDate('decided_at', '<=', $condition['to_date']);
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $staffs->orderByDesc('punishments.updated_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $staffs->orderBy('punishments.updated_at');
            }
        }

        return $staffs;
    }

    public static function doRegister($params) {
        $obj_punishment = new Punishment();
        $obj_punishment->user_id = $params['user_id'];
        $obj_punishment->type = $params['decision_type'];
        if ($obj_punishment->type == config('const.punishment_decision_code.lesson_article_stop') || $obj_punishment->type == config('const.punishment_decision_code.buy_sell_stop')) {
            $obj_punishment->stop_period = config('const.stop_period.'.$params['stop_period']);
        }

        $obj_punishment->basis = json_encode($params['basis']);
        $obj_punishment->reason = json_encode($params['reason']);
        $obj_punishment->alert_title = $params['alert_title'];
        $obj_punishment->alert_text = $params['alert_text'];
        $obj_punishment->decided_at = Carbon::now()->format('Y-m-d H:i:s');
        return $obj_punishment->save();
    }

    public static function getPunishmentHistoryByUser($user_id) {
        return Punishment::where('user_id', $user_id)->orderBy('decided_at')->get();
    }
}
