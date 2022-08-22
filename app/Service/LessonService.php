<?php

namespace App\Service;

use App\Http\Requests\User\LessonReserveRequest;
use App\Models\Lesson;
use App\Models\LessonChangeHistory;
use App\Models\LessonDiscussArea;
use App\Models\LessonLoad;
use App\Models\LessonRequestSchedule;
use App\Models\Senpai;
use App\Models\TransferApplication;
use App\Models\User;
use App\Models\Area;
use App\Models\LessonClass;
use App\Models\LessonArea;
use App\Models\LessonCondition;
use App\Models\LessonSchedule;
use App\Models\LessonRequest;
use App\Models\Evalution;
use App\Models\EvalutionType;
use App\Models\Favourite;
use App\Service\SenpaiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;
use App\Service\UserService;
use App\Service\BlockService;
use App\Service\AreaService;
use App\Service\SettingService;
use function PHPUnit\Framework\isEmpty;

class LessonService
{

    public static function doCancelStopLessonSearch($condition=[]) {
        $lessons = Lesson::select('lessons.*')
            ->where('lesson_stop', config('const.lesson_stop_code.stop_lesson'))
            ->whereNotNull('lesson_stop_cancel_reverse_at');

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $lessons->orderByDesc('lesson_stopped_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $lessons->orderBy('lesson_stopped_at');
            }
        }

        return $lessons;
    }

    public static function doStopLessonSearch($condition=[]) {
        $lessons = Lesson::select('lessons.*')
            ->where('lesson_stop', config('const.lesson_stop_code.stop_lesson'))
            ->whereNull('lesson_stop_cancel_reverse_at');
        $lessons->leftJoin('users', function ($join) {
            $join->on('lessons.lesson_senpai_id', 'users.id');
        });

        // カテゴリー
        if (isset($condition['category']) && $condition['category']) {
            $lessons->where("lesson_class_id", $condition['category']);
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $lessons->whereIn('lesson_id', function($query) use($condition) {
                $query->select('la_lesson_id')
                    ->from('lesson_areas')
                    ->where('la_deep2_id', $condition['area']);
            });
        }

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $lessons->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $lessons->whereDate('lesson_stopped_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $lessons->whereDate('lesson_stopped_at', '<=', $condition['to_date']);
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $lessons->whereRaw("name LIKE ?", '%'.$condition['nickname'].'%');
        }

        // ぴろしきまるのみ表示
        if(isset($condition['punishment']) && $condition['punishment']) {
            $lessons->whereIn('lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where(function($q) {
                        $q->where('punishment_cnt', '>', 0);
                        $q->orWhere('caution_cnt', '>', 0);
                    });
            });
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $lessons->orderByDesc('lesson_stopped_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $lessons->orderBy('lesson_stopped_at');
            }
        }

        return $lessons;
    }

    // レッスン履歴=>出品レッスン
    public static function doLessonHistorySearch($condition=[]) {
        $lessons = LessonRequestSchedule::selectRaw('lesson_request_schedules.*, lesson_request_schedules.updated_at as history_updated_at');
        $lessons->leftJoin('lesson_requests', function ($join) {
            $join->on('lesson_request_schedules.lrs_lr_id', 'lesson_requests.lr_id');
        });
        $lessons->leftJoin('lessons', function ($join) {
            $join->on('lesson_requests.lr_lesson_id', 'lessons.lesson_id');
        });

        // ステータス
        if (isset($condition['status']) && $condition['status']) {
            switch ($condition['status']) {
                case config('const.lesson_history_status_code.complete'):
                    $lessons->where('lrs_state', config('const.schedule_state.complete'));
                    break;
                case config('const.lesson_history_status_code.reserve_suggest'):
                    $lessons->where(function($q) {
                        $q->where('lrs_state', config('const.schedule_state.confirm'));
                        $q->orWhere('lrs_state', config('const.schedule_state.request'));
                    });
                    break;
                case config('const.lesson_history_status_code.reserve_cancel_complete'):
                    $lessons->where(function($q) {
                        $q->where('lrs_state', config('const.schedule_state.cancel_senpai'));
                        $q->orWhere('lrs_state', config('const.schedule_state.cancel_kouhai'));
                    });
                    break;
                case config('const.lesson_history_status_code.reserve_complete'):
                    $lessons->where('lr_type', config('const.request_type.reserve'));
                    $lessons->where('lrs_state', config('const.schedule_state.reserve'));
                    break;
                case config('const.lesson_history_status_code.attendance_complete'):
                    $lessons->where('lr_type', config('const.request_type.attend'));
                    $lessons->where('lrs_state', config('const.schedule_state.reserve'));
                    break;
                case config('const.lesson_history_status_code.reserve_canceled'):
                    $lessons->where('lr_type', config('const.request_type.reserve'));
                    $lessons->where('lrs_state', config('const.schedule_state.cancel_system'));
                    break;
                case config('const.lesson_history_status_code.attendance_canceled'):
                    $lessons->where('lr_type', config('const.request_type.attend'));
                    $lessons->where('lrs_state', config('const.schedule_state.cancel_system'));
                    break;
                default:
                    break;
            }
        }

        $lessons->where('lrs_state', '!=', config('const.schedule_state.start'));

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $lessons->whereIn('lr_lesson_id', function($query) use($condition) {
                $query->select('la_lesson_id')
                    ->from('lesson_areas')
                    ->where('la_deep2_id', $condition['area']);
            });
        }

        // ID（先輩）
        if (isset($condition['senpai_user_no']) && $condition['senpai_user_no']) {
            $lessons->whereIn('lrs_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['senpai_user_no'].'%');
            });
        }
        // ニックネーム（先輩）
        if (isset($condition['senpai_nickname']) && $condition['senpai_nickname']) {
            $lessons->whereIn('lrs_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['senpai_nickname'].'%');
            });
        }

        // ID（後輩）
        if (isset($condition['kouhai_user_no']) && $condition['kouhai_user_no']) {
            $lessons->whereIn('lrs_kouhai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['kouhai_user_no'].'%');
            });
        }
        // ニックネーム（後輩）
        if (isset($condition['kouhai_nickname']) && $condition['kouhai_nickname']) {
            $lessons->whereIn('lrs_kouhai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['kouhai_nickname'].'%');
            });
        }

        // 期間 (リクエストが送られた時間)
        if(isset($condition['from_date']) && $condition['from_date']) {
            $lessons->whereDate('lesson_request_schedules.updated_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $lessons->whereDate('lesson_request_schedules.updated_at', '<=', $condition['to_date']);
        }

        // ぴろしきまるのみ表示
        if(isset($condition['punishment']) && $condition['punishment']) {
            $lessons->whereIn('lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where(function($q) {
                        $q->where('punishment_cnt', '>', 0);
                        $q->orWhere('caution_cnt', '>', 0);
                    });
            });
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $lessons->orderByDesc('history_updated_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $lessons->orderBy('history_updated_at');
            }
        }

        return $lessons;
    }

    // lesson
    public static function doUpdateLessonLoad($lesson_id, $state){
        if($lesson_id <= 0)
            return false;
        $obj = LessonLoad::where('lesson_id', $lesson_id)->first();
        if(!$obj)
            return  LessonLoad::create(array('lesson_id'=>$lesson_id, 'state'=>$state));
        $obj->state = $state;
        return  $obj->save();
    }
    public static function doCreateLesson($data)
    {
        if(! Auth::User()->user_is_senpai ) return false;
        $data_query = array();
        $data_query['lesson_senpai_id'] = Auth::User()->id;
        $data_query['lesson_type'] = $data['lesson_type'];
        $data_query['lesson_class_id'] = intval($data['lesson_class_id']);
        $data_query['lesson_title'] = trim($data['lesson_title']);
        $data_query['lesson_image'] = serialize($data['lesson_image']);
        $data_query['lesson_wish_sex'] = intval($data['lesson_wish_sex']) ;
        $data_query['lesson_wish_minage'] = intval($data['lesson_wish_minage']);
        $data_query['lesson_wish_maxage'] = intval($data['lesson_wish_maxage']);
        $data_query['lesson_min_hours'] = intval($data['lesson_min_hours']);
        $data_query['lesson_30min_fees'] = intval($data['lesson_30min_fees']);
        $data_query['lesson_person_num'] = intval($data['lesson_person_num']);

        if(isset($data['lesson_able_with_man']))
            $data_query['lesson_able_with_man'] = intval($data['lesson_able_with_man']);
        else
            $data_query['lesson_able_with_man'] = 0;

        if(isset($data['lesson_accept_without_map']))
            $data_query['lesson_accept_without_map'] = intval($data['lesson_accept_without_map']);
        else
            $data_query['lesson_accept_without_map'] = 0;

        $data_query['lesson_latitude'] =  isset($data['lesson_latitude']) ? floatval($data['lesson_latitude']) : NULL;
        $data_query['lesson_longitude'] =  isset($data['lesson_longitude']) ? floatval($data['lesson_longitude']) : NULL;
        $data_query['lesson_map_address'] =  isset($data['lesson_map_address']) &&trim($data['lesson_map_address']) != "" ? trim($data['lesson_map_address']) : '';

        $data_query['lesson_pos_detail'] = isset($data['lesson_pos_detail']) ? trim($data['lesson_pos_detail']) : NULL;
        $data_query['lesson_discuss_pos_detail'] = isset($data['lesson_discuss_pos_detail']) ? trim($data['lesson_discuss_pos_detail']) : NULL;

        if(isset($data['lesson_able_discuss_pos']))
            $data_query['lesson_able_discuss_pos'] = intval($data['lesson_able_discuss_pos']);
        else
            $data_query['lesson_able_discuss_pos'] = 0;

        $data_query['lesson_service_details'] = trim($data['lesson_service_details']) != "" ? trim($data['lesson_service_details']) : NULL;
        $data_query['lesson_other_details'] = trim($data['lesson_other_details']) != "" ? trim($data['lesson_other_details']) : NULL;
        $data_query['lesson_buy_and_attentions'] = trim($data['lesson_buy_and_attentions']) != "" ? trim($data['lesson_buy_and_attentions']) : NULL;

        if(isset($data['lesson_accept_attend_request']))
            $data_query['lesson_accept_attend_request'] = intval($data['lesson_accept_attend_request']);
        else
            $data_query['lesson_accept_attend_request'] = 0;

        for ( $i = 1; $i <= config('const.lesson_cond_cnt') ; $i++) {
            if(isset($data['lesson_cond_'.$i]))
                $data_query['lesson_cond_'.$i] = intval($data['lesson_cond_'.$i]);
            else
                $data_query['lesson_cond_'.$i] = 0;
        }

        $data_query['lesson_state'] = intval($data['lesson_state']);
        $data_query['lesson_coupon'] = isset($data['lesson_coupon']) ?  intval($data['lesson_coupon']) : 0;

        if ($data['lesson_state'] == config('const.lesson_state.check')) { // 申請中
            $obj_lesson = Lesson::create($data_query);
            if(self::saveLessonChangeHistory($obj_lesson, true)) {
                return $obj_lesson;
            }
        } else {
            return  Lesson::create($data_query);
        }
    }
    public static function doUpdateLesson($conditon, $data){
        if( !Auth::User()->user_is_senpai ) return false;
        $lesson_model = Lesson::orderBy('lesson_id');
        foreach ($conditon as $k=>$v){
            $lesson_model = $lesson_model->where($k, $v);
        }
        $lesson_model = $lesson_model->first();
        if(!$lesson_model) return false;
        foreach ($data as $k1=>$v1){
            $lesson_model->$k1 = $v1;
        }
        if ($data['lesson_state'] == config('const.lesson_state.check')) { // 申請中
            $origin_staff_info = $lesson_model->getOriginal();
            $lesson_model->save();
            return self::saveLessonChangeHistory($lesson_model, false, $origin_staff_info);
        } else {
            return  $lesson_model->save();
        }
    }

    public static function increaseLessonClick($lesson_id){
        $lesson_model = Lesson::where('lesson_id', $lesson_id)->first();

        if ( Auth::guard('web')->check() ) {
            if($lesson_model->lesson_senpai_id == Auth::user()->id)
                return;
        }

        $lesson_model->lesson_click = $lesson_model->lesson_click + 1;
        return $lesson_model->save();
    }

    public static function doCreateLessonSchedule($data){
        $senpai_id = $data['ls_senpai_id'];
        $date = $data['ls_date'];
        $start_time = $data['ls_start_time'];
        $end_time = $data['ls_end_time'];
        $case1_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)
            ->where('ls_date', $date)->where('ls_end_time', $start_time)->first();
        $case2_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)
            ->where('ls_date', $date)->where('ls_start_time', $end_time)->first();

        if(!is_null($case1_obj) && !is_null($case2_obj)){
            $case1_obj->ls_end_time = $case2_obj->ls_end_time;
            $case2_obj->delete();
            return $case1_obj->save();
        }else if(!is_null($case1_obj)){
            $case1_obj->ls_end_time = $end_time;
            return $case1_obj->save();
        }else if(!is_null($case2_obj)){
            $case2_obj->ls_start_time = $start_time;
            return $case1_obj->save();
        }else{
            return LessonSchedule::create($data);
        }
    }
    public static function doCancelLessonSchedule($data){
        $senpai_id = $data['ls_senpai_id'];
        $date = $data['ls_date'];
        $start_time = $data['ls_start_time'];
        $end_time = $data['ls_end_time'];
        $case0_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)->where('ls_date', $date)->where('ls_start_time', '>=',$start_time)->where('ls_end_time', '<=', $end_time)->first();
        $case1_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)->where('ls_date', $date)->where('ls_start_time', $start_time)->where('ls_end_time', '>', $end_time)->first();
        $case2_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)->where('ls_date', $date)->where('ls_start_time', '<', $start_time)->where('ls_end_time', $end_time)->first();
        $case3_obj = LessonSchedule::where('ls_senpai_id', $senpai_id)->where('ls_date', $date)->where('ls_start_time', '<', $start_time)->where('ls_end_time', '>', $end_time)->first();
        $rs = true;
        if(!is_null($case0_obj)){
            if(!$case0_obj->delete()) $rs = false;
            return $rs;
        }else if(!is_null($case1_obj)){
            $case1_obj->ls_start_time = $end_time;
            if(!$case1_obj->save()) $rs = false;
            return $rs;
        }else if(!is_null($case2_obj)){
            $case2_obj->ls_end_time = $start_time;
            if(!$case2_obj->save()) $rs = false;
            return $rs;
        }else  if(!is_null($case3_obj)){
            $r = LessonSchedule::create(array('ls_senpai_id'=>$senpai_id, 'ls_date'=>$date, 'ls_start_time'=>$end_time, 'ls_end_time'=>$case3_obj->ls_end_time));
            if(!$r) $rs = false;
            $case3_obj->ls_end_time = $start_time;
            if(!$case3_obj->save()) $rs = false;
            return $rs;
        }else{
            return $rs;
        }
    }
    public static function doDeleteLessonScheduleByMonth($year, $month){
        $first_day = date('Y-m-d', strtotime(date($year.'-'.$month).' first day of this month'));
        $end_day = date('Y-m-d', strtotime(date($year.'-'.$month).' last day of this month'));
        $schedule_model = LessonSchedule::whereBetween('ls_date', [$first_day, $end_day]);
        if($schedule_model->count() <= 0) return true;
        return $schedule_model->delete();
    }
    public static function doDeleteLesson($data){
        $lesson_id = intval($data['lesson_id']);
        $lesson_model = Lesson::where('lesson_id', $lesson_id);
        if($lesson_model->count() > 0)
            return $lesson_model->delete();
        else
            return false;
    }
    public static function doLessonSearch($search_params)
    {
        if (isset($search_params['order_type']) && !empty($search_params['order_type'])) {
            if ($search_params['order_type'] == 1) { // 人気順
                $lessons = Lesson::withCount(['evalution as learner_evalution_count' => function (Builder $query) {
                    $query->where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                        ->where('eval_val', EvalutionService::YES_EVAL);
                }])->orderBy('learner_evalution_count', 'DESC');
            } elseif ($search_params['order_type'] == 2) {  //単価の安い順
                $lessons = Lesson::orderBy('lesson_30min_fees');
            } elseif ($search_params['order_type'] == 3) {  //単価の高い順
                $lessons = Lesson::orderByDesc('lesson_30min_fees');
            } elseif ($search_params['order_type'] == 4) {  //出勤日の多い順
                $lessons = Lesson::withCount('lesson_schedule')
                    ->orderBy('lesson_schedule_count', 'DESC');
            } elseif ($search_params['order_type'] == 5) {  //取引件数順
                ////////?????/////////////////////
                $lessons = Lesson::orderByDesc('updated_at');
            } elseif ($search_params['order_type'] == 6) {  //新着順
                $lessons = Lesson::orderByDesc('updated_at');
            } elseif ($search_params['order_type'] == 7) {  //お気に入りの多い順
                $lessons = Lesson::withCount(['favourite as lesson_favourite_count' => function (Builder $query) {
                    $query->where('fav_type', config('const.fav_type.lesson'));
                }])->orderBy('lesson_favourite_count', 'DESC');
            }
        } else {
            $lessons = Lesson::orderByDesc('updated_at');
        }

        $lesson_state = config('const.lesson_state.public');
        if (isset($search_params['lesson_state']) && !empty($search_params['lesson_state'])) {
            $lesson_state = $search_params['lesson_state'];
        }
        $lessons->where('lesson_state', $lesson_state);
        if (isset($search_params['class_id']) && !empty($search_params['class_id'])) {
            $lessons->where('lesson_class_id', $search_params['class_id']);
        }

        if (isset($search_params['title']) && !empty($search_params['title'])) {
            $lessons->where('lesson_title', 'like', '%' . $search_params['title'] . '%');
        }

        if (isset($search_params['date']) && !empty($search_params['date'])) {
            $date = $search_params['date'];
            if (isset($search_params['start_hour'])) {
                $start_hour = $search_params['start_hour'];
            }
            if (isset($search_params['start_minute'])) {
                $start_minute = $search_params['start_minute'];
            }
            if (isset($search_params['end_hour'])) {
                $end_hour = $search_params['end_hour'];
            }
            if (isset($search_params['end_minute'])) {
                $end_minute = $search_params['end_minute'];
            }
            $start_time = str_pad($start_hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($start_minute, 2, "0", STR_PAD_LEFT) . ":00";
            $end_time = str_pad($end_hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($end_minute, 2, "0", STR_PAD_LEFT) . ":00";

            $lessons = $lessons->whereHas('lesson_schedule', function ($query) use ($date, $start_time, $end_time) {
                $query->where('ls_date', $date)
                    ->where('ls_start_time', '>=', $start_time)
                    ->where('ls_end_time', '<=', $end_time);

            });
        }
        if (isset($search_params['start_interval']) && !empty($search_params['start_interval'])) {
            $lessons->where('lesson_min_hours', '>=', $search_params['start_interval']);
        }
        if (isset($search_params['end_interval']) && !empty($search_params['end_interval'])) {
            $lessons->where('lesson_min_hours', '<=', $search_params['end_interval']);
        }

        if (isset($search_params['start_fee']) && !empty($search_params['start_fee'])) {
            $lessons->where('lesson_30min_fees', '>=', $search_params['start_fee']);
        }
        if (isset($search_params['end_fee']) && !empty($search_params['end_fee'])) {
            $lessons->where('lesson_30min_fees', '<=', $search_params['end_fee']);
        }

        if (isset($search_params['sex']) && !empty($search_params['sex'])) {
            $lessons->where(function($q) use ($search_params) {
                $q->where('lesson_wish_sex', $search_params['sex']);
                $q->orWhere('lesson_wish_sex', 0);
            });
        }

        if (isset($search_params['start_age']) && !empty($search_params['start_age']) && isset($search_params['end_age']) && !empty($search_params['end_age'])) {
            $lessons->where(function ($query_origin) use ($search_params) {
                $query_origin->where(function ($query) use ($search_params) {
                    $query->where('lesson_wish_minage', '<=', $search_params['start_age'])
                        ->where('lesson_wish_minage','>=', $search_params['start_age']);
                });
                $query_origin->orWhere(function ($query) use ($search_params) {
                    $query->where('lesson_wish_minage', '<=', $search_params['end_age'])
                        ->where('lesson_wish_maxage','>=', $search_params['end_age']);
                });
                $query_origin->orWhere(function ($query) use ($search_params) {
                    $query->where('lesson_wish_minage', 0)
                        ->where('lesson_wish_maxage', 0);
                });
            });
        } else if (isset($search_params['start_age']) && !empty($search_params['start_age'])) {
            $lessons->where(function ($query) use ($search_params) {
                $query->where(function ($subquery) use ($search_params) {
                    $subquery->where('lesson_wish_minage', '<=', $search_params['start_age'])
                        ->where('lesson_wish_maxage','>=', $search_params['start_age']);
                });
                $query->orWhere(function ($subquery) use ($search_params) {
                    $subquery->where('lesson_wish_minage', 0)
                        ->where('lesson_wish_maxage', 0);
                });
            });
        } else if (isset($search_params['end_age']) && !empty($search_params['end_age'])) {
            $lessons->where(function ($query) use ($search_params) {
                $query->where(function ($subquery) use ($search_params) {
                    $subquery->where('lesson_wish_minage', '<=', $search_params['end_age'])
                        ->where('lesson_wish_maxage','>=', $search_params['end_age']);
                });
                $query->orWhere(function ($subquery) use ($search_params) {
                    $subquery->where('lesson_wish_minage', 0)
                        ->where('lesson_wish_maxage', 0);
                });
            });
        }

        for ($i = 1; $i <= config('const.lesson_cond_cnt'); $i++) {
            if (isset($search_params['condition_' . $i]) && $search_params['condition_' . $i] == 1) {
                $lessons->where('lesson_cond_' . $i, $search_params['condition_' . $i]);
            }
        }

        /*if (isset($search_params['is_attend'])) {
            $lessons->where('lesson_accept_attend_request', $search_params['is_attend']);
        }*/

        if (isset($search_params['area_id_arr']) && !empty($search_params['area_id_arr'])) {
            $area_ids = $search_params['area_id_arr'];
            $lessons = $lessons->whereHas('lesson_area', function ($query) use ($area_ids) {
                $query
                    ->whereIn('la_deep3_id', $area_ids);
            });

        }else if(isset($search_params['province_id']) && !empty($search_params['province_id'])){
            $province_id = $search_params['province_id'];
            $lessons = $lessons->whereHas('lesson_area', function ($query) use ($province_id) {
                $query
                    ->where('la_deep2_id', $province_id);
            });
        }

        if (isset($search_params['except_user_id']) && $search_params['except_user_id']) {
            $lessons->where('lesson_senpai_id', '!=', $search_params['except_user_id']);
        }
        $lessons->with('lesson_class')
            ->with('senpai');
        return $lessons;
    }

    public static function doLessonExminationSearch($search_params)
    {
        $lessons = Lesson::orderBy('updated_at');

        $lesson_state = config('const.lesson_state.public');
        if (isset($search_params['lesson_state']) && !empty($search_params['lesson_state'])) {
            $lesson_state = $search_params['lesson_state'];
        }
        $lessons->where('lesson_state', $lesson_state);

        if (isset($search_params['sort_type']) && $search_params['sort_type']) {
            $lessons->where('lesson_class_id', $search_params['sort_type']);
        }

        if (isset($search_params['onof-line']) && $search_params['onof-line']) {
            if ($search_params['onof-line'] == config('const.lesson_op_type_code.new')) { // 新規
                $lessons->whereNotIn('lesson_id', function($query) {
                    $query->select('lesson_id')
                        ->from('lesson_change_history')
                        ->where('origin_data', 'like', '%\"lesson_state\":'.config('const.lesson_state.public').'%');
                });
            } else if($search_params['onof-line'] == config('const.lesson_op_type_code.change')) { // 差し替え
                $lessons->whereIn('lesson_id', function($query) {
                    $query->select('lesson_id')
                        ->from('lesson_change_history')
                        ->where('origin_data', 'like', '%\"lesson_state\":'.config('const.lesson_state.public').'%');
                });
            }
        }

        $lessons->with('lesson_class')
            ->with('senpai');
        return $lessons;
    }

    public static function getSenpaiIdFromLesson($lesson_id)
    {
        $lesson_info = Lesson::where('lesson_id', $lesson_id)
            ->select('lesson_senpai_id')
            ->first();

        if(is_null($lesson_info)){
            return 0;
        }

        return $lesson_info['lesson_senpai_id'];
    }


    public static function getLessonCount($state)
    {
        return Lesson::where('lesson_state', $state)
            ->count();
    }

    public static function getLessonConditions()
    {
        return LessonCondition::orderBy('lc_id')
            ->get();
    }

    public static function isSelfConfCondition($lesson_id)
    {
        $self_conf_lesson =  Lesson::where('lesson_id', $lesson_id)
            ->where('lesson_cond_6', '!=', 0)
            ->first();
        if(is_null($self_conf_lesson))
            return true;
        else{
            $user_id = Auth::user()->id;
            $user_info = UserService::getUserByID($user_id);
            if(!empty($user_info['userConfirm'])){
                if($user_info['userConfirm']['pc_state'] == config('const.pers_conf.confirmed'))
                    return true;
                else
                    return false;
            }else
                return false;

        }
    }

    public static function getLessonCondNames($lesson_obj)
    {
        $lesson_conds = self::getLessonConditions();

        if($lesson_obj->count() && $lesson_conds->count()){
            $result = array();
            for($i = 1; $i <= config('const.lesson_cond_cnt'); $i++){
                if($lesson_obj['lesson_cond_'.$i] == 1)
                    $result[$i] = $lesson_conds[$i - 1]['lc_name'];
            }
            return $result;

        }else{
            return array();
        }
    }

    public static function getLessonFirstImage($lesson_obj)
    {
        $images = $lesson_obj['lesson_image'];
        if(empty($lesson_obj) && empty($images)){
            return NULL;
        }

        $imageList = CommonService::unserializeData($images);
        if (count($imageList))
            return $imageList[0];

        return null;
    }

    public static function getLessonListBySenpai($senpai_id)
    {
        return Lesson::orderBy('lesson_id', 'asc')
            ->where('lesson_senpai_id', $senpai_id)
            ->with('senpai')
            ->with('lesson_class')
            ->get();
    }

    public static function getLessonInfo($lesson_id)
    {
        $lesson_obj = Lesson::where('lesson_id', $lesson_id)
            ->with('lesson_class')
            ->with('senpai')
            ->first();
        return $lesson_obj;
    }

    /**
     * @param $lesson_id
     * @return string
     * yes:
     * self_lesson
     * no_self_conf
     * self_block
     * other_block
     */
    public static function getLessonValidCode($lesson_id){
        if(!Auth::guard('web')->check())
            return 'yes';
        $user_id = Auth::user()->id;
        $senpai_id = self::getSenpaiIdFromLesson($lesson_id);
        if($user_id == $senpai_id)
            return 'self_lesson';
        if(!self::isSelfConfCondition($lesson_id)) {
            return 'no_self_conf';
        }

        $block = BlockService::getWhoseBlock($senpai_id);
        if ($block == 'self_block' || $block == 'other_block')
            return $block;
        return 'yes';
    }

    public static function getLessonDetailList($tab){
        $lessons_obj = Lesson::with('lesson_class')->withCount(['favourite as fav_count' => function (Builder $query){
            $query->where('fav_type', config('const.fav_type.lesson'));
        }])->with('senpai.area')->orderBy('updated_at', 'desc');
        if($tab == config('const.lesson_tab.public')){
            $lessons_obj->where('lesson_state',config('const.lesson_state.public'));
        }
        if($tab == config('const.lesson_tab.checking')){
            $lessons_obj->whereIn('lesson_state', [config('const.lesson_state.check'),config('const.lesson_state.reject')]);
        }
        if($tab == config('const.lesson_tab.draft')){
            $states = array(config('const.lesson_state.draft'), config('const.lesson_state.private'));
            $lessons_obj->whereIn('lesson_state', $states);
        }
        return $lessons_obj;
    }

    //lesson_schedule
    public static function doCreateRequestSchedule($data)
    {
        $data_query = array();
        $data_query['lrs_lr_id'] = $data['lr_id'];
        $data_query['lrs_date'] = $data['date'];
        $data_query['lrs_start_time'] = $data['start_time'];
        $data_query['lrs_end_time'] = $data['end_time'];
        $data_query['lrs_amount'] = CommonService::getLessonAmount($data['30min_fees'], $data['start_time'], $data['end_time']);
        $data_query['lrs_fee_type'] = config('const.fee_type.c');
        $data_query['lrs_kouhai_id'] = isset($data['lrs_kouhai_id']) ? $data['lrs_kouhai_id'] : 0;
        $data_query['lrs_senpai_id'] = isset($data['lrs_senpai_id']) ? $data['lrs_senpai_id'] : 0;

        $request_obj = self::getLessonRequestInfo($data['lr_id']);
        if(is_null($request_obj))
            $data_query['lrs_fee'] = 0;
        else{
            $senpai_id = $request_obj['lesson']['lesson_senpai_id'];
            $kouhai_id = $request_obj['lr_user_id'];
            $data_query['lrs_fee'] = LessonService::getLessonFee($kouhai_id, $senpai_id, $data_query['lrs_amount']);
        }

        $data_query['lrs_service_fee'] = CommonService::getServiceFee($data_query['lrs_amount']);
        if(!empty($data['traffic_fee'])){
            $data_query['lrs_traffic_fee'] = $data['traffic_fee'];
        }
        $data_query['lrs_state'] = config('const.schedule_state.request');
        $data_query['lrs_request_date'] = date('Y-m-d H:i:s');
        if(!empty($data['no_confirm'])){
            $data_query['lrs_no_confirm'] = $data['no_confirm'];
            if ( $data['no_confirm'] == config('const.no_confirm.old_request') ) {
                $data_query['lrs_old_schedule'] = $data['old_schedule'];
            }
        }

        if($obj_lessonReqSche = LessonRequestSchedule::create($data_query))
        {
            return $obj_lessonReqSche;
        } else {
            return null;
        }
    }

    public static function doUpdateRequestSchedule($data=array(), $condition=array())
    {
        $obj = LessonRequestSchedule::orderBy('lrs_id');
        foreach ($condition as $k=>$v){
            $obj = $obj->where($k, $v);
        }
        $update_data = array();
        foreach ($data as $k=>$v){
            $update_data[$k] = $v;
        }
        $result=$obj->update($update_data);

        if($result)
        {
            return $result;
        } else {
            return false;
        }
    }

    public static function doConvertRequestScheduleToRequestStatus($lr_id)
    {
        return LessonRequestSchedule::where('lrs_lr_id', $lr_id)
            ->where('lrs_state', config('const.schedule_state.confirm'))
            ->update([
                'lrs_state' => config('const.schedule_state.request')
            ]);
    }

    public static function doUpdateRequest($data=array(), $condition=array())
    {
        $obj = LessonRequest::orderBy('lr_id');
        foreach ($condition as $k=>$v){
            $obj = $obj->where($k, $v);
        }
        $update_data = array();
        foreach ($data as $k=>$v){
            $update_data[$k] = $v;
        }
        $result=$obj->update($update_data);
        if($result)
        {
            return $result;
        } else {
            return false;
        }
    }

    public static function getLessonSchedules($lesson_id)
    {
        $lesson = Lesson::where('lesson_id', $lesson_id)
            ->first();

        if (!$lesson)
            return $lesson;

        $result = LessonSchedule::where('ls_senpai_id', $lesson['lesson_senpai_id'])
            ->get();

        return $result;
    }

    public static function getLessonFee($kouhai_id, $senpai_id, $amount) {
        $fee_type_a_amount = SettingService::getSetting('fee_type_a_amount', 'int');
        $fee_type_b_percent = SettingService::getSetting('fee_type_b_percent', 'int');
        $fee_type_c_percent = SettingService::getSetting('fee_type_c_percent', 'int');
        $latest_schedule_cnt =  LessonRequestSchedule::whereHas('lesson_request', function ($query) use ($kouhai_id, $senpai_id) {
            $query
                ->whereHas('lesson', function ($query) use ($senpai_id) {
                    $query
                        ->where('lesson_senpai_id', $senpai_id);
                })->where('lr_user_id', $kouhai_id);
        })->where('lrs_state', '>=', config('const.schedule_state.start'))
            ->Where('lrs_state','<=', config('const.schedule_state.complete'))
            ->Where('lrs_date','>=', date('Y-m-d', strtotime("-14 days")))
            ->count();
        if($latest_schedule_cnt > 0){
            $lesson_fee =  round($amount * $fee_type_b_percent / 100);
        }else{
            $lesson_fee =  round($amount * $fee_type_c_percent / 100);
        }
        return $lesson_fee > $fee_type_a_amount ? $lesson_fee : $fee_type_a_amount;
    }

    public static function getLessonSchedulesOfDay($lesson_id, $date)
    {
        $lesson = Lesson::where('lesson_id', $lesson_id)
            ->first();

        if (!$lesson)
            return $lesson;

        $result = LessonSchedule::where('ls_senpai_id', $lesson['lesson_senpai_id'])
            ->where('ls_date', $date)
            ->get();

        return $result;
    }

    public static function isTrafficFee($req_schedule_id) {
        $reserve_schedule_count = LessonRequestSchedule::wherehas('lesson_request', function ($query){
            $query->where('lr_type', config('const.request_type.reserve'));
        })->where('lrs_id', $req_schedule_id)
            ->count();

        if($reserve_schedule_count > 0)
            return false;

        $traffic_schedule_count = LessonRequestSchedule::wherehas('lesson_request', function ($query){
            $query->where('lr_type', config('const.request_type.attend'))
                ->wherehas('lesson', function ($query){
                $query->where('lesson_cond_8', 1);
            });
        })->where('lrs_id', $req_schedule_id)
            ->count();

        if($traffic_schedule_count > 0)
            return false;
        else
            return true;
    }

    //lesson request
    public static function doCreateRequest($data)
    {
        $data_query = array();
        $data_query['lr_lesson_id'] = $data['lesson_id'];
        $data_query['lr_user_id'] = $data['user_id'];
        if(isset($data['hope_maxtime']) && !empty($data['hope_maxtime'])){
            $data_query['lr_type'] = config('const.request_type.attend');
            $data_query['lr_hope_mintime'] = $data['hope_mintime'];
            $data_query['lr_hope_maxtime'] = $data['hope_maxtime'];
        }else{
            $data_query['lr_type'] = config('const.request_type.reserve');
        }
        $data_query['lr_man_num'] = $data['man_num'];
        $data_query['lr_woman_num'] = $data['woman_num'];
        $data_query['lr_area_id'] = $data['area_id'];
        $data_query['lr_address'] = $data['address'];
        $data_query['lr_address_detail'] = $data['address_detail'];
        $data_query['lr_hope_type'] = $data['hope_type'];
        $data_query['lr_target_reserve'] = isset($data['target_reserve']) ? $data['target_reserve'] : 0;
        $data_query['lr_pos_discuss'] = isset($data['pos_discuss']) ? $data['pos_discuss'] : 0;
        $data_query['lr_until_confirm'] = $data['until_confirm'];
        $data_query['lr_state'] = config('const.req_state.request');
        $data_query['lr_request_date'] = date('Y-m-d H:i:s');
        if($obj_lesson_req = LessonRequest::create($data_query))
        {
            return $obj_lesson_req;
        } else {
            return null;
        }

    }

    public static function getLessonRequestInfo($request_id)
    {
        $req_info = LessonRequest::where('lr_id', $request_id)
            ->with('lesson')
            ->first();

        return $req_info;
    }
    public static function getRequestLstByKouhaiId($user_id, $state)
    {
        if($state == config('const.quit_state')){
            return LessonRequest::where('lr_user_id', $user_id)
                ->where('lr_state', '<', config('const.req_state.complete'))
                ->get();
        }else{
            return LessonRequest::where('lr_user_id', $user_id)
                ->where('lr_state', $state)
                ->get();
        }
    }

    public static function getRequestLstBySenpaiId($senpai_id, $state)
    {
        if($state == config('const.quit_state')){
            return LessonRequest::whereHas('lesson', function ($query) use ($senpai_id) {
                $query->where('lesson_senpai_id', $senpai_id);})
                ->where('lr_state', '<', config('const.req_state.complete'))
                ->get();
        }else{
            return LessonRequest::whereHas('lesson', function ($query) use ($senpai_id) {
                $query->where('lesson_senpai_id', $senpai_id);})
                ->where('lr_state', $state)
                ->get();
        }

    }

    public static function getLessonReqAmountByRequestId($lr_id, $state = 0)
    {
        $schedule = LessonRequestSchedule::where('lrs_lr_id', $lr_id)
            ->where('lrs_state', $state)
            ->get()
            ->toArray();
        if(!is_array($schedule)){
            return false;
        }
        $result = 0;
        foreach ($schedule as $key => $value){
            $result += $value['lrs_amount'];
        }
        return $result;

    }

    // get Request by kouhai id
    public static function getRequestInfosByKouhaiId($user_id, $type, $order_type)
    {
        $obj_requests = self::getObjRequestsByKouhaiId($user_id, $type);

        $obj_request_infos = $obj_requests
            ->with('lesson', function ($query) {
                $query
                    ->with('lesson_class')
                    ->with('senpai');
            })
            ->with('schedule_request');

        $obj_ordered_lesson_req_list = $obj_request_infos;

        if ( $type == 0 ) {
            switch ($order_type) {
                case 0: {           // 新着順
                    $obj_ordered_lesson_req_list = $obj_request_infos->orderByDesc('updated_at');
                }
                    break;
                case 1: {           // 単価の高い順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->join('lessons', 'lessons.lesson_id', '=', 'lesson_requests.lr_lesson_id')
                        ->orderByDesc('lessons.lesson_30min_fees');
                }
                    break;
                case 2: {           // 支払総額の高い順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderByDesc('lr_amount');
                }
                    break;
                case 3: {           // 残り時間の多い順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderBy('lr_until_confirm');
                }
                    break;
                case 4: {           // 残り時間の少ない順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderByDesc('lr_until_confirm');
                }
                    break;
                case 5: {           // 購入期限の近い順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderByDesc('lr_until_confirm');
                }
                    break;
                default:
                    $obj_ordered_lesson_req_list = $obj_request_infos;
                    break;
            }
        } elseif ( $type == 1 ) {
            switch ($order_type) {
                case 0: {           // 人気順
                    $obj_ordered_lesson_req_list = $obj_request_infos;
                }
                    break;
                case 1: {           // 新着順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderByDesc('updated_at');
                }
                    break;
                case 2: {           // 古い順
                    $obj_ordered_lesson_req_list = $obj_request_infos
                        ->orderBy('updated_at');
                }
                    break;
                default:
                    $obj_ordered_lesson_req_list = $obj_request_infos;
                    break;
            }
        }

        return $obj_ordered_lesson_req_list;
    }

    public static function getCntRequestByKouhaiId($user_id, $type) {
        return self::getObjRequestsByKouhaiId($user_id, $type)->count();
    }

    public static function getObjRequestsByKouhaiId($user_id, $type) {
        /*return LessonRequest::where('lr_user_id', $user_id)
            ->where('lr_type', $type)
            ->whereHas('lesson_request_schedule', function ($query) {
                $query->where('lrs_state', '<=', config('const.schedule_state.confirm'));
            });*/

        return LessonRequest::where('lr_user_id', $user_id)
            ->where('lr_type', $type);
    }

    // schedule
    public static function doDeleteSchedulesByReqId($req_id) {
        $req_info = self::getRequestAllInfo($req_id);

        foreach ( $req_info['lesson_request_schedule'] as $key => $value ) {
            LessonRequestSchedule::destroy($value['lrs_id']);
        }
    }

    public static function getScheduleCntByLessonId($lessson_id) {
        return LessonRequestSchedule::whereHas('lesson_request', function ($query) use ($lessson_id) {
                $query
                    ->whereHas('lesson', function ($query) use ($lessson_id) {
                        $query
                            ->where('lesson_id', $lessson_id);
                    });
            })->where('lrs_state', config('const.schedule_state.complete'))
            ->count();
    }

    public static function getBuyScheduleCntByKouhaiId($kouhai_id) {
        return LessonRequestSchedule::whereHas('lesson_request', function ($query) use ($kouhai_id) {
            $query->where('lr_user_id', $kouhai_id);
        })->where('lrs_state', '>=', config('const.schedule_state.reserve'))
            ->Where('lrs_state','<=', config('const.schedule_state.complete'))
            ->count();
    }

    public static function getSellScheduleCntBySenpaiId($senpai_id) {
        return LessonRequestSchedule::whereHas('lesson_request', function ($query) use ($senpai_id) {
            $query
                ->whereHas('lesson', function ($query) use ($senpai_id) {
                    $query
                        ->where('lesson_senpai_id', $senpai_id);
                });
        })->where('lrs_state', '>=', config('const.schedule_state.reserve'))
            ->Where('lrs_state','<=', config('const.schedule_state.complete'))
            ->count();
    }

    // get schedules by senpai id
    public static function getScheduleListBySenpaiId($senpai_id, $state = -1, $keyword = '', $order_type = 0, $start_date = NULL, $end_date = NULL) {

        $obj_schedules = LessonRequestSchedule::select('*');

        $obj_schedules = self::getObjSchedulesBySenpaiId($obj_schedules, $senpai_id);
        $obj_schedules = self::getObjSchedulesByState($obj_schedules, $state);
        $obj_schedules = self::getObjSchedulesByKeyword($obj_schedules, $keyword);
        $obj_schedules = self::getObjSchedulesByTerm($obj_schedules, $start_date, $end_date);
        $obj_schedule_infos = self::getObjScheduleWithInfos($obj_schedules);

        // orderBy
        $obj_ordered_schedule_infos = $obj_schedule_infos;
        if ( $state == config('const.quit_state') ) {
            if ( $order_type == 0 ) { // レッスンが近い順
                $obj_ordered_schedule_infos = $obj_schedule_infos->orderBy('lrs_date');
            } elseif ( $order_type == 1 ) { // 支払いの総額の高い順
                $obj_ordered_schedule_infos = $obj_schedule_infos->orderByDesc('lrs_amount');
            } elseif ( $order_type == 2 ) { // 単価の高い順
                $obj_ordered_schedule_infos = $obj_schedule_infos
                    ->join('lesson_requests', 'lesson_request_schedules.lrs_lr_id', '=', 'lesson_requests.lr_id')
                    ->join('lessons', 'lesson_requests.lr_lesson_id', '=', 'lessons.lesson_id')
                    ->orderByDesc('lessons.lesson_30min_fees');
            }
        }

        return $obj_ordered_schedule_infos;
    }

    public static function getCntScheduleBySenpaiId($senpai_id, $state = -1, $keyword = '', $start_date = NULL, $end_date = NULL) {
        $obj_schedules = LessonRequestSchedule::select('*');

        $obj_schedules = self::getObjSchedulesBySenpaiId($obj_schedules, $senpai_id);
        $obj_schedules = self::getObjSchedulesByState($obj_schedules, $state);
        $obj_schedules = self::getObjSchedulesByKeyword($obj_schedules, $keyword);
        $obj_schedules = self::getObjSchedulesByTerm($obj_schedules, $start_date, $end_date);

        return $obj_schedules->count();
    }

    // get schedules by kouhai id
    public static function getScheduleListByKouhaiId($kouhai_id, $state = -1, $keyword = '', $order_type = 0, $start_date = NULL, $end_date = NULL) {
        $obj_schedules = LessonRequestSchedule::select('*');

        $obj_schedules = self::getObjSchedulesByKouhaiId($obj_schedules, $kouhai_id);
        $obj_schedules = self::getObjSchedulesByState($obj_schedules, $state);
        $obj_schedules = self::getObjSchedulesByKeyword($obj_schedules, $keyword);
        $obj_schedules = self::getObjSchedulesByTerm($obj_schedules, $start_date, $end_date);
        $obj_schedule_infos = self::getObjScheduleWithInfos($obj_schedules);

        // orderBy
        $obj_ordered_schedule_infos = $obj_schedule_infos;
        if ( $state == config('const.schedule_state.reserve') ) {
            if ( $order_type == 0 ) { // レッスンが近い順
                $obj_ordered_schedule_infos = $obj_schedule_infos->orderBy('lrs_date');
            } elseif ( $order_type == 1 ) { // 支払いの総額の高い順
                $obj_ordered_schedule_infos = $obj_schedule_infos->orderByDesc('lrs_amount');
            } elseif ( $order_type == 2 ) { // 単価の高い順
                $obj_ordered_schedule_infos = $obj_schedule_infos
                    ->join('lesson_requests', 'lesson_request_schedules.lrs_lr_id', '=', 'lesson_requests.lr_id')
                    ->join('lessons', 'lesson_requests.lr_lesson_id', '=', 'lessons.lesson_id')
                    ->orderByDesc('lessons.lesson_30min_fees');
            }
        }

        return $obj_ordered_schedule_infos;
    }

    public static function getCntScheduleByKouhaiId($kouhai_id, $state = -1, $keyword = '', $start_date = NULL, $end_date = NULL) {
        $obj_schedules = LessonRequestSchedule::select('*');

        $obj_schedules = self::getObjSchedulesByKouhaiId($obj_schedules, $kouhai_id);
        $obj_schedules = self::getObjSchedulesByState($obj_schedules, $state);
        $obj_schedules = self::getObjSchedulesByKeyword($obj_schedules, $keyword);
        $obj_schedules = self::getObjSchedulesByTerm($obj_schedules, $start_date, $end_date);

        return $obj_schedules->count();
    }

    public static function getObjSchedulesByKouhaiId($obj_schedules, $kouhai_id) {

        return $obj_schedules
            ->whereHas('lesson_request', function ($query) use ($kouhai_id) {
                $query
                    ->where('lr_user_id', $kouhai_id);
            });
    }

    public static function getObjSchedulesBySenpaiId($obj_schedules, $senpai_id) {
        return $obj_schedules
            ->whereHas('lesson_request', function ($query) use ($senpai_id) {
                $query
                    ->whereHas('lesson', function ($query) use ($senpai_id) {
                        $query
                            ->whereHas('senpai', function ($query) use ($senpai_id) {
                                $query
                                    ->where('id', $senpai_id);
                            });
                    });
            });
    }

    public static function getObjScheduleWithInfos($obj_schedule) {
        return $obj_schedule
            ->with('lesson_request', function ($query) {
                $query
                    ->with('lesson', function ($query){
                        $query
                            ->with('lesson_class')
                            ->with('senpai');
                    });
            });
    }

    public static function getObjSchedulesByTerm($obj_schedules, $start_date = NULL, $end_date = NULL) {
        if ( !is_null($start_date) && !is_null($end_date) ) {
            return $obj_schedules
                ->where('lrs_date', '>=', $start_date)
                ->where('lrs_date', '<=', $end_date);
        } else {
            return $obj_schedules;
        }
    }

    public static function getObjSchedulesByKeyword($obj_schedules, $keyword = '') {
        return $obj_schedules
            ->whereHas('lesson_request', function ($query) use ($keyword) {
                $query
                    ->whereHas('lesson', function ($query) use ($keyword) {
                        $query
                            ->where('lesson_title', 'like', '%' . $keyword . '%');
                    });
            });
    }

    public static function getObjSchedulesByState($obj_schedules, $state = -1) {
        if($state == config('const.quit_state')){
            return $obj_schedules->where('lrs_state', '<', config('const.schedule_state.complete'));
        }
        if ( $state == -1 ) {
            $state = config('const.schedule_state.reserve');
        }
        if ( $state >= config('const.schedule_state.cancel_senpai') ) {
            return $obj_schedules->where('lrs_state', '>=', $state);
        } else {
            return $obj_schedules->where('lrs_state', $state);
        }
    }


    public static function getRequestOriginInfo($request_id) {
        $req_info = LessonRequest::where('lr_id', $request_id)
            ->with('lesson', function($query){
                $query->with('lesson_class');
            })
            ->with('user')
            ->with('lesson_request_schedule', function ($query) {
                $query->orderBy('lrs_date')->orderBy('lrs_start_time');
            })->first();

        if (!$req_info)
            return null;

        $req_info = $req_info->toArray();
        return $req_info;
    }

    public static function getRequestInfo($request_id) {
        $req_info = LessonRequest::where('lr_id', $request_id)
            ->with('lesson', function($query){
                $query->with('lesson_class');
            })
            ->with('user')
            ->with('lesson_request_schedule', function ($query) {
                $query->orderBy('lrs_date')->orderBy('lrs_start_time')
                    ->where('lrs_state', config('const.schedule_state.request'));
            })->first();

        if (!$req_info)
            return null;

        $req_info = $req_info->toArray();
        return $req_info;
    }

    public static function getRequestAllInfo($request_id) {
        $req_info = LessonRequest::where('lr_id', $request_id)
            ->with('lesson', function($query){
                $query->with('lesson_class');
            })
            ->with('user')
            ->with('lesson_request_schedule', function ($query) {
                $query->orderBy('lrs_date')->orderBy('lrs_start_time')
                    ->where(function($sub_query) {
                        $sub_query->where('lrs_state', config('const.schedule_state.request'));
                        $sub_query->orWhere('lrs_state', config('const.schedule_state.confirm'));
                    });
            })->first();

        if (!$req_info)
            return null;

        $req_info = $req_info->toArray();
        return $req_info;
    }

    public static function getSchedulesInKouhaiMenu($senpai_id, $sdate, $edate)
    {
        return LessonRequestSchedule::
            where('lrs_date', '>=', $sdate)
            ->where('lrs_date', '<=', $edate)
            ->where('lrs_state', config('const.schedule_state.reserve'))
            ->wherehas('lesson_request',
                function ($query) use ($senpai_id) {
                    $query->where('lr_user_id', $senpai_id);
                })
            ->with('lesson_request' , function ($query) {
                $query->with('lesson', function ($query){
                    $query ->with('senpai');
                });
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time')
            ->get()
            ->groupBy('lrs_date');
    }

    public static function getReserveSchedulesInKouhaiMenu($senpai_id, $kouhai_id=null) {
        $ret =  LessonRequestSchedule::where('lrs_state', config('const.schedule_state.reserve'))
            ->where(function($q) {
                $q->where('lrs_date', '>', date('Y-m-d'));
                $q->orwhere(function ($query) {
                    $query->where('lrs_date', date('Y-m-d'))
                        ->where('lrs_start_time','>=', date('H:i:s'));
                });
            })
            ->wherehas('lesson_request',
                function ($query) use ($senpai_id) {
                    $query->wherehas('lesson',
                        function ($query) use ($senpai_id) {
                            $query->where('lesson_senpai_id', $senpai_id);
                        });
                })
            ->with('lesson_request' , function ($query) {
                $query->with('user');
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time');

        $ret->where('lrs_senpai_id', $senpai_id);
        if (!is_null($kouhai_id)) {
            $ret->where('lrs_kouhai_id', $kouhai_id);
        }

        return $ret->get();
    }

    public static function getSchedulesInSenpaiMenu($kouhai_id, $sdate, $edate)
    {
        return LessonRequestSchedule::
              where('lrs_date', '>=', $sdate)
            ->where('lrs_date', '<=', $edate)
            ->where('lrs_state', config('const.schedule_state.reserve'))
            ->wherehas('lesson_request',
                function ($query) use ($kouhai_id) {
                    $query->wherehas('lesson',
                        function ($query) use ($kouhai_id) {
                            $query->where('lesson_senpai_id', $kouhai_id);
                        });
                })
            ->with('lesson_request' , function ($query) {
                $query->with('user');
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time')
            ->get()
            ->groupBy('lrs_date');
    }

    public static function getReserveSchedulesInSenpaiMenu($kouhai_id, $senpai_id=null) {
        $ret = LessonRequestSchedule::where('lrs_state', config('const.schedule_state.reserve'))
            ->where(function($q) {
                $q->where('lrs_date', '>', date('Y-m-d'));
                $q->orwhere(function ($query) {
                    $query->where('lrs_date', date('Y-m-d'))
                        ->where('lrs_start_time','>=', date('H:i:s'));
                });
            })

            ->wherehas('lesson_request',
                function ($query) use ($kouhai_id) {
                    $query->where('lr_user_id', $kouhai_id);
                })
            ->with('lesson_request' , function ($query) {
                $query->with('user');
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time');

        $ret->where('lrs_kouhai_id', $kouhai_id);
        if (!is_null($senpai_id)) {
            $ret->where('lrs_senpai_id', $senpai_id);
        }

        return $ret->get();

    }

    public static function getScheduleInfoById($schedule_id) {
        $obj_schedule = LessonRequestSchedule::where('lrs_id', $schedule_id)
            ->with('lesson_request', function ($query) {
                $query
                    ->with('lesson', function ($query){
                        $query
                            ->with('lesson_class')
                            ->with('senpai');
                    })
                    ->with('user');
            });

        $schedule_info = $obj_schedule->first();
        return $schedule_info;
    }

    public static function cancelScheduleBySystem($schedule_id, $reason) {
        $update_params = [
            'lrs_state'=>config('const.schedule_state.cancel_system'),
            'lrs_cancel_reason'=>serialize($reason),
            'lrs_cancel_date'=>date('Y-m-d H:i:s'),
            'lrs_cancel_fee'=>0,
        ];

        return LessonRequestSchedule::where('lrs_id', $schedule_id)
            ->update($update_params);
    }

    public static function cancelSchedule($schedule_id, $cancel_reasons, $user_type = 0, $reason_note = '', $cancel_fee = 0) {
        $cancel_state = $user_type ? config('const.schedule_state.cancel_kouhai') : config('const.schedule_state.cancel_senpai');

        $update_params = array();
        $update_params['lrs_state'] = $cancel_state;
        $update_params['lrs_cancel_reason'] = serialize($cancel_reasons);
        $update_params['lrs_cancel_date'] = now();
        $update_params['lrs_cancel_date'] = date('Y-m-d H:i:s');
        if ( $user_type ) {
            $update_params['lrs_cancel_fee'] = $cancel_fee;
        }

        if ( in_array(config('const.kouhai_cancel_other_reason_id'), $cancel_reasons) || in_array(config('const.senpai_cancel_reason_id'), $cancel_reasons) || in_array(config('const.senpai_cancel_other_reason_id'), $cancel_reasons) ) {
            $update_params['lrs_cancel_note'] = $reason_note;
        }
        $res = LessonRequestSchedule::where('lrs_id', $schedule_id)
            ->update($update_params);

        return $res;
    }

    public static function modifySchedule($schedule_id) {
        $schedule_info = self::getScheduleInfoById($schedule_id);

        $update_params['lrs_state'] = config('const.schedule_state.modified');
        $update_params['lrs_cancel_date'] = date('Y-m-d H:i:s');
        $update_params['lrs_cancel_fee'] = CommonService::getCancelFee($schedule_info['lrs_date'], $schedule_info['lrs_amount'], $schedule_info['lrs_service_fee'], $schedule_info['lrs_traffic_fee']);

        $res = LessonRequestSchedule::where('lrs_id', $schedule_id)
            ->update($update_params);

        return $res;
    }

    public static function getSchedulesForSenpai($senpai_id, $year, $month){
        $first_day = date('Y-m-d', strtotime(date($year.'-'.$month).' first day of this month'));
        $end_day = date('Y-m-d', strtotime(date($year.'-'.$month).' last day of this month'));
        $s_list = LessonSchedule::whereBetween('ls_date', [$first_day, $end_day])
            ->where('ls_senpai_id', $senpai_id)->get();

        $rs_list = LessonRequestSchedule::whereBetween('lrs_date', [$first_day, $end_day])
            ->where(function ($query){
                $query->where('lrs_state', config('const.schedule_state.request'))
                    ->orwhere('lrs_state', config('const.schedule_state.reserve'));
            })
            ->whereHas('lesson_request', function ($query) use ($senpai_id) {
                $query->whereHas('lesson', function ($query) use ($senpai_id) {
                        $query ->whereHas('senpai', function ($query) use ($senpai_id) {
                                $query ->where('id', $senpai_id);
                            });
                    });
            })
            ->with('lesson_request', function ($query) use ($senpai_id) {
                $query->with('lesson', function ($query) use ($senpai_id) {
                    $query ->with('senpai');
                });
            })->get();

        $schedule_list = array();
        if($s_list->count()){
            foreach ($s_list as $k=>$v){
                $day = date('j', strtotime($v['ls_date']));
                $s_arr = explode(':', $v['ls_start_time']);
                $s = intval($s_arr[0])+floatval(intval($s_arr[1])/60);
                $e_arr = explode(':', $v['ls_end_time']);
                $e = intval($e_arr[0])+floatval(intval($e_arr[1])/60)-0.25;
                for($i=$s; $i<=$e; $i=$i+0.25){
                    $schedule_list[strval($i)][$day] = 1;
                }
            }
        };
        if($rs_list->count()){
            foreach ($rs_list as $k=>$v){
                $day = date('j', strtotime($v['lrs_date']));
                $s_arr = explode(':', $v['lrs_start_time']);
                $s = intval($s_arr[0])+floatval(intval($s_arr[1])/60);
                $e_arr = explode(':', $v['lrs_end_time']);
                $e = intval($e_arr[0])+floatval(intval($e_arr[1])/60)-0.25;
                $state = intval($v['lrs_state']);
                for($i=$s; $i<=$e; $i=$i+0.25){
                        if ( $state == config('const.schedule_state.request') ) {
                            $schedule_list[strval($i)][$day] = 2;
                        } else if( $state == config('const.schedule_state.reserve')){
                            $temp_arr = array();
                            $temp_arr['val'] = 3;
                            $temp_arr['time'] = CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']);
                            $lesson_info = $v['lesson_request']['lesson'];
                            $temp_arr['ttl'] = $lesson_info['lesson_title'];
                            $birthday = $lesson_info['senpai']['user_birthday'];
                            $old = CommonService::getAge($birthday);
                            $temp_arr['kouhai'] = $lesson_info['senpai']['name'].'('.$old.')';
                            $schedule_list[strval($i)][$day] = $temp_arr;
                        }
                }
            }
        };
        return $schedule_list;
    }

    public static function getWeekSchedulesForSenpai($senpai_id, $date){
        $first_day = $date;
        $end_day = Carbon::parse($date)->addDays(6)->format('Y-m-d');
        $s_list = LessonSchedule::whereBetween('ls_date', [$first_day, $end_day])
            ->where('ls_senpai_id', $senpai_id)->get();

        $rs_list = LessonRequestSchedule::whereBetween('lrs_date', [$first_day, $end_day])
            ->where(function ($query){
                $query->where('lrs_state', config('const.schedule_state.request'))
                    ->orwhere('lrs_state', config('const.schedule_state.reserve'));
            })
            ->whereHas('lesson_request', function ($query) use ($senpai_id) {
                $query->whereHas('lesson', function ($query) use ($senpai_id) {
                    $query ->whereHas('senpai', function ($query) use ($senpai_id) {
                        $query ->where('id', $senpai_id);
                    });
                });
            })
            ->with('lesson_request', function ($query) use ($senpai_id) {
                $query->with('lesson', function ($query) use ($senpai_id) {
                    $query ->with('senpai');
                });
            })->get();

        $schedule_list = array();
        if($s_list->count()){
            foreach ($s_list as $k=>$v){
                $day = date('j', strtotime($v['ls_date']));
                $s_arr = explode(':', $v['ls_start_time']);
                $s = intval($s_arr[0])+floatval(intval($s_arr[1])/60);
                $e_arr = explode(':', $v['ls_end_time']);
                $e = intval($e_arr[0])+floatval(intval($e_arr[1])/60)-0.25;
                for($i=$s; $i<=$e; $i=$i+0.25){
                    $schedule_list[strval($i)][$day] = 1;
                }
            }
        };
        if($rs_list->count()){
            foreach ($rs_list as $k=>$v){
                $day = date('j', strtotime($v['lrs_date']));
                $s_arr = explode(':', $v['lrs_start_time']);
                $s = intval($s_arr[0])+floatval(intval($s_arr[1])/60);
                $e_arr = explode(':', $v['lrs_end_time']);
                $e = intval($e_arr[0])+floatval(intval($e_arr[1])/60)-0.25;
                $state = intval($v['lrs_state']);
                for($i=$s; $i<=$e; $i=$i+0.25){
                    if ( $state == config('const.schedule_state.request') ) {
                        $schedule_list[strval($i)][$day] = 2;
                    } else if( $state == config('const.schedule_state.reserve')){
                        $temp_arr = array();
                        $temp_arr['val'] = 3;
                        $temp_arr['time'] = CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']);
                        $lesson_info = $v['lesson_request']['lesson'];
                        $temp_arr['ttl'] = $lesson_info['lesson_title'];
                        $birthday = $lesson_info['senpai']['user_birthday'];
                        $old = CommonService::getAge($birthday);
                        $temp_arr['kouhai'] = $lesson_info['senpai']['name'].'('.$old.')';
                        $schedule_list[strval($i)][$day] = $temp_arr;
                    }
                }
            }
        };
        return $schedule_list;
    }

    public static function getCountRequestBySenpaiId($senpai_id){
        return LessonRequest::whereHas('lesson', function ($query) use ($senpai_id) {
                $query->where('lesson_senpai_id', $senpai_id);
            })
            ->whereHas('lesson_request_schedule', function ($query)  {
                $query->where('lrs_state', config('const.schedule_state.request'));
            })->get()->count();
    }

    public static function getRequestListBySenpaiId($senpai_id, $type, $page_from=null){
        $ret =  LessonRequest::where('lr_type', $type)
            ->whereHas('lesson', function ($query) use ($senpai_id) {
                $query->where('lesson_senpai_id', $senpai_id);
            });
        if ($page_from == "myaccount") {
            //
        } else {
            $ret->whereHas('lesson_request_schedule', function ($query)  {
                $query->where('lrs_state', config('const.schedule_state.request'));
            });
        }
        $ret->with('lesson', function($query){
                $query->with('lesson_class');
            })
            ->with('user')
            ->with('lesson_request_schedule', function ($query) {
                $query->orderBy('lrs_state');
            })
            ->orderByDesc('lr_id');
        return $ret;
	}

    public static function getReservedRequestListWithCond($type, $lesson_id, $kouhai_id ){
        $reserve_obj = LessonRequest::where('lr_type', $type)
            ->where('lr_until_confirm', '>=', date('Y-m-d'))
            ->Where('lr_lesson_id', $lesson_id)
            ->Where('lr_user_id', $kouhai_id)
            ->Where('lr_state', config('const.req_state.reserve'))
            ->whereHas('lesson_request_schedule', function ($query)  {
                $query->where('lrs_state', config('const.schedule_state.request'))
                    ->where('lrs_date', '>=', date('Y-m-d') )
                    ->where('lrs_start_time', '>=', date('h:i:s') );
            })
            ->with('lesson_request_schedule');
        return $reserve_obj->get();
    }

    // get by lesson id
    public static function getSchedulesOfDayByLessonId($lesson_id, $date, $state = '') {
        if ( $state == '' ) {
            $state = config('const.schedule_state.complete');
        }

        return  LessonRequestSchedule::where('lrs_state', $state)
            ->where('lrs_date', $date)
            ->whereHas('lesson_request', function ($query) use ($lesson_id) {
                $query
                    ->whereHas('lesson', function ($query) use ($lesson_id) {
                        $query
                            ->where('lesson_id', $lesson_id);
                    });
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time')
            ->get();

    }

    public static function getSchedulesByLessonId($lesson_id, $state = '') {
        if ( $state == '' ) {
            $state = config('const.schedule_state.complete');
        }

        return  LessonRequestSchedule::where('lrs_state', $state)
                ->whereHas('lesson_request', function ($query) use ($lesson_id) {
                    $query
                        ->whereHas('lesson', function ($query) use ($lesson_id) {
                            $query
                                ->where('lesson_id', $lesson_id);
                    });
                })
                ->orderBy('lrs_date')
                ->orderBy('lrs_start_time')
                ->get();
    }

    public static function getSchedulesCntByLessonId($lesson_id, $state =  '') {
        if ( $state == '' ) {
            $state = config('const.schedule_state.complete');
        }

        return  LessonRequestSchedule::where('lrs_state', $state)
            ->whereHas('lesson_request', function ($query) use ($lesson_id) {
                $query
                    ->whereHas('lesson', function ($query) use ($lesson_id) {
                        $query
                            ->where('lesson_id', $lesson_id);
                    });
            })
            ->orderBy('lrs_date')
            ->orderBy('lrs_start_time')
            ->count();
    }

    public static function doCreateLessonArea($lesson_id, $area_info){
        $data = array();
        $data['la_lesson_id'] = $lesson_id;
        // deep1, deep2
        $prefecture = Area::where('area_deep', config('const.area_deep_code.pref'))
            ->where('area_name', $area_info->prefecture)
            ->first();
        if (is_object($prefecture)) {
            $data['la_deep1_id'] = $prefecture->area_region;
            $data['la_deep2_id'] = $prefecture->area_id;
        }
        // deep3
        $locality = Area::where('area_deep', config('const.area_deep_code.city'))
            ->where('area_name', $area_info->county.$area_info->locality.$area_info->sublocality)
            ->first();
        if (is_object($locality)) {
            $data['la_deep3_id'] = $locality->area_id;
        }
        // position
        $data['position'] = json_encode($area_info, JSON_UNESCAPED_UNICODE);

        return LessonArea::create($data);
    }

    public static function doCreateLessonDiscussArea($lesson_id, $area_info){
        $data = array();
        $data['lda_lesson_id'] = $lesson_id;
        // deep1, deep2
        $prefecture = Area::where('area_deep', config('const.area_deep_code.pref'))
            ->where('area_name', $area_info->prefecture)
            ->first();
        if (is_object($prefecture)) {
            $data['lda_deep1_id'] = $prefecture->area_region;
            $data['lda_deep2_id'] = $prefecture->area_id;
        }
        // deep3
        $data['lda_deep3_id'] = $area_info->area_id;
        // position
        $data['position'] = json_encode($area_info, JSON_UNESCAPED_UNICODE);

        return LessonDiscussArea::create($data);
    }

    public static function doDeleteLessonArea($lesson_id){
        $model = LessonArea::where('la_lesson_id', $lesson_id);
        if($model->count() >0)
            return $model->delete();
    }

    public static function doDeleteLessonDiscussArea($lesson_id){
        $model = LessonDiscussArea::where('lda_lesson_id', $lesson_id);
        if($model->count() >0)
            return $model->delete();
    }

    public static function getRecommendLessons($lesson_class_id = 0, $lesson_id = 0){
        $limit_num = SettingService::getSetting('home_show_lesson_cnt','int');
        $curDate = date('Y-m-d');
        $curTime = date('H:i:s');
        $lastDate = date('Y-m-d', strtotime($curDate.' -90 days'));

        $lesson_obj = Lesson::where('lesson_state', config('const.lesson_state.public'));
        if($lesson_class_id > 0){
            $lesson_obj = $lesson_obj->where('lesson_class_id', $lesson_class_id);
        }
        if($lesson_id > 0){
            $lesson_obj = $lesson_obj->where('lesson_id', '!=', $lesson_id);
        }

        return $lesson_obj->wherehas('lesson_schedule', function ($query) use ($curDate, $curTime) {
                $query->where('ls_date', '>', $curDate)
                      ->orWhere(function ($query) use ($curDate, $curTime) {
                         $query->where('ls_date', $curDate)
                               ->where('ls_start_time', '>=', $curTime);
                      });
            })
            ->wherehas('lesson_load', function ($query) {
                $query->whereIn('state', [config('const.load_state.able'),config('const.load_state.full')] );
            })
            ->withcount(['evalution as eval_mark'=> function($query) use ($lastDate) {
                $query->where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                      ->where('eval_val', 1)
                      ->where('updated_at', '>', $lastDate);
            }])
            ->withcount(['evalution as eval_cnt'=> function($query) use ($lastDate) {
                $query->where('eval_kind', EvalutionService::KOUHAIS_EVAL)
                    ->where('updated_at', '>', $lastDate);
            }])
            ->limit($limit_num)
            ->orderByDesc('eval_mark')
            ->orderByDesc('eval_cnt')
            ->orderByDesc('updated_at')
            ->get();
    }

    public static function getBrowseLessons($user_id){
        $limit_num = SettingService::getSetting('home_show_lesson_cnt','int');
        return Lesson::where('lesson_state', config('const.lesson_state.public'))
            ->where('lesson_click', '>', 0)
            ->with('lesson_class')
            ->with('senpai')
            ->limit($limit_num)
            ->orderByDesc('lesson_click')
            ->get();
    }

    public static function getReservedLessons(){
        //lesson_request_schedule
        $limit_num = SettingService::getSetting('home_show_lesson_cnt','int');
        return Lesson::where('lesson_state', config('const.lesson_state.public'))
            ->whereHas('request_schedule', function ($query) {
                    $query->where('lrs_state', config('const.schedule_state.reserve'));
            })
            ->withCount('request_schedule')
            ->with('lesson_class')
            ->with('senpai')
            ->limit($limit_num)
            ->orderByDesc('request_schedule_count')
            ->get();
    }

    public static function updateLessonState($lesson_id, $state)
    {
        return Lesson::where('lesson_id', $lesson_id)
            ->update(['lesson_state'=>$state]);
    }

    public static function updateLessonReqState($lr_id,  $state) {
        $data = array();
        $data['lr_state'] = $state;
        if ( $state == config('const.req_state.request')) {
            $data['lr_request_date'] = date('Y-m-d');
        }else if ( $state == config('const.req_state.reserve')) {
            $data['lr_reserve_date'] = date('Y-m-d');
        }else if ( $state == config('const.req_state.complete')) {
            $data['lr_complete_date'] = date('Y-m-d');
        }else if ( $state == config('const.req_state.cancel')) {
            $data['lr_cancel_date'] = date('Y-m-d');
        }else
            return false;

        return LessonRequest::where('lr_id', $lr_id)
            ->update($data);
    }

    public static function updateScheduleState($sch_id, $state) {
        $data = array();
        $data['lrs_state'] = $state;
        if ( $state == config('const.schedule_state.request')) {
            $data['lrs_request_date'] = date('Y-m-d H:i:s ');
        }else if ( $state == config('const.schedule_state.confirm')) {
            $data['lrs_confirm_date'] = date('Y-m-d H:i:s');
        }else if ( $state == config('const.schedule_state.reserve')) {
            $data['lrs_reserve_date'] = date('Y-m-d H:i:s');
            $schedule_obj = self::getScheduleInfoById($sch_id);
            if(is_null($schedule_obj))
                $data['lrs_fee'] = 0;
            else{
                $senpai_id = $schedule_obj['lesson_request']['lesson']['lesson_senpai_id'];
                $kouhai_id = $schedule_obj['lesson_request']['lr_user_id'];
                $data['lrs_fee'] = LessonService::getLessonFee($kouhai_id, $senpai_id, $schedule_obj['lrs_amount']);
            }
        }else if ( $state == config('const.schedule_state.start')) {
            $data['lrs_start_date'] = date('Y-m-d H:i:s');
            $schedule_obj = self::getScheduleInfoById($sch_id);
            $senpai_id = $schedule_obj['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $schedule_obj['lesson_request']['lr_user_id'];
            // msg
            $msg = "レッスンがスタートしました。<br>レッスン頑張ってください。";
            TalkroomService::saveSenpaiSysMsg($senpai_id, $kouhai_id, $msg);
            TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);
            $msg = "位置情報の共有をオフにしました。";
            TalkroomService::saveSenpaiSysMsg($senpai_id, $kouhai_id, $msg);
            TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);

        }else if ( $state == config('const.schedule_state.complete')) {
            $data['lrs_complete_date'] = date('Y-m-d');
        }else if ( $state == config('const.schedule_state.cancel_senpai') ||
                   $state == config('const.schedule_state.cancel_kouhai') ||
                   $state == config('const.schedule_state.cancel_system') ||
                   $state == config('const.schedule_state.modified')) {
            $data['lrs_cancel_date'] = date('Y-m-d H:i:s');
        }
        $ret = LessonRequestSchedule::where('lrs_id', $sch_id)
            ->update($data);
        if (!$ret)
            return false;
        $sch_obj = LessonRequestSchedule::where('lrs_id', $sch_id)->first();
        $min = LessonRequestSchedule::where('lrs_lr_id', $sch_obj['lrs_lr_id'])
                    ->min('lrs_state');

        if ( $min < config('const.schedule_state.reserve' ))
            $lr_state = config('const.req_state.request');
        else if ( $min < config('const.schedule_state.start' ))
            $lr_state = config('const.req_state.reserve');
        else if ( $min < config('const.schedule_state.complete' ))
            $lr_state = config('const.req_state.reserve');
        else if ( $min < config('const.schedule_state.cancel_senpai' ))
            $lr_state = config('const.req_state.complete');
        /*else {
            $lr_state = config('const.req_state.cancel');
        }*/

        if (isset($lr_state)) {
            return LessonRequest::where('lr_id', $sch_obj['lrs_lr_id'])
                ->update(['lr_state'=> $lr_state]);
        }
        return true;
    }

    // レッスンを購入する => 予約を確定する
    public static function updateRequestAndScheduleState($lr_id, $state) {
        $obj_lr = LessonRequest::find($lr_id);
        $obj_lr->lr_state = config('const.req_state.reserve');
        $obj_lr->lr_reserve_date = date('Y-m-d H:i:s');
        $obj_lr->save();

        $update_data = [
            'lrs_state' => $state
        ];
        if ($state == config('const.schedule_state.reserve')) {
            $update_data['lrs_reserve_date'] = date('Y-m-d H:i:s');
        }
        return LessonRequestSchedule::where('lrs_lr_id', $lr_id)
            ->where('lrs_state', config('const.schedule_state.confirm'))
            ->update($update_data);
    }

    // update attend request state
    public static function updateAttendRequestState($lr_id, $state, $reject_lrs_ids=[]) {
        $obj_lr = LessonRequest::find($lr_id);

        if (!is_null($reject_lrs_ids) && is_array($reject_lrs_ids) && count($reject_lrs_ids) > 0) {
            $update_data = [
                'lrs_state' => config('const.schedule_state.reject_senpai'),
                'lrs_cancel_date' => date('Y-m-d H:i:s')
            ];
            LessonRequestSchedule::whereIn('lrs_id', $reject_lrs_ids)
                ->update($update_data);
        }

        $obj_lr->lr_state = $state;
        if ($state == config('const.req_state.response')) {
            $obj_lr->lr_response_date = date('Y-m-d H:i:s');
        } else if($state == config('const.req_state.reject')) {
            $obj_lr->lr_cancel_date = date('Y-m-d H:i:s');
        }
        return $obj_lr->save();
    }

    public static function getSchedulesFromStartTime($date, $startTime, $state)
    {
        return LessonRequestSchedule::where('lrs_state', $state)
            ->where('lrs_date', $date)
            ->where('lrs_start_time', $startTime)
            ->with('lesson_request', function ($query) {
                $query->with('lesson');
            })
            ->get();
    }

    public static function getSchedules($state)
    {
        return LessonRequestSchedule::where('lrs_state', $state)
            ->with('lesson_request', function ($query) {
                $query->with('lesson');
            })
            ->get();
    }

    public static function getRequestOrConfirmSchedulesByUserId($user_id)
    {
        return LessonRequestSchedule::whereIn('lrs_state', [config('const.schedule_state.request'), config('const.schedule_state.confirm')])
            ->whereHas('lesson_request', function ($query) use($user_id) {
                $query->where('lr_user_id', $user_id);
            })
            ->with('lesson_request')
            ->get();
    }

    // talkroom レッスンを購入する
    public static function getSchedulesByLessonRequestId($lr_id, $state=null)
    {
        if (is_null($state)) {
            return LessonRequestSchedule::whereHas('lesson_request', function ($query) use($lr_id) {
                    $query->where('lr_id', $lr_id);
                })
                ->with('lesson_request')
                ->get();
        }
        return LessonRequestSchedule::where('lrs_state', $state)
            ->whereHas('lesson_request', function ($query) use($lr_id) {
                $query->where('lr_id', $lr_id);
            })
            ->with('lesson_request')
            ->get();
    }


    public static function getSchedulesPassedUntilConfirm($date)
    {
        return LessonRequestSchedule::where('lrs_state', config('const.schedule_state.request'))
            ->wherehas('lesson_request', function ($query) use ($date) {
                $query->where( 'lr_until_confirm', '<',  $date );
            })
            ->get()->toArray();
    }

    public static function getWeekScheduleState($lesson_id, $date)
    {
        $results = array();
        $day = date('w', strtotime($date));
        $week_start = date('Y-m-d', strtotime($date.' -'.$day.' days'));

        $lesson_info = self::getLessonInfo($lesson_id);
        $month_arr = [];
        for( $i = 0; $i < 7; $i++ ) {
            $cur_day =  date('Y-m-d', strtotime($week_start.' +'.$i.' days'));
            $cur_short_day =  date('j', strtotime($week_start.' +'.$i.' days'));
            $month_arr[$cur_short_day] = Carbon::parse($cur_day)->format('Y-m');
            if (!$lesson_info) {
                $results[$cur_short_day] = config('const.load_state.unable');
                continue;
            }

            $lsSchedules = self::getLessonSchedulesOfDay($lesson_id, $cur_day);

            if (!count($lsSchedules)) {
                $results[$cur_short_day] = config('const.load_state.unable');
                continue;
            }

            $load = array();
            foreach ($lsSchedules as $k => $v) {
                $s = CommonService::convertDatetimeToId($v['ls_start_time']);
                $e = CommonService::convertDatetimeToId($v['ls_end_time']);
                for( $j = $s; $j <= $e; $j++ )
                   $load[$j] = 0;
            }


            if (!isset($load)) {
                $results[$cur_short_day] = config('const.load_state.unable');
                continue;
            }

            $lrSchedules= self::getSchedulesOfDayByLessonId($lesson_id, $cur_day, config('const.schedule_state.reserve'));

            if (!count($lrSchedules)) {
                $results[$cur_short_day] = config('const.load_state.able');
                continue;
            }

            foreach ($lrSchedules as $k => $v) {
                $s = CommonService::convertDatetimeToId($v['lrs_start_time']);
                $e = CommonService::convertDatetimeToId($v['lrs_end_time']);
                for( $j = $s; $j <= $e; $j++ ) {
                    if (isset($load[$j])) $load[$j] += 1;
                }
            }


            foreach ( $load as $kk => $vv ) {

                if ( ($vv + 1) < $lesson_info['lesson_person_num']) {
                    $results[$cur_short_day] = config('const.load_state.able');
                    break;
                }
            }
            if ( !isset($results[$cur_short_day]))
                $results[$cur_short_day] = config('const.load_state.full');

        }

        $ret = [];
        foreach ($results as $key=>$val) {
            $ret[] = ['day'=>$key, 'val'=>$val, 'month'=>$month_arr[$key]];
        }
        return $ret;
    }

    public static function getWeekDateInfo($date)
    {
        $results = [];
        $day = date('w', strtotime($date));
        $week_start = date('Y-m-d', strtotime($date.' -'.$day.' days'));

        for( $i = 0; $i < 7; $i++ ) {
            $cur_day = date('Y-m-d', strtotime($week_start.' +'.$i.' days'));
            $cur_short_day = date('j', strtotime($week_start.' +'.$i.' days'));
            $results[$cur_day] = $cur_short_day;
        }

        /*$ret = [];
        foreach ($results as $key=>$val) {
            $ret[] = ['day'=>$key, 'val'=>$val];
        }*/
        return $results;
    }

    public static function doAgreeLesson($params)
    {
        $lesson_id = $params['lesson_id'];
        $obj_lesson = Lesson::where('lesson_id', $lesson_id)->first();
        if (is_object($obj_lesson)) {
            $obj_lesson->lesson_state = config('const.lesson_state.public');
            return $obj_lesson->save();
        }
        return false;
    }

    public static function getEarningAmount($user_id, $date_type, $from_date="", $to_date="")
    {
        $complete_state = config('const.req_state.complete');

        $ret = LessonRequestSchedule::select(DB::raw("SUM(lrs_amount) as earning"))
            ->whereIn('lrs_lr_id', function($query) use ($user_id, $complete_state) {
                $query->select('lesson_requests.lr_id')
                    ->from('lesson_requests')
                    ->whereIn('lr_lesson_id', function($sub_query) use ($user_id) {
                        $sub_query->select('lessons.lesson_id')
                            ->from('lessons')
                            ->where('lesson_senpai_id', $user_id);
                    })
                    ->where('lr_state', $complete_state);
            })
            ->where('lrs_state', config('const.schedule_state.complete'));
        $now = Carbon::now()->format('Y-m-d');
        if ($date_type == config('const.date_type.day')) {
            $ret->whereDate('lrs_complete_date', $now);
        } else if($date_type == config('const.date_type.month')) {
            $ret->whereMonth('lrs_complete_date', $now);
        } else if($date_type == config('const.date_type.year')) {
            $ret->whereYear('lrs_complete_date', $now);
        } else if($date_type == config('const.date_type.period')) {
            $ret->whereDate('lrs_complete_date', '>=', $from_date);
            $ret->whereDate('lrs_complete_date', '<', $to_date);
        }
        $amount = $ret->get();
        if (is_object($amount) && $amount[0] && $amount[0]['earning']) {
            return $amount[0]['earning'];
        }
        return 0;
    }

    // 売上管理・振込申請 => お支払い金額 by period
    public static function getPaymentHistoryByPeriod($user_id, $from_date, $to_date)
    {
        $complete_state = config('const.req_state.complete');

        return LessonRequestSchedule::whereIn('lrs_lr_id', function($query) use ($user_id, $complete_state) {
                $query->select('lesson_requests.lr_id')
                    ->from('lesson_requests')
                    ->whereIn('lr_lesson_id', function($sub_query) use ($user_id) {
                        $sub_query->select('lessons.lesson_id')
                            ->from('lessons')
                            ->where('lesson_senpai_id', $user_id);
                    })
                    ->where('lr_state', $complete_state);
            })
            ->where('lrs_state', config('const.schedule_state.complete'))
            ->whereDate('lrs_complete_date', '>=', $from_date)
            ->whereDate('lrs_complete_date', '<=', $to_date)
            ->orderByDesc('lrs_complete_date')
            ->get();
    }

    // 売上金の振込申請期限 by date
    public static function getPeriodApplication($user_id, $to_date)
    {
        $complete_state = config('const.req_state.complete');
        $from_date = Carbon::parse($to_date)->addMonths(-5)->format('Y-m-01');

        $ret = LessonRequestSchedule::select(DB::raw("SUM(lrs_amount) as earning"))->whereIn('lrs_lr_id', function($query) use ($user_id, $complete_state) {
            $query->select('lesson_requests.lr_id')
                ->from('lesson_requests')
                ->whereIn('lr_lesson_id', function($sub_query) use ($user_id) {
                    $sub_query->select('lessons.lesson_id')
                        ->from('lessons')
                        ->where('lesson_senpai_id', $user_id);
                })
                ->where('lr_state', $complete_state);
            })
            ->where('lrs_state', config('const.schedule_state.complete'))
            ->whereDate('lrs_complete_date', '>=', $from_date)
            ->whereDate('lrs_complete_date', '<=', $to_date)
            ->get();

        if (is_object($ret) && $ret[0] && $ret[0]->earning) {
            return $ret[0]->earning;
        }
        return 0;
    }

    // 売上金残高合計
    public static function getRemainPrice($user_id)
    {
        $all_price = self::getAllPrice($user_id);
        $transfer_price = TransferApplicationService::getAllTransferPrice($user_id);

        if ($all_price == 0) return $all_price;

        return $all_price - $transfer_price;
    }

    // 売上金合計
    public static function getAllPrice($user_id)
    {
        $complete_state = config('const.req_state.complete');

        $ret = LessonRequestSchedule::select(DB::raw("SUM(lrs_amount) as earning"))->whereIn('lrs_lr_id', function($query) use ($user_id, $complete_state) {
            $query->select('lesson_requests.lr_id')
                ->from('lesson_requests')
                ->whereIn('lr_lesson_id', function($sub_query) use ($user_id) {
                    $sub_query->select('lessons.lesson_id')
                        ->from('lessons')
                        ->where('lesson_senpai_id', $user_id);
                })
                ->where('lr_state', $complete_state);
        })
            ->where('lrs_state', config('const.schedule_state.complete'))
            ->get();
        if (is_object($ret) && $ret[0] && $ret[0]->earning) {
            return $ret[0]->earning;
        }
        return 0;
    }

    // XX月XX日以降の申請可能な売上
    public static function getPossibleSendPrice($user_id, $compare_date)
    {
        $complete_state = config('const.req_state.complete');

        $ret = LessonRequestSchedule::select(DB::raw("SUM(lrs_amount) as earning"))->whereIn('lrs_lr_id', function($query) use ($user_id, $complete_state) {
            $query->select('lesson_requests.lr_id')
                ->from('lesson_requests')
                ->whereIn('lr_lesson_id', function($sub_query) use ($user_id) {
                    $sub_query->select('lessons.lesson_id')
                        ->from('lessons')
                        ->where('lesson_senpai_id', $user_id);
                })
                ->where('lr_state', $complete_state);
        })
            ->where('lrs_state', config('const.schedule_state.complete'))
            ->whereDate('lrs_complete_date', '>', $compare_date)
            ->get();
        if (is_object($ret) && $ret[0] && $ret[0]->earning) {
            $transfer_price = TransferApplicationService::getTransferPriceAfterDate($user_id, $compare_date);

            return $ret[0]->earning - $transfer_price;
        }
        return 0;
    }

    // 売上明細書 year_list 取得
    public static function getRequestScheduleYear()
    {
        return LessonRequestSchedule::select(DB::raw("DISTINCT YEAR(lrs_complete_date) as year"))
            ->where('lrs_state', config('const.schedule_state.complete'))
            ->orderBy('year')
            ->get()->toArray();
    }

    // 最終出勤登録日
    public static function getLastAttendanceDate($senpai_id)
    {
        $ret = LessonSchedule::where('ls_senpai_id', $senpai_id)->orderByDesc('ls_date')->first();
        return is_object($ret) ? $ret['ls_date'] : '';
    }

    // 公開停止の取り消し
    public static function stopLessonCancel($params)
    {
        $obj_lesson = Lesson::find($params['lesson_id']);
        if (!is_object($obj_lesson)) {
            return false;
        }

        if ($params['radio-cancel'] == config('const.stop_lesson_cancel.now')) { // 今すぐ公開停止を取り消す
            $obj_lesson->lesson_stop = config('const.lesson_stop_code.no_stop_lesson');
        } else { // 公開停止の取り消しを予約する
            $obj_lesson->lesson_stop_cancel_reverse_at =  Carbon::create($params['cancel_date']." ".$params['cancel_hour'].":".$params['cancel_minute'])->format('Y-m-d H:i:s');
        }
        return $obj_lesson->save();
    }

    // 取り消し予約一覧
    public static function cancelStopLessonCancel($params)
    {
        $obj_lesson = Lesson::find($params['lesson_id']);
        if (!is_object($obj_lesson)) {
            return false;
        }

        $obj_lesson->lesson_stop_cancel_reverse_at = null;
        return $obj_lesson->save();
    }

    public static function saveLessonChangeHistory($obj_lesson, $is_create=false, $origin_info=null) {

        if (Auth::guard('admin')->check()) {
            $updated_by = 'admin.'.Auth::guard('admin')->user()->id;
        } else {
            $updated_by = 'user.'.Auth::user()->id;
        }
        $arr_changed_data = $is_create ? $obj_lesson->getAttributes() : $obj_lesson->getChanges();
        if (!$is_create && !$obj_lesson->change_history_exists) {
            $obj_init_data = new LessonChangeHistory();
            $obj_init_data->lesson_id = $obj_lesson->lesson_id;
            $obj_init_data->op_type = $is_create ? config('const.lesson_op_type_code.new') : config('const.lesson_op_type_code.change');
            $obj_init_data->changed_data = $obj_lesson->getAttributes();
            $obj_init_data->updated_by = $updated_by;
            $obj_init_data->save();
        }

        if(!empty($arr_changed_data)) {
            $obj_changed_data = new LessonChangeHistory();
            $obj_changed_data->lesson_id = $obj_lesson->lesson_id;
            $obj_changed_data->op_type = $is_create ? config('const.lesson_op_type_code.new') : config('const.lesson_op_type_code.change');
            $obj_changed_data->changed_data = $arr_changed_data;
            $obj_changed_data->origin_data = $origin_info;
            $obj_changed_data->updated_by = $updated_by;
            $obj_changed_data->save();
        }
        return true;
    }

    public static function getNewLessonCount()
    {
        $lessons = Lesson::orderBy('updated_at')
            ->where('lesson_state', config('const.lesson_state.check'))
            ->whereNotIn('lesson_id', function($query) {
                $query->select('lesson_id')
                    ->from('lesson_change_history')
                    ->where('origin_data', 'like', '%\"lesson_state\":'.config('const.lesson_state.public').'%');
            })
            ->get()
            ->count();

        return $lessons;
    }

    public static function getChangeLessonCount()
    {
        $lessons = Lesson::orderBy('updated_at')
            ->where('lesson_state', config('const.lesson_state.check'))
            ->whereIn('lesson_id', function($query) {
                $query->select('lesson_id')
                    ->from('lesson_change_history')
                    ->where('origin_data', 'like', '%\"lesson_state\":'.config('const.lesson_state.public').'%');
            })
            ->get()
            ->count();

        return $lessons;
    }

    public static function getChangedItemHistory($lesson_id) {
        $lessons =  LessonChangeHistory::where('lesson_id', $lesson_id);
        $ret = $lessons->where('updated_at', '>=', function($query) {
            $query->select('updated_at')
                ->from('lesson_change_history')
                ->where('origin_data', 'like', '%\"lesson_state\":'.config('const.lesson_state.public').'%')
                ->orderByDesc('updated_at')
                ->first();
        })->get();

        $changed_items = [];
        if (is_object($ret) && count($ret) > 0) {
            foreach ($ret as $lesson_history) {
                $changed_data = $lesson_history->changed_data;
                if (is_object($changed_data)) {
                    foreach ($changed_data as $key=>$change_item) {
                        if (!isset($changed_items[$key])) {
                            $changed_items[$key] = $change_item;
                        }
                    }
                }
            }
        }

        return $changed_items;
    }

    //
    public static function getFirstRequestSchedule($lr_id, $state=null) {
        $schedule = LessonRequestSchedule::where('lrs_lr_id', $lr_id);
        if (!is_null($state)) {
            $schedule->where('lrs_state', $state);
        }
        return $schedule->orderBy('created_at')->first();
    }

    // リクエスト送信
    public static function getSendRequestInfo($condition) {
        $lesson_requests = LessonRequest::select('*');
        if (isset($condition['status']) && $condition['status']) {
            if ($condition['status'] == config('const.lesson_request_status_code.reserve_request')) {
                $lesson_requests->where('lr_state', config('const.req_state.request'));
                $lesson_requests->where('lr_type', config('const.request_type.reserve'));
            } else if($condition['status'] == config('const.lesson_request_status_code.attendance_request')) {
                $lesson_requests->where('lr_state', config('const.req_state.request'));
                $lesson_requests->where('lr_type', config('const.request_type.attend'));
            } else if($condition['status'] == config('const.lesson_request_status_code.reserve_response')) {
                $lesson_requests->where('lr_state', config('const.req_state.response'));
                $lesson_requests->where('lr_type', config('const.request_type.reserve'));
            } else if($condition['status'] == config('const.lesson_request_status_code.attendance_response')) {
                $lesson_requests->where('lr_state', config('const.req_state.response'));
                $lesson_requests->where('lr_type', config('const.request_type.attend'));
            } else if($condition['status'] == config('const.lesson_request_status_code.reserve')) {
                $lesson_requests->where('lr_state', config('const.req_state.reserve'));
            } else if($condition['status'] == config('const.lesson_request_status_code.complete')) {
                $lesson_requests->where('lr_state', config('const.req_state.complete'));
            } else if($condition['status'] == config('const.lesson_request_status_code.cancel')) {
                $lesson_requests->where('lr_state', config('const.req_state.cancel'));
            }
        }

        // ID（先輩）
        if (isset($condition['senpai_user_no']) && $condition['senpai_user_no']) {
            $lesson_requests->leftJoin('lessons', function ($join) {
                $join->on('lesson_requests.lr_lesson_id', 'lessons.lesson_id');
            });
            $lesson_requests->whereIn('lessons.lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['senpai_user_no'].'%');
            });
        }
        // ニックネーム（先輩）
        if (isset($condition['senpai_nickname']) && $condition['senpai_nickname']) {
            $lesson_requests->leftJoin('lessons', function ($join) {
                $join->on('lesson_requests.lr_lesson_id', 'lessons.lesson_id');
            });
            $lesson_requests->whereIn('lessons.lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['senpai_nickname'].'%');
            });
        }

        // ID（後輩）
        if (isset($condition['kouhai_user_no']) && $condition['kouhai_user_no']) {
            $lesson_requests->whereIn('lr_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['kouhai_user_no'].'%');
            });
        }
        // ニックネーム（後輩）
        if (isset($condition['kouhai_nickname']) && $condition['kouhai_nickname']) {
            $lesson_requests->whereIn('lr_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['kouhai_nickname'].'%');
            });
        }

        // 期間 (リクエストが送られた時間)
        if(isset($condition['from_date']) && $condition['from_date']) {
            $lesson_requests->whereDate('lr_request_date', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $lesson_requests->whereDate('lr_request_date', '<=', $condition['to_date']);
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $lesson_requests->orderByDesc('lr_request_date');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $lesson_requests->orderBy('lr_request_date');
            }
        }

        return $lesson_requests;
    }

    // リクエスト回答
    public static function getAnswerRequestInfo($condition) {
        $lesson_requests = LessonRequest::select('*');
        $lesson_requests->where('lr_state', '!=', config('const.req_state.request'));
        if (isset($condition['status']) && $condition['status']) {
            if($condition['status'] == config('const.lesson_request_status_code.reserve_response')) {
                $lesson_requests->where('lr_state', config('const.req_state.response'));
                $lesson_requests->where('lr_type', config('const.request_type.reserve'));
            } else if($condition['status'] == config('const.lesson_request_status_code.attendance_response')) {
                $lesson_requests->where('lr_state', config('const.req_state.response'));
                $lesson_requests->where('lr_type', config('const.request_type.attend'));
            } elseif($condition['status'] == config('const.lesson_request_status_code.reserve')) {
                $lesson_requests->where('lr_state', config('const.req_state.reserve'));
            } else if($condition['status'] == config('const.lesson_request_status_code.complete')) {
                $lesson_requests->where('lr_state', config('const.req_state.complete'));
            } else if($condition['status'] == config('const.lesson_request_status_code.cancel')) {
                $lesson_requests->where('lr_state', config('const.req_state.cancel'));
            } else {
                $lesson_requests->where('lr_state', '!=', config('const.req_state.request'));
            }
        }

        // ID（先輩）
        if (isset($condition['senpai_user_no']) && $condition['senpai_user_no']) {
            $lesson_requests->leftJoin('lessons', function ($join) {
                $join->on('lesson_requests.lr_lesson_id', 'lessons.lesson_id');
            });
            $lesson_requests->whereIn('lessons.lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['senpai_user_no'].'%');
            });
        }
        // ニックネーム（先輩）
        if (isset($condition['senpai_nickname']) && $condition['senpai_nickname']) {
            $lesson_requests->leftJoin('lessons', function ($join) {
                $join->on('lesson_requests.lr_lesson_id', 'lessons.lesson_id');
            });
            $lesson_requests->whereIn('lessons.lesson_senpai_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['senpai_nickname'].'%');
            });
        }

        // ID（後輩）
        if (isset($condition['kouhai_user_no']) && $condition['kouhai_user_no']) {
            $lesson_requests->whereIn('lr_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['kouhai_user_no'].'%');
            });
        }
        // ニックネーム（後輩）
        if (isset($condition['kouhai_nickname']) && $condition['kouhai_nickname']) {
            $lesson_requests->whereIn('lr_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['kouhai_nickname'].'%');
            });
        }

        // 期間 (リクエストが送られた時間)
        if(isset($condition['from_date']) && $condition['from_date']) {
            $lesson_requests->whereDate('lr_request_date', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $lesson_requests->whereDate('lr_request_date', '<=', $condition['to_date']);
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $lesson_requests->orderByDesc('lr_request_date');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $lesson_requests->orderBy('lr_request_date');
            }
        }

        return $lesson_requests;
    }

    public static function deleteLessonRequestBySystem($lesson_request_id, $reason) {
        /*$update_params = [
            'lr_state'=>config('const.req_state.cancel_system'),
        ];

        return LessonRequest::where('lr_id', $lesson_request_id)
            ->update($update_params);*/

        return LessonRequest::where('lr_id', $lesson_request_id)
            ->delete();
    }
}
