<?php

namespace App\Service;

use App\Models\Evalution;
use App\Models\Favourite;
use App\Models\LessonRequest;
use App\Models\User;
use App\Models\PersonConfirm;
use App\Models\Block;
use Auth;
use Carbon\Carbon;
use DB;

class UserService
{
    public static function doSearchAdmin($condition=[]) {
        $staffs = User::select('users.*');

        // メールアドレス
        if (isset($condition['email']) && $condition['email']) {
            $staffs->where("email", 'like', '%'.$condition['email'].'%');
        }

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $staffs->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // 電話番号
        if (isset($condition['user_phone']) && $condition['user_phone']) {
            $tel = str_replace([' ', '　', '-', 'ー'], '', $condition['user_phone']);
            $staffs->whereRaw("replace(users.user_phone, '-', '') like '%{$tel}%'");
        }

        // 氏名
        if (isset($condition['user_name']) && $condition['user_name']) {
            $staffs->where(function($query) use($condition) {
                $query->whereRaw("CONCAT(user_firstname, user_lastname) LIKE ?", '%'.$condition['user_name'].'%');
                $query->orWhereRaw("CONCAT(user_sei, user_mei) LIKE ?", '%'.$condition['user_name'].'%');
            });
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $staffs->where("name", 'like', '%'.$condition['nickname'].'%');
        }

        // 年代
        if (isset($condition['from_age']) && $condition['from_age']) {
            $staffs->whereRaw('TIMESTAMPDIFF(YEAR, user_birthday, CURDATE()) >= '.$condition['from_age']);
        }
        if (isset($condition['to_age']) && $condition['to_age']) {
            $to_age = 999;
            if ($condition['to_age'] != config('const.age_year.seventieth')) {
                $to_age = $condition['to_age'] + 10;
            }
            $staffs->whereRaw('TIMESTAMPDIFF(YEAR, user_birthday, CURDATE()) < '.$to_age);
        }

        // 性別
        if (isset($condition['user_sex']) && $condition['user_sex']) {
            $staffs->where("user_sex", $condition['user_sex']);
        }

        // 登録日
        if (isset($condition['from_register_at']) && $condition['from_register_at']) {
            $staffs->whereDate("created_at", '>=', $condition['from_register_at']);
        }
        if (isset($condition['to_register_at']) && $condition['to_register_at']) {
            $staffs->whereDate("created_at", '<=', $condition['to_register_at']);
        }

        // 会員種別
        if (isset($condition['user_type']) && !is_null($condition['user_type'])) {
            $staffs->where("user_is_senpai", $condition['user_type']);
        }

        // 購入件数
        if (isset($condition['from_number_purchase']) && $condition['from_number_purchase']) {
            /*$join_query = LessonRequest::selectRaw('count(lesson_request_schedules.lrs_id) as p_cnt, lesson_requests.lr_user_id as p_user_id')
                ->leftJoin('lesson_request_schedules', function ($join) {
                    $join->on('lesson_requests.lr_id', 'lesson_request_schedules.lrs_lr_id');
                })
                ->where('lesson_request_schedules.lrs_state','<=', config('const.schedule_state.complete'))
                ->where('lesson_request_schedules.lrs_state', '>=', config('const.schedule_state.reserve'))
                ->groupBy('lesson_requests.lr_user_id');*/
            $join_query = DB::raw("(select count(lesson_request_schedules.lrs_id) as p_cnt, lesson_requests.lr_user_id as p_user_id
                        from lesson_requests
                        left join  lesson_request_schedules on (lesson_requests.lr_id=lesson_request_schedules.lrs_lr_id)
                        where lesson_request_schedules.lrs_state <= ".config('const.schedule_state.complete')." and
                        lesson_request_schedules.lrs_state >= " . config('const.schedule_state.reserve').
                        " group by lesson_requests.lr_user_id) as purchase_data");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'purchase_data.p_user_id');
            });
            $staffs->where('purchase_data.p_cnt', '>=', $condition['from_number_purchase']);

        }
        if (isset($condition['to_number_purchase']) && $condition['to_number_purchase']) {
            $join_query = DB::raw("(select count(lesson_request_schedules.lrs_id) as p_cnt, lesson_requests.lr_user_id as p_user_id
                        from lesson_requests
                        left join  lesson_request_schedules on (lesson_requests.lr_id=lesson_request_schedules.lrs_lr_id)
                        where lesson_request_schedules.lrs_state <= ".config('const.schedule_state.complete')." and
                        lesson_request_schedules.lrs_state >= " . config('const.schedule_state.reserve').
                " group by lesson_requests.lr_user_id) as to_purchase_data");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'to_purchase_data.p_user_id');
            });
            $staffs->where('to_purchase_data.p_cnt', '<=', $condition['to_number_purchase']);
        }

        // 販売回数
        if (isset($condition['from_number_sale']) && $condition['from_number_sale']) {
            $join_query = DB::raw("(select count(lesson_request_schedules.lrs_id) as s_cnt, lessons.lesson_senpai_id as s_senpai_id
                        from lesson_requests
                        left join  lesson_request_schedules on (lesson_requests.lr_id=lesson_request_schedules.lrs_lr_id)
                        left join  lessons on (lesson_requests.lr_lesson_id=lessons.lesson_id)
                        where lesson_request_schedules.lrs_state <= ".config('const.schedule_state.complete')." and
                        lesson_request_schedules.lrs_state >= " . config('const.schedule_state.reserve').
                " group by lessons.lesson_senpai_id) as sale_data");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'sale_data.s_senpai_id');
            });
            $staffs->where('sale_data.s_cnt', '>=', $condition['from_number_sale']);
        }
        if (isset($condition['to_number_sale']) && $condition['to_number_sale']) {
            $join_query = DB::raw("(select count(lesson_request_schedules.lrs_id) as s_cnt, lessons.lesson_senpai_id as s_senpai_id
                        from lesson_requests
                        left join  lesson_request_schedules on (lesson_requests.lr_id=lesson_request_schedules.lrs_lr_id)
                        left join  lessons on (lesson_requests.lr_lesson_id=lessons.lesson_id)
                        where lesson_request_schedules.lrs_state <= ".config('const.schedule_state.complete')." and
                        lesson_request_schedules.lrs_state >= " . config('const.schedule_state.reserve').
                " group by lessons.lesson_senpai_id) as to_sale_data");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'to_sale_data.s_senpai_id');
            });
            $staffs->where('to_sale_data.s_cnt', '<=', $condition['to_number_sale']);
        }

        // 平均評価（センパイ）
        if (isset($condition['avg_senpai']) && $condition['avg_senpai']) {
            /*$join_query = DB::raw("(select sum(evalutions.eval_val)/count(evalutions.eval_val)/5 as avg_mark
                        from evalutions
                        where evalutions.eval_kind = ".EvalutionService::SENPAIS_EVAL.
                " group by evalutions.target_user_id) as average_evalution");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'average_evalution.target_user_id');
            });
            $avg_min = config('const.average_evalution_period.'.$condition['avg_senpai'])['min'];
            $avg_max = config('const.average_evalution_period.'.$condition['avg_senpai'])['max'];
            $staffs->where('average_evalution.avg_mark', '>=', $avg_min);
            $staffs->where('average_evalution.avg_mark', '<', $avg_max);*/
        }

        // 平均評価（コウハイ）
        if (isset($condition['avg_kouhai']) && $condition['avg_kouhai']) {
            /*$join_query = DB::raw("(select sum(evalutions.eval_val)/count(evalutions.eval_val)/5 as avg_mark
                        from evalutions
                        where evalutions.eval_kind = ".EvalutionService::KOUHAIS_EVAL.
                " group by evalutions.target_user_id) as average_evalution");

            $staffs->leftJoin($join_query, function ($join) {
                $join->on('users.id', 'average_evalution.target_user_id');
            });
            $avg_min = config('const.average_evalution_period.'.$condition['avg_kouhai'])['min'];
            $avg_max = config('const.average_evalution_period.'.$condition['avg_kouhai'])['max'];
            $staffs->where('average_evalution.avg_mark', '>=', $avg_min);
            $staffs->where('average_evalution.avg_mark', '<', $avg_max);*/
        }

        // 集計期間

        // 登録期間

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.user_sort_code.register_new')) {
                $staffs->orderByDesc('created_at');
            } else if($condition['order'] == config('const.user_sort_code.register_old')) {
                $staffs->orderBy('created_at');
            }
        }

        return $staffs;
    }

    public static function doSearch($condition=[]) {
        $staffs = User::select('*');

        // メールアドレス
        if (isset($condition['email']) && $condition['email']) {
            $staffs->where("email", 'like', '%'.$condition['email'].'%');
        }

        // ID

        // 電話番号
        if (isset($condition['user_phone']) && $condition['user_phone']) {
            $tel = str_replace([' ', '　', '-', 'ー'], '', $condition['user_phone']);
            $staffs->whereRaw("replace(users.user_phone, '-', '') like '%{$tel}%'");
        }

        // 氏名
        if (isset($condition['user_name']) && $condition['user_name']) {
            $staffs->where(function($query) use($condition) {
                $query->whereRaw("CONCAT(user_firstname, user_lastname) LIKE ?", '%'.$condition['user_name'].'%');
                $query->orWhereRaw("CONCAT(user_sei, user_mei) LIKE ?", '%'.$condition['user_name'].'%');
            });
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $staffs->where("name", 'like', '%'.$condition['nickname'].'%');
        }

        // 年代
        if (isset($condition['user_age']) && $condition['user_age']) {

        }

        // 性別
        if (isset($condition['user_sex']) && $condition['user_sex']) {
            $staffs->where("user_sex", $condition['user_sex']);
        }

        // 登録日
        if (isset($condition['register_at']) && $condition['register_at']) {
            $staffs->whereDate("created_at", $condition['register_at']);
        }

        // 会員種別
        if (isset($condition['user_type']) && !is_null($condition['user_type'])) {
            $staffs->where("user_is_senpai", $condition['user_type']);
        }

        // 購入件数

        // 販売回数

        // 平均評価（センパイ）

        // 平均評価（コウハイ）

        // 集計期間

        // 登録期間

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.user_sort_code.register_new')) {
                $staffs->orderByDesc('created_at');
            } else if($condition['order'] == config('const.user_sort_code.register_old')) {
                $staffs->orderBy('created_at');
            }
        }

        return $staffs;
    }

    //user
    public static function getUserList() {
        return User::orderBy('id', 'asc')
            ->get()->toArray();
    }

    public static function getUserByID($id) {
        return User::where('id', $id)
            ->with('userConfirm')
            ->first();
    }

    public static function getUserByCode($code)
    {
        return User::where('user_code', $code)
            ->first();
    }

    public static function deleteUser($id) {
        return User::where('id', $id)
            ->delete();
    }

    public static function getUserConfirmed($id)
    {
        $user_info = User::with('userConfirm')
            ->where('id', $id)
            ->first();

        if (!$user_info['userconfirm'])
            return false;

        if ($user_info['userconfirm']['pc_state'] == config('const.pers_conf.confirmed'))
            return true;

        return false;
    }

    public static function doCreatePersonConfirm($data)
    {
        $data_array = array();
        $data_array['pc_user_id'] = Auth::user()->id;
        $data_array['pc_confirm_doc'] =  $data['file_name'];
        $data_array['pc_state'] = config('const.pers_conf.review');

        PersonConfirm::create($data_array);
    }

    public static function updatePersonConfirm($data)
    {
        $data_array = array();
        $user_id = isset($data['use_id']) ? $data['use_id'] : Auth::user()->id;
        if(isset($data['file_name']))
            $data_array['pc_confirm_doc'] = $data['file_name'];

        $data_array['pc_state'] = $data['state'];

        if(isset($data['state']))
            $data_array['pc_reject_reason'] = $data['state'];

        PersonConfirm::where('pc_user_id', $user_id)->update($data_array);
    }

    public static function getFavoriteSenpais(){

        if(Auth::check()) {

            return Favourite::where('fav_type', config('const.fav_type.user'))
                ->where('fav_user_id', Auth::user()->id)
                ->whereHas('f_user', function ($query){
                    $query->where('user_state', 1);
                    $query->where('user_is_senpai', 1);
                })
                ->get();
        }
        return [];

    }

    public static function updateUserInfo($id, $data)
    {

        $obj_user = User::find($id);
        if(is_object($obj_user)) {
            return $obj_user->update($data);
        }

        return false;
    }

    public static function generateCustomerID()
    {
        return 'CST' . Carbon::now()->format('ymd') . uniqid();
    }

    public static function setMapLocation($user_id, $position)
    {
        $obj_user = User::find($user_id);
        if (!is_object($obj_user)) return false;

        $obj_user->current_location = json_encode($position, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $obj_user->save();
    }

    public static function getMapLocation($user_id)
    {
        $obj_user = User::find($user_id);
        if (!is_object($obj_user)) return "";

        return $obj_user->current_location;
    }

    public static function doCaution($obj_user)
    {
        $obj_user->caution_cnt += 1;
        return $obj_user->save();
    }

    public static function doPunishment($obj_user)
    {
        $obj_user->punishment_cnt += 1;
        return $obj_user->save();
    }

    public static function getEvalutionCountByType($user_id, $type)
    {
        if ($type == config('const.staff_type.kouhai')) {
            // コウハイの評価 ($user_id => センパイ)
            $ret = Evalution::where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->groupBy('eval_schedule_id')
                ->get()
                ->count();
        } else if($type == config('const.staff_type.senpai')) {
            // センパイの評価 ($user_id => コウハイ)
            $ret = Evalution::where('eval_kind', EvalutionService::SENPAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->groupBy('eval_schedule_id')
                ->get()
                ->count();
        }
        return $ret;
    }

    public static function getEvalutionValueCountByType($user_id, $type)
    {
        $ret = 0;
        if ($type == config('const.staff_type.senpai')) {
            $senpai_eval_types = EvalutionService::getEvalutionTypes(EvalutionService::SENPAIS_EVAL);
            // コウハイの評価 ($user_id => センパイ)
            foreach ($senpai_eval_types as $k => $v) {
                $ret += self::getEvalutionPercentByType($user_id, config('const.staff_type.senpai'), $v['et_id']);
            }
            $ret = (int)$ret;
        } else if($type == config('const.staff_type.kouhai')) {
            $kouhai_eval_types = EvalutionService::getEvalutionTypes(EvalutionService::KOUHAIS_EVAL);
            // センパイの評価 ($user_id => コウハイ)
            foreach ($kouhai_eval_types as $k => $v) {
                $ret += self::getEvalutionPercentByType($user_id, config('const.staff_type.kouhai'), $v['et_id']);
            }
            $ret = (int)$ret;
        }
        return $ret;
        /*if ($type == config('const.staff_type.kouhai')) {
            // コウハイの評価 ($user_id => センパイ)
            $ret = Evalution::selectRaw('sum(eval_val) as cnt_eval')
                ->where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->get();
        } else if($type == config('const.staff_type.senpai')) {
            // センパイの評価 ($user_id => コウハイ)
            $ret = Evalution::selectRaw('sum(eval_val) as cnt_eval')
                ->where('eval_kind', EvalutionService::SENPAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->get();
        }
        return is_object($ret) && isset($ret[0]) ? (int)$ret[0]->cnt_eval : 0;*/
    }

    public static function getEvalutionPercentByType($user_id, $eval_kind, $eval_type)
    {
        if ($eval_kind == config('const.staff_type.kouhai')) {
            // コウハイの評価 ($user_id => センパイ)
            $ret = Evalution::selectRaw('sum(eval_val)*100/count(eval_val) as percent_eval')
                ->where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->where('eval_type_id', $eval_type)
                ->get();
        } else if($eval_kind == config('const.staff_type.senpai')) {
            // センパイの評価 ($user_id => コウハイ)
            $ret = Evalution::selectRaw('sum(eval_val)*100/count(eval_val) as percent_eval')
                ->where('eval_kind', EvalutionService::SENPAIS_EVAL)
                ->where('target_user_id', $user_id)
                ->where('eval_type_id', $eval_type)
                ->get();
        }
        return is_object($ret) && isset($ret[0]) ? $ret[0]->percent_eval : 0;
    }
}
