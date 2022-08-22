<?php

namespace App\Service;

use App\Models\Area;
use App\Models\RecruitArea;
use App\Models\Setting;
use App\Service\AreaService;
use App\Service\SettingService;
use App\Models\Recruit;
use App\Models\Proposal;
use App\Models\Question;
use App\Models\LessonClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Auth;


class KeijibannService
{

    // 取り消し予約一覧
    public static function doCancelStopRecruitSearch($condition=[]) {
        $recruits = Recruit::select('recruits.*')
            ->where('rc_stop', config('const.lesson_stop_code.stop_lesson'))
            ->whereNotNull('rc_stop_cancel_reverse_at');

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $recruits->orderByDesc('rc_stopped_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $recruits->orderBy('rc_stopped_at');
            }
        }

        return $recruits;
    }

    // 公開停止投稿
    public static function doStopRecruitSearch($condition=[]) {
        $recruits = Recruit::select('recruits.*')
            ->where('rc_stop', config('const.lesson_stop_code.stop_lesson'))
            ->whereNull('rc_stop_cancel_reverse_at');
        $recruits->leftJoin('users', function ($join) {
            $join->on('recruits.rc_user_id', 'users.id');
        });

        // カテゴリー
        if (isset($condition['category']) && $condition['category']) {
            $recruits->where("rc_class_id", $condition['category']);
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $recruits->whereIn('rc_id', function($query) use($condition) {
                $query->select('ra_recruit_id')
                    ->from('recruit_areas')
                    ->where('ra_deep2_id', $condition['area']);
            });
        }

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $recruits->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $recruits->whereDate('rc_stopped_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $recruits->whereDate('rc_stopped_at', '<=', $condition['to_date']);
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $recruits->whereRaw("name LIKE ?", '%'.$condition['nickname'].'%');
        }

        // ぴろしきまるのみ表示
        if(isset($condition['punishment']) && $condition['punishment']) {
            $recruits->whereIn('rc_user_id', function($query) use($condition) {
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
                $recruits->orderByDesc('rc_stopped_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $recruits->orderBy('rc_stopped_at');
            }
        }

        return $recruits;
    }

    public static function doCreateRecruit($data){
        $data_query = array();
        $data_query['rc_user_id'] = $data['user_id'];
        $data_query['rc_class_id'] = $data['lesson_classes'];
        $data_query['rc_title'] = $data['title'];
        $data_query['rc_date'] = $data['date'];
        $data_query['rc_start_time'] = $data['start_hour'] . ":" . $data['start_minute'];
        $data_query['rc_end_time'] = $data['end_hour'] . ":" . $data['end_minute'];
        $data_query['rc_lesson_period_from'] = $data['period_start'];
        /*$data_query['rc_lesson_period_to'] = $data['period_end'];*/
        $data_query['rc_man_num'] = $data['count_man'];
        $data_query['rc_woman_num'] = $data['count_woman'];
        $data_query['rc_wish_minmoney'] = $data['money_start'];
        $data_query['rc_wish_maxmoney'] = $data['money_end'];
        $data_query['rc_place'] = $data['lesson_place'];
        $data_query['rc_longitude'] = $data['longitude'];
        $data_query['rc_latitude'] = $data['latitude'];
        $data_query['rc_place_detail'] = $data['place_detail'];
        $data_query['rc_detail'] = $data['recruit_detail'];
        $data_query['rc_req_sex'] = $data['sex_hope'];
        $data_query['rc_req_age_from'] = !isset($data['age_start']) || is_null($data['age_start']) ? 0 : $data['age_start'];
        $data_query['rc_req_age_to'] =  !isset($data['age_end']) || is_null($data['age_end']) ? 100 : $data['age_end'];
        $data_query['rc_period'] = $data['recruit_date']." ".str_pad($data['period_hour'], 2, "0", STR_PAD_LEFT).':00';
        $data_query['rc_state'] = $data['state'];
        $mode = $data['mode'];
        $id = $data['recruit_id'];

        // map address
        $area_info = isset($data['map_location']) ? json_decode($data['map_location']) : [];

        if($mode == "input")
        {
            if($obj_recruit = Recruit::create($data_query))
            {
                if(count($area_info) > 0){
                    foreach ($area_info as $v){
                        self::doCreateRecruitArea($obj_recruit->rc_id, $v);
                    }
                }
                return $obj_recruit;
            } else {
                return null;
            }
        } else if($mode == "edit")      //edit
        {
            if (Recruit::where('rc_id', $id)->update($data_query)) {
                self::doDeleteRecruitArea($id);
                if(count($area_info) > 0){
                    foreach ($area_info as $v){
                        self::doCreateRecruitArea($id, $v);
                    }
                }
            }

        } else      //$mode == "delete"
        {
            Recruit::destroy($id);
        }
    }

    public static function doCreateProposal($data)
    {
        $data_query = array();
        $data_query['pro_user_id'] = Auth::user()->id;
        $data_query['pro_rc_id'] = $data['rc_id'];
        $data_query['pro_money'] = $data['prop_money'];
        /*$data_query['pro_traffic_fee'] = isset($data['traffic_fee']) ? $data['traffic_fee'] : 0;*/
        $data_query['pro_start_time'] = $data['start_hour'] . ":" . $data['start_minute'];
        $data_query['pro_end_time'] = $data['end_hour'] . ":" . $data['end_minute'];
        $data_query['pro_msg'] = $data['message'];
        $data_query['pro_buy_datetime'] = date("Y") . "-" . $data['buy_month'] . "-" . $data['buy_day'] . " " .$data['buy_hour'] . ":" . $data['buy_minute'] . ":00";
        $data_query['pro_state'] =  config('const.prop_state.request');
        $data_query['pro_fee_type'] =  isset($data['fee_type']) ? $data['fee_type'] : config('const.fee_type.c');
        $data_query['pro_fee'] =  isset($data['price_mark']) ? $data['price_mark'] : 0;
        $data_query['pro_request_time'] =  date('Y-m-d H:i:s', time());
        $data_query['pro_lesson_period_start'] = $data['period_start'];
        /*$data_query['pro_lesson_period_end'] = $data['period_end'];*/
        $data_query['pro_service_fee'] =  $data['prop_money'] * SettingService::getSetting('service_fee_percent', 'int') / 100;

        $mode = $data['mode'];
        if($mode == "input")
        {
            if($obj_prop = Proposal::create($data_query))
            {
                return $obj_prop;
            } else {
                return null;
            }
        } else //"edit"
        {
            $id = $data['pro_id'];
            Proposal::where('pro_id', $id)
                ->update($data_query);
            return Proposal::where('pro_id', $id)->first();
        }
    }

    public static function incRecruitView($rc_id)
    {
        Recruit::where('rc_id', $rc_id)
        ->increment('rc_views');
    }


    //$rc_user_id == 0 => all
    //$rc_user_id == -1 => none
    //$rc_user_id > 0 => his or her

    //$rc_state == -1 => all
    public static function getCruitDetails($rc_state, $rc_id = 0, $rc_user_id = 0, $except_stop=null)
    {
        if($rc_id == 0)
        {
            $recruits = Recruit::with('cruitLesson')
                ->with('cruitUser.area');
        } else
        {
            $recruits = Recruit::where('rc_id', $rc_id)
                ->with('cruitLesson')
                ->with('cruitUser.area');
        }

        if($rc_state > config('const.recruit_state.all'))
        {
            $recruits->where('rc_state', $rc_state);
        }

        if (isset($except_stop) && $except_stop) {
            $recruits->where('rc_stop', '!=', config('const.lesson_stop_code.stop_lesson'));
        }

        if($rc_user_id != 0)
        {
            $recruits->where('rc_user_id', $rc_user_id);
        }

        return $recruits;
    }

    public static function doSearchRecruit($data)
    {
        /*$data = ['province_id'=>'21', 'order'=>1,
            'area_id'=>2, 'date'=>'2022-06-20', 'start_hour'=>0, 'start_minute'=>0,
            'end_hour'=>23,
            'end_minute'=>0,
            'start_interval'=>15,
            'end_interval'=>300,
            'start_fee'=>1000,
            'recruit_date'=>'2022-06-20',
            'end_fee'=>3000,
            'period_month'=>3,
            'period_day'=>15,
            'period_hour'=>16
        ];*/
        if(isset($data['order']) && !is_null($data['order']))
        {
            $order = $data['order'];
            if($order == config('const.recruit_order.fav'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->withCount(['favourite as fav_count' => function (Builder $query){
                    $query->where('fav_type',  config('const.fav_type.recruit'));
                }])->orderBy('fav_count', 'DESC');
            } else if($order == config('const.recruit_order.new'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderByDesc('created_at');
            } else if($order == config('const.recruit_order.unit_price'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderByDesc(
                        DB::raw('rc_wish_maxmoney IS NULL')
                    )
                    ->orderByDesc('rc_wish_maxmoney');
            } else if($order == config('const.recruit_order.payment'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderByDesc(
                        DB::raw('rc_wish_maxmoney IS NULL')
                    )
                    ->orderByDesc(
                        DB::raw('rc_lesson_period_from*rc_wish_maxmoney')
                    );
            } else if($order == config('const.recruit_order.remain_time_large'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderByDesc('rc_period');
            } else if($order == config('const.recruit_order.remain_time_small'))
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderBy('rc_period');
            } else  // $order == old
            {
                $recruits = Recruit::with('cruitLesson')
                    ->with('cruitUser.area')
                    ->orderBy('created_at', 'asc');
            }
        }

        if(isset($data['category_id']) && !is_null($data['category_id']))
        {
            $recruits->whereIn('rc_class_id', $data['category_id']);
        }

        $sex = config('const.sex.uncertain');
        if(isset($data['sex']) && !is_null($data['sex']))
        {
            if($data['sex'] != config('const.sex.uncertain'))
            {
                $sex = $data['sex'];
                $recruits->where('rc_req_sex', $data['sex']);
            }
        }

        /*if (isset($data['start_age']) && !empty($data['start_age'])) {
            $recruits->where('rc_req_age_from', '>=', $data['start_age']);
        }
        if (isset($data['end_age']) && !empty($data['end_age'])) {
            $recruits->where('rc_req_age_to', '<=', $data['end_age']);
        }*/

        $min_age = 0;
        $max_age = 100;
        if (isset($data['start_age']) && $data['start_age']) {
            $min_age = $data['start_age'];
        }

        if (isset($data['end_age']) && $data['end_age']) {
            $max_age = $data['end_age'];
        }

        // 年代
        $recruits->where(function ($query_origin) use ($min_age, $max_age) {
            $query_origin->where(function ($query) use ($min_age, $max_age) {
                $query->where('rc_req_age_from', '<=', $max_age)
                    ->where('rc_req_age_from','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($min_age, $max_age) {
                $query->where('rc_req_age_to', '<=', $max_age)
                    ->where('rc_req_age_to','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($min_age) {
                $query->where('rc_req_age_from', '<=', $min_age)
                    ->where('rc_req_age_to','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($max_age) {
                $query->where('rc_req_age_from', '<=', $max_age)
                    ->where('rc_req_age_to','>=', $max_age);
            });
        });

        if(isset($data['numbers']) && !is_null($data['numbers']))
        {
            if($data['numbers'] > 0)
            {
                $numbers = $data['numbers'];
                if($sex == config('const.sex.woman'))
                {
                    $recruits->where('rc_woman_num' , $numbers);
                } else if($sex == config('const.sex.man'))
                {
                    $recruits->where('rc_man_num', $numbers);
                } else
                {
                    $recruits->havingRaw('sum(rc_man_num + rc_woman_num) = '.$numbers);
                }
            }
        }

        if(isset($data['province_id']) && !empty($data['province_id'])){
            $province_id = $data['province_id'];
            $recruits = $recruits->whereHas('recruit_area', function ($query) use ($province_id) {
                $query->where('ra_deep2_id', $province_id);
            });
        }

        // エリア
        /*if(isset($data['area_id_2']) && !is_null($data['area_id_2']) && $data['area_id_2'] > 0)
        {
            $area_deep2 = $data['area_id_2'];
            $recruits = $recruits->whereHas('recruit_area', function($query) use($area_deep2){
                $query->where('ra_deep2_id', $area_deep2);
            });

        }*/

        if(isset($data['area_id_arr']) && !is_null($data['area_id_arr']) && count($data['area_id_arr']) > 0)
        {
            $area_deep3 = $data['area_id_arr'];
            $recruits = $recruits->whereHas('recruit_area', function($query) use($area_deep3){
                $query->whereIn('ra_deep3_id', $area_deep3);
            });

        }

        // レッスン日時
        if(isset($data['date']) && $data['date']) {
            $period_compare_start = Carbon::create(Carbon::parse($data['date'])->format('Y'), Carbon::parse($data['date'])->format('m'), Carbon::parse($data['date'])->format('d'), $data['start_hour'], $data['start_minute'])->format('Y-m-d H:i');
            $period_compare_end = Carbon::create(Carbon::parse($data['date'])->format('Y'), Carbon::parse($data['date'])->format('m'), Carbon::parse($data['date'])->format('d'), $data['end_hour'], $data['end_minute'])->format('Y-m-d H:i');

            $recruits->where(function ($query_origin) use ($period_compare_start, $period_compare_end) {
                $query_origin->where(function ($query) use ($period_compare_start, $period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_start, $period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_start) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_start)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_end);
                });
            });

        }
        /*if(isset($data['date']) && $data['date']) {
            $recruits->where('rc_date', $data['date']);
        }
        if(isset($data['start_hour']) && isset($data['start_minute'])) {
            $recruits->whereTime('rc_start_time', '>=', $data['start_hour'].':'.$data['start_minute']);
        }

        if(isset($data['end_hour']) && isset($data['end_minute'])) {
            $recruits->whereTime('rc_end_time', '<=', $data['end_hour'].':'.$data['end_minute']);
        }*/

        // レッスン時間
        if(isset($data['start_interval']) && $data['start_interval'] && isset($data['end_interval']) && $data['end_interval']) {
            $recruits->where('rc_lesson_period_from', '>=', $data['start_interval']);
            $recruits->where('rc_lesson_period_from', '<=', $data['end_interval']);
        } else if(isset($data['start_interval']) && $data['start_interval']) {
            $recruits->where('rc_lesson_period_from', '>=', $data['start_interval']);
        } else if(isset($data['end_interval']) && $data['end_interval']) {
            $recruits->where('rc_lesson_period_from', '<=', $data['end_interval']);
        }

        $min_fee = 0;
        $max_fee = 100000;
        if (isset($data['start_fee']) && $data['start_fee']) {
            $min_fee = $data['start_fee'];
        }

        if (isset($data['end_fee']) && $data['end_fee']) {
            $max_fee = $data['end_fee'];
        }

        // 料金
        $recruits->where(function ($query_origin) use ($min_fee, $max_fee) {
            $query_origin->where(function ($query) use ($min_fee, $max_fee) {
                $query->where('rc_wish_minmoney', '<=', $max_fee)
                    ->where('rc_wish_minmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($min_fee, $max_fee) {
                $query->where('rc_wish_maxmoney', '<=', $max_fee)
                    ->where('rc_wish_maxmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($min_fee) {
                $query->where('rc_wish_minmoney', '<=', $min_fee)
                    ->where('rc_wish_maxmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($max_fee) {
                $query->where('rc_wish_minmoney', '<=', $max_fee)
                    ->where('rc_wish_maxmoney','>=', $max_fee);
            });
        });

        // 募集期限
        if(isset($data['period_hour']) && $data['period_hour']) {
            // add 1 hour
            $period_compare_date = Carbon::create(Carbon::parse($data['recruit_date'])->format('Y'), Carbon::parse($data['recruit_date'])->format('m'), Carbon::parse($data['recruit_date'])->format('d'), $data['period_hour'], 0)->format('Y-m-d H:i');
            $recruits->where("rc_period", '<=', $period_compare_date);
        }

        // 募集期限が過ぎたものは「掲示板」の「すべて」から削除する。
        $recruits->where("rc_period", '>=', Carbon::now()->format('Y-m-d H:i:00'));

        // not 公開停止, レッスンを中断
        $recruits->where("rc_stop", '!=', config('const.lesson_stop_code.stop_lesson'));
        $recruits->where("rc_stop", '!=', config('const.lesson_stop_code.break_lesson'));


        if(isset($data['keyword']) && !is_null($data['keyword']))
        {
            $keyword = $data['keyword'];
            $recruits->where(function($query) use ($keyword) {
                $query
                    ->where('rc_title', 'like', '%' . $keyword . '%')
                    ->orWhere('rc_place', 'like' , '%' . $keyword . '%')
                    ->orWhere('rc_place_detail', 'like' , '%' . $keyword . '%')
                    ->orWhere('rc_detail', 'like' , '%' . $keyword . '%');
            });

            $recruits->where('rc_date', '>=', $data['keyword']);
        }

        if (isset($data['except_user_id']) && $data['except_user_id']) {
            $recruits->where('rc_user_id', '!=', $data['except_user_id']);
        }

        $recruits->where('rc_state', config('const.recruit_state.recruiting'));
        return $recruits;
    }

    public static function doSearchRecruitAdmin($condition)
    {
        $recruits = Recruit::whereNull('deleted_at');

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $recruits->orderByDesc('rc_period');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $recruits->orderBy('rc_period');
            }
        }

        // カテゴリー
        if (isset($condition['category']) && $condition['category']) {
            $recruits->where("rc_class_id", $condition['category']);
        }

        // 都道府県
        if(isset($condition['province_id']) && !empty($condition['province_id'])){
            $province_id = $condition['province_id'];
            $recruits = $recruits->whereHas('recruit_area', function ($query) use ($province_id) {
                $query->where('ra_deep2_id', $province_id);
            });
        }

        // エリア
        if(isset($condition['area_id']) && !is_null($condition['area_id']) && $condition['area_id'])
        {
            $area_ids = explode(',', $condition['area_id']);
            $recruits = $recruits->whereHas('recruit_area', function ($query) use ($area_ids) {
                $query->whereIn('ra_deep3_id', $area_ids);
            });

        }

        // レッスン開始日時
        if(isset($condition['date']) && $condition['date']) {
            $period_compare_start = Carbon::create(Carbon::parse($condition['date'])->format('Y'), Carbon::parse($condition['date'])->format('m'), Carbon::parse($condition['date'])->format('d'), $condition['start_hour'], $condition['start_minute'])->format('Y-m-d H:i');
            $period_compare_end = Carbon::create(Carbon::parse($condition['date'])->format('Y'), Carbon::parse($condition['date'])->format('m'), Carbon::parse($condition['date'])->format('d'), $condition['end_hour'], $condition['end_minute'])->format('Y-m-d H:i');

            $recruits->where(function ($query_origin) use ($period_compare_start, $period_compare_end) {
                $query_origin->where(function ($query) use ($period_compare_start, $period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_start, $period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_start) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_start)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_start);
                });
                $query_origin->orWhere(function ($query) use ($period_compare_end) {
                    $query->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_start_time), '%Y-%m-%d %H:%i') <= ?", $period_compare_end)
                        ->whereRaw("DATE_FORMAT(CONCAT(rc_date, ' ', rc_end_time), '%Y-%m-%d %H:%i') >= ?", $period_compare_end);
                });
            });

        }

        // レッスン時間
        if(isset($condition['start_interval']) && $condition['start_interval'] && isset($condition['end_interval']) && $condition['end_interval']) {
            $recruits->where('rc_lesson_period_from', '>=', $condition['start_interval']);
            $recruits->where('rc_lesson_period_from', '<=', $condition['end_interval']);
        } else if(isset($condition['start_interval']) && $condition['start_interval']) {
            $recruits->where('rc_lesson_period_from', '>=', $condition['start_interval']);
        } else if(isset($condition['end_interval']) && $condition['end_interval']) {
            $recruits->where('rc_lesson_period_from', '<=', $condition['end_interval']);
        }

        $min_fee = 0;
        $max_fee = 100000;
        if (isset($condition['start_fee']) && $condition['start_fee']) {
            $min_fee = $condition['start_fee'];
        }

        if (isset($condition['end_fee']) && $condition['end_fee']) {
            $max_fee = $condition['end_fee'];
        }

        // 料金
        $recruits->where(function ($query_origin) use ($min_fee, $max_fee) {
            $query_origin->where(function ($query) use ($min_fee, $max_fee) {
                $query->where('rc_wish_minmoney', '<=', $max_fee)
                    ->where('rc_wish_minmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($min_fee, $max_fee) {
                $query->where('rc_wish_maxmoney', '<=', $max_fee)
                    ->where('rc_wish_maxmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($min_fee) {
                $query->where('rc_wish_minmoney', '<=', $min_fee)
                    ->where('rc_wish_maxmoney','>=', $min_fee);
            });
            $query_origin->orWhere(function ($query) use ($max_fee) {
                $query->where('rc_wish_minmoney', '<=', $max_fee)
                    ->where('rc_wish_maxmoney','>=', $max_fee);
            });
        });

        // 募集期限
        if(isset($condition['recruit_date']) && $condition['recruit_date']) {
            // add 1 hour
            $period_compare_date = Carbon::create(Carbon::parse($condition['recruit_date'])->format('Y'), Carbon::parse($condition['recruit_date'])->format('m'), Carbon::parse($condition['recruit_date'])->format('d'), $condition['period_hour'], 0)->format('Y-m-d H:i');
            $recruits->where("rc_period", '<=', $period_compare_date);
        }

        $sex = config('const.sex.uncertain');
        if(isset($condition['user_sex']) && !is_null($condition['user_sex']))
        {
            if($condition['user_sex'] != config('const.sex.uncertain'))
            {
                $sex = $condition['user_sex'];
                $recruits->where('rc_req_sex', $condition['user_sex']);
            }
        }

        $min_age = 0;
        $max_age = 100;
        if (isset($condition['start_age']) && $condition['start_age']) {
            $min_age = $condition['start_age'];
        }

        if (isset($condition['end_age']) && $condition['end_age']) {
            $max_age = $condition['end_age'];
        }

        // 年代
        $recruits->where(function ($query_origin) use ($min_age, $max_age) {
            $query_origin->where(function ($query) use ($min_age, $max_age) {
                $query->where('rc_req_age_from', '<=', $max_age)
                    ->where('rc_req_age_from','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($min_age, $max_age) {
                $query->where('rc_req_age_to', '<=', $max_age)
                    ->where('rc_req_age_to','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($min_age) {
                $query->where('rc_req_age_from', '<=', $min_age)
                    ->where('rc_req_age_to','>=', $min_age);
            });
            $query_origin->orWhere(function ($query) use ($max_age) {
                $query->where('rc_req_age_from', '<=', $max_age)
                    ->where('rc_req_age_to','>=', $max_age);
            });
        });

        // 参加人数
        if(isset($condition['numbers']) && !is_null($condition['numbers']))
        {
            if($condition['numbers'] > 0)
            {
                $numbers = $condition['numbers'];
                if($sex == config('const.sex.woman'))
                {
                    $recruits->where('rc_woman_num' , $numbers);
                } else if($sex == config('const.sex.man'))
                {
                    $recruits->where('rc_man_num', $numbers);
                } else
                {
                    $recruits->havingRaw('sum(rc_man_num + rc_woman_num) = '.$numbers);
                }
            }
        }

        if (isset($condition['piro']) && $condition['piro']) {
            $recruits->whereHas('cruitUser', function ($query) {
                $query->where('punishment_cnt', '>', 0);
                $query->where('caution_cnt', '>', 0);
            });
        }

        // 募集期限が過ぎたものは「掲示板」の「すべて」から削除する。
        /*$recruits->where("rc_period", '>=', Carbon::now()->format('Y-m-d H:i:00'));*/

        return $recruits;
    }

    // レッスン履歴=>掲示板履歴
    public static function doRecruitHistorySearch($condition)
    {
        $recruits = Recruit::select('recruits.*');

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.stop_lesson_sort_code.register_new')) {
                $recruits->orderByDesc('recruits.created_at');
            } else if($condition['order'] == config('const.stop_lesson_sort_code.register_old')) {
                $recruits->orderBy('recruits.created_at');
            }
        }

        // ステータス
        if (isset($condition['status']) && $condition['status']) {
            switch ($condition['status']) {
                case config('const.recruit_history_status_code.complete'):
                    $recruits->where('rc_state', config('const.recruit_state.complete'));
                    break;
                case config('const.recruit_history_status_code.reserve_suggest'):
                    $recruits->where(function($q) {
                        $q->where('rc_state', config('const.recruit_state.request'));
                        $q->orWhere('rc_state', config('const.recruit_state.recruiting'));
                    });
                    break;
                case config('const.recruit_history_status_code.reserve_cancel_complete'):
                    $recruits->where('rc_state', config('const.recruit_state.cancel'));
                    break;
                case config('const.recruit_history_status_code.has_suggest'):
                    $recruits->leftJoin('proposals', function($join) {
                        $join->on('recruits.rc_id', '=', 'proposals.pro_rc_id');
                    });
                    $recruits->where('proposals.pro_state', config('const.prop_state.complete'));
                    $recruits->groupBy('rc_id')->whereNull('proposals.deleted_at');
                    break;
                case config('const.recruit_history_status_code.reserve_not_success'):
                    $recruits->where('rc_state', config('const.recruit_state.past'));
                    $recruits->whereRaw(
                        'rc_id not in ('.DB::raw('select pro_rc_id from proposals where deleted_at is null and pro_state = '.config('const.prop_state.complete')).')'
                    );
                    break;
                default:
                    break;
            }
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $recruits->whereIn('rc_id', function($query) use($condition) {
                $query->select('ra_recruit_id')
                    ->from('recruit_areas')
                    ->where('ra_deep2_id', $condition['area']);
            });
        }

        // ID
        if (isset($condition['kouhai_user_no']) && $condition['kouhai_user_no']) {
            $recruits->whereIn('rc_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("user_no", 'like', '%'.$condition['kouhai_user_no'].'%');
            });
        }
        // ニックネーム
        if (isset($condition['kouhai_nickname']) && $condition['kouhai_nickname']) {
            $recruits->whereIn('rc_user_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where("name", 'like', '%'.$condition['kouhai_nickname'].'%');
            });
        }

        // 期間 (リクエストが送られた時間)
        if(isset($condition['from_date']) && $condition['from_date']) {
            $recruits->whereDate('recruits.updated_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $recruits->whereDate('recruits.updated_at', '<=', $condition['to_date']);
        }

        return $recruits;
    }

    public static function getRecruitActHistories($rc_id)
    {
        $obj_recruit = Recruit::find($rc_id);
        $propose_info = [];
        $propose_complete_info = [];
        foreach ($obj_recruit->recruit_proposal as $propose) {
            $propose_info[] = Carbon::parse($propose->pro_request_time)->format('Y年m月d日 H:i 提案有');
            if ($propose->pro_state == config('const.prop_state.complete')) {
                $propose_complete_info[] = Carbon::parse($propose->pro_complete_time)->format('Y年m月d日 H:i 購入済み');
            }
        }
        return array_merge($propose_info, $propose_complete_info);
    }

    public static function getCountProposals($rc_id)
    {
        return Proposal::where('pro_rc_id', $rc_id)->where('pro_state', '!=', config('const.prop_state.request'))->count();
    }

    public static function getProposalByUser($user_id = 0)
    {
        $proposals = Proposal::orderByDesc('created_at');
        if($user_id != 0)
        {
            $proposals->where('pro_user_id', $user_id);
        }
        return $proposals;
    }

    public static function getProposalById($id = 0)
    {
        $proposals = Proposal::orderByDesc('created_at');
        if($id != 0)
        {
            $proposals->where('pro_id', $id);
        }
        return $proposals->first();
    }

    public static function delProposal($id)
    {
        Proposal::where('pro_id', $id)
            ->delete();
    }

    public static function updatePropState($prop_id, $state)
    {
        $data['pro_state'] = $state;
        Proposal::where('pro_id', $prop_id)
            ->update($data);
    }

    public static function isProposed($rc_id, $user_id)
    {
        $prop_info = Proposal::where('pro_rc_id', $rc_id);
        if($user_id > 0)
        {
            $prop_info->where('pro_user_id', $user_id);
        }
        return $prop_info->first();

    }

    public static function updateRecruitOldState()
    {
        $data['rc_state'] = config('const.recruit_state.past');
        Recruit::where('rc_period', '<', date('Y-m-d', time()))->update($data);
    }

    public static function getPropFeeType($senpai_id)
    {
        $proposal = Proposal::where('pro_user_id', Auth::user()->id)
        ->with('recruit', function($query) use($senpai_id){
            $query->where('rc_user_id', $senpai_id);
        })->orderByDesc('pro_complete_time')->first();

        if(!isset($proposal))
            return config('const.fee_type.c');

        if(!isset($proposal['pro_complete_time']))
            return config('const.fee_type.c');

        $date_diff = time() - strtotime($proposal['pro_complete_time']);

        if(floor($date_diff / 86400) >= SettingService::getSetting('fee_type_c_days', 'int'))
            return config('const.fee_type.c');

        return config('const.fee_type.b');
    }

    public static function doDeleteRecruitArea($recruit_id){
        $model = RecruitArea::where('ra_recruit_id', $recruit_id);
        if($model->count() >0)
            return $model->delete();
    }

    public static function doCreateRecruitArea($recruit_id, $area_info){
        $data = array();
        $data['ra_recruit_id'] = $recruit_id;
        // deep1, deep2
        $prefecture = Area::where('area_deep', config('const.area_deep_code.pref'))
            ->where('area_name', $area_info->prefecture)
            ->first();
        if (is_object($prefecture)) {
            $data['ra_deep1_id'] = $prefecture->area_region;
            $data['ra_deep2_id'] = $prefecture->area_id;
        }
        // deep3
        $locality = Area::where('area_deep', config('const.area_deep_code.city'))
            ->where('area_name', $area_info->county.$area_info->locality.$area_info->sublocality)
            ->first();
        if (is_object($locality)) {
            $data['ra_deep3_id'] = $locality->area_id;
        }
        // position
        $data['position'] = json_encode($area_info, JSON_UNESCAPED_UNICODE);

        return RecruitArea::create($data);
    }

    // 公開停止の取り消し
    public static function stopRecruitCancel($params)
    {
        $obj_recruit = Recruit::find($params['recruit_id']);
        if (!is_object($obj_recruit)) {
            return false;
        }

        if ($params['radio-cancel'] == config('const.stop_lesson_cancel.now')) { // 今すぐ公開停止を取り消す
            $obj_recruit->rc_stop = config('const.lesson_stop_code.no_stop_lesson');
        } else { // 公開停止の取り消しを予約する
            $obj_recruit->rc_stop_cancel_reverse_at =  Carbon::create($params['cancel_date']." ".$params['cancel_hour'].":".$params['cancel_minute'])->format('Y-m-d H:i:s');
        }
        return $obj_recruit->save();
    }

    // 公開停止の取り消し
    public static function cancelStopRecruitCancel($params)
    {
        $obj_recruit = Recruit::find($params['recruit_id']);
        if (!is_object($obj_recruit)) {
            return false;
        }

        $obj_recruit->rc_stop_cancel_reverse_at = null;
        return $obj_recruit->save();
    }

    public static function stopRecruit($condition)
    {
        $obj_recruit = Recruit::find($condition['recruit_id']);
        if (!is_object($obj_recruit)) {
            return false;
        }

        $obj_recruit->rc_state = config('const.recruit_state.past');
        $obj_recruit->rc_stop = config('const.lesson_stop_code.stop_lesson');
        $obj_recruit->rc_stopped_at = Carbon::now()->format('Y-m-d H:i:s');
        return $obj_recruit->save();
    }

    // 管理画面の掲示板履歴のこのレッスンを中断する
    public static function breakRecruitBySystem($recruit, $reason) {
        $update_params = [
            'rc_stop'=>config('const.lesson_stop_code.break_lesson'),
            'rc_break_at'=>date('Y-m-d H:i:s')
        ];

        return Recruit::where('rc_id', $recruit)
            ->update($update_params);
    }
}
