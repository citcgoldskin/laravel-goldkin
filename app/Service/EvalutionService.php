<?php

namespace App\Service;

use App\Models\Evalution;
use App\Models\EvalutionType;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class EvalutionService
{
    public const SENPAIS_EVAL = 0;
    public const KOUHAIS_EVAL = 1;

    public const No_EVAL = 0;
    public const YES_EVAL = 1;

    public static function doCreate($data)
    {
        $obj = Evalution::create($data);
        if($obj)
            return $obj;
        return false;
    }

    public static function getEvalutionTypes($kind)
    {
        $results = EvalutionType::where('et_kind', $kind)
            ->orderBy('et_sort')
            ->get();
        return $results;
    }

    public static function getLessonEvalutionCount($lesson_id, $type)
    {
        return Evalution::selectRaw('count(distinct eval_schedule_id) as cnt_eval')->where('eval_kind', $type)
            ->where('eval_lesson_id', $lesson_id)
            ->groupBy('eval_lesson_request_id')
            ->get()
            ->count();
    }

    public static function getLessonEvalutionPercentByType($lesson_id, $type)
    {
        $eval_total_count = Evalution::selectRaw('count(distinct eval_schedule_id) as cnt_eval')
            ->where('eval_kind', $type)
            ->where('eval_lesson_id', $lesson_id)
            ->groupBy('eval_schedule_id')
            ->get()
            ->count();
        $eval_info = EvalutionType::orderBy('et_sort')
            ->where('et_kind', $type)
            ->withCount(['evalution as learner_evalution_count' => function (Builder $query) use ($lesson_id, $type){
                $query->where('eval_kind', $type)
                    ->where('eval_lesson_id', $lesson_id)
                    ->where('eval_val', self::YES_EVAL);
            }])
            ->get();
            /*->withCount(['evalution as learner_evalution_count' => function (Builder $query) use ($lesson_id, $type){
                $query->where('eval_kind', $type)
                    ->where('eval_lesson_id', $lesson_id)
                    ->where('eval_val', self::YES_EVAL)
                    ->groupBy('eval_schedule_id');
            }])
            ->get();;*/
        if(count($eval_info) == 0){
            return null;
        }
        foreach ($eval_info as $key => $value){
            if($eval_total_count > 0){
                $result[$key]['percent'] = round(100 * $value['learner_evalution_count'] / $eval_total_count);
            }else{
                $result[$key]['percent'] = 0;
            }
            $result[$key]['type_name'] = $value['et_question'];
        }
        return $result;
    }

    public static function isExistEvalution($user_id, $lrs_id)
    {
        $ret = Evalution::where('eval_user_id', $user_id)
            ->where('eval_schedule_id', $lrs_id)
            ->first();
        return is_object($ret) ? $ret : false;
    }

    public static function getEvaluationValueByType($user_id, $lrs_id, $eval_type)
    {
        $ret = Evalution::where('eval_user_id', $user_id)
            ->where('eval_schedule_id', $lrs_id)
            ->where('eval_type_id', $eval_type)
            ->first();
        return is_object($ret) ? $ret->eval_val : "no_exist";
    }
}
