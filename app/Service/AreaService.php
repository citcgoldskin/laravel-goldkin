<?php

namespace App\Service;

use App\Models\Lesson;
use App\Models\RecruitArea;
use App\Service\BaseCateService;
use App\Models\Area;
use App\Models\LessonArea;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class AreaService extends BaseCateService
{
    public $_table = "areas";
    public $_id = "area_id";
    public $_name = "area_name";
    public $_parent = "area_parent_id";

    public static function getArea($area_id) {
        return Area::where('area_id', $area_id)
            ->first();
    }

    public static function getAreaNames($area_ids) {
        $area_ids_arr = explode(',', $area_ids);
        $area_names = Area::select('area_name')
            ->whereIn('area_id', $area_ids_arr)
            ->get()
            ->pluck('area_name');
        if(is_object($area_names) && count($area_names) > 0){
            $ret = $area_names->toArray();
            $result = "";
            foreach ($ret as $key=>$val) {
                $result .= $val;
                if ($key != count($area_names) - 1) {
                    $result .= '、';
                }
            }

            return $result;
        }
        return "";
    }

    //get regions
    public static function getTopAreaList() {
        return Area::orderBy('area_code')
            ->where('area_deep', config('const.area_deep_code.region'))
            ->get();
    }

    //get prefectures by region
    public static function getPrefectureList($region_id=null) {
        $prefectures =  Area::where('area_deep', config('const.area_deep_code.pref'));
        if(!is_null($region_id)) {
            $prefectures->where('area_region', $region_id);
        }
        return $prefectures->get();
    }


    //get regions and prefectures
    public static function getRegionAndPrefectures($region_id=null) {
        $region_prefs =  Area::where(function($query) {
            $query->where('area_deep', config('const.area_deep_code.pref'))
                ->orWhere('area_deep', config('const.area_deep_code.region'));
        });

        if(!is_null($region_id)) {
            $region_prefs->where(function ($query) use ($region_id){
                $query->where(function ($q) use ($region_id){
                    $q->where('area_deep', config('const.area_deep_code.region'))
                        ->where('id', $region_id);
                });
                $query->orWhere(function ($q) use ($region_id){
                    $q->where('area_deep', config('const.area_deep_code.pref'))
                    ->where('area_region', $region_id);
                });
            });
        }
        $obj_areas = $region_prefs->get();

        $arr_areas = [];
        foreach ($obj_areas as $obj_area) {
            if($obj_area->area_deep == config('const.area_deep_code.region')) {
                //region
                $arr_areas[$obj_area->area_id]['region'] = $obj_area->area_name;
            } else {
                //prefecture
                $arr_areas[$obj_area->area_region]['child'][$obj_area->area_id] = $obj_area->area_name;
            }
        }

        return $arr_areas;
    }

    // get area
    public static function getAreaByPrefecture($pref_id) {
        return Area::orderBy('area_code')
            ->where('area_pref', $pref_id)
            ->get();
    }

    public static function getNewLowerAreaList($id, $type=null, $deep=null) {
        $result = Area::orderBy('area_code');
        if (!is_null($type)) {
            $result->where($type, $id);
        }
        if (!is_null($deep)) {
            $result->where('area_deep', $deep);
        }

        return $result->get();
    }



    public static function getLowerAreaList($id) {
        return Area::orderBy('area_code')
            ->where('area_parent_id', $id)
            ->get();
    }

    public static function getSecondAreaList() {
        return Area::orderBy('area_code')
            ->where('area_deep', 2)
            ->get();
    }

    public static function getAreaDeep($area_id) {
        $area =  Area::where('area_id', $area_id)
            ->first();
        if(empty($area)){
            return 0;
        }else{
            return $area['area_deep'];
        }
    }

    public static function getChildrenIds($area_id) {
        $result = array();
        $list = self::getLowerAreaList($area_id);
        if ($list) {
            foreach ( $list as $v ) {
                $result[] = $v['area_id'];
                $result = array_merge($result, self::getChildrenIDs($v['area_id']));
            }
        }
        return $result;
    }


    public static function getOneArea($id) {
        return Area::orderBy('area_code')
            ->where('area_id', $id)
            ->select('area_name')
            ->first();
    }

    public static function getOneAreaFullName($id) {
        $area =  Area::where('area_id', $id)
            ->first();
        if(!empty($area)){
            $parent_area = Area::orderBy('area_code')
                ->where('area_id', $area['area_parent_id'])
                ->select('area_id', 'area_name')
                ->first();
            if(!empty($parent_area) && $area['area_deep'] == 4){
                return $parent_area['area_name'].$area['area_name'];
            }else{
                return $area['area_name'];
            }
        }
        return '';
    }

    public static function getLessonCountListByArea($area_deep, $except_user_id=0) {
        /*$areas_counts = DB::select(DB::raw('select count(areas.area_id) as cnt, `areas`.`area_id`
            from `areas`
            left join (select distinct `lesson_areas`.`la_deep2_id` as `la_ar_id`, `lesson_areas`.`la_lesson_id` as `la_ls_id`
                from `lesson_areas`
                left join `lessons` on `lessons`.`lesson_id` = `lesson_areas`.`la_lesson_id`
                where `lessons`.`lesson_state` = '. config('const.lesson_state.public').' and `lessons`.`lesson_senpai_id` != '.$except_user_id . '
            ) AS ls_areas on `ls_areas`.`la_ar_id` = `areas`.`area_id`
            where `ls_areas`.`la_ls_id` is not null and `areas`.`deleted_at` is null
            group by `areas`.`area_id`'));*/

        // 出品者本人のサービスが表示されていないので表示をしてほしい。但し、自分ではそのレッスンには予約リクエスト・出勤リクエストできないようにする。
        $areas_counts = DB::select(DB::raw('select count(areas.area_id) as cnt, `areas`.`area_id`
            from `areas`
            left join (select distinct `lesson_areas`.`la_deep2_id` as `la_ar_id`, `lesson_areas`.`la_lesson_id` as `la_ls_id`
                from `lesson_areas`
                left join `lessons` on `lessons`.`lesson_id` = `lesson_areas`.`la_lesson_id`
                where `lessons`.`lesson_state` = '. config('const.lesson_state.public').'
            ) AS ls_areas on `ls_areas`.`la_ar_id` = `areas`.`area_id`
            where `ls_areas`.`la_ls_id` is not null and `areas`.`deleted_at` is null
            group by `areas`.`area_id`'));
        $arr_cnt = [];
        if($areas_counts) {
            foreach ($areas_counts as $areas) {
                $arr_cnt[$areas->area_id] = $areas->cnt;
            }
        }

        return $arr_cnt;
    }

    public static function getRecruitCountListByArea($area_deep, $except_user_id=0) {
        $areas_counts = DB::select(DB::raw('select count(areas.area_id) as cnt, `areas`.`area_id`
            from `areas`
            left join (select distinct `recruit_areas`.`ra_deep2_id` as `ra_ar_id`, `recruit_areas`.`ra_recruit_id` as `ra_rc_id`
                from `recruit_areas`
                left join `recruits` on `recruits`.`rc_id` = `recruit_areas`.`ra_recruit_id`
                where `recruits`.`rc_state` = '. config('const.recruit_state.recruiting').' and `recruits`.`rc_user_id` != '.$except_user_id . '
            ) AS rs_areas on `rs_areas`.`ra_ar_id` = `areas`.`area_id`
            where `rs_areas`.`ra_rc_id` is not null and `areas`.`deleted_at` is null
            group by `areas`.`area_id`'));
        $arr_cnt = [];
        if($areas_counts) {
            foreach ($areas_counts as $areas) {
                $arr_cnt[$areas->area_id] = $areas->cnt;
            }
        }

        return $arr_cnt;
    }

    public static function getLessonCountByArea($area_id, $except_user_id=0) {
        $area_deep = self::getAreaDeep($area_id);
        if($area_deep == 0)
            return 0;
        return LessonArea::where('la_deep'.$area_deep.'_id', $area_id)
            ->wherehas('lesson', function ($query) use($except_user_id){
        $query->where('lesson_state',  config('const.lesson_state.public'));
        $query->where('lesson_senpai_id', '!=', $except_user_id);
        })->count();
    }

    public static function getRecruitCountByArea($area_id, $except_user_id=0) {
        $area_deep = self::getAreaDeep($area_id);
        if($area_deep == 0)
            return 0;
        return RecruitArea::where('ra_deep'.$area_deep.'_id', $area_id)
            ->wherehas('recruit', function ($query) use($except_user_id) {
        $query->where('rc_state',  config('const.recruit_state.recruiting'));
        $query->where('rc_user_id', '!=', $except_user_id );
        })->count();
    }

    public static function getAreaIdByName($area_name) {
        $name_arr = explode(' ', $area_name);
        $area_name = end($name_arr);
        $area =  Area::where('area_name', $area_name)
            ->first();
        if(!empty($area)){
            return intval($area['area_id']);
        }
        return 0;
    }

}
