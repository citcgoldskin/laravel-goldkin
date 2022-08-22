<?php

namespace App\Models;

use App\Models\LessonClass;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Favourite;
use App\Service\CommonService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recruit extends BaseModel
{
    use SoftDeletes;
//    public $table = 'sp_recruit';
    public $primaryKey = 'rc_id';
    protected $fillable = ['rc_user_id',
        'rc_class_id',
        'rc_title',
        'rc_date',
        'rc_start_time',
        'rc_end_time',
        'rc_lesson_period_from',
        'rc_lesson_period_to',
        'rc_man_num',
        'rc_woman_num',
        'rc_wish_minmoney',
        'rc_wish_maxmoney',
        'rc_place',
        'rc_longitude',
        'rc_latitude',
        'rc_place_detail',
        'rc_detail',
        'rc_req_sex',
        'rc_req_age_from',
        'rc_req_age_to',
        'rc_period',
        'rc_state',
        'rc_stop',
        'rc_stopped_at',
        'rc_stop_cancel_reverse_at',
        'rc_break_at'
    ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];

    public function cruitLesson()
    {
        return $this->hasOne(LessonClass::class, 'class_id', 'rc_class_id');
    }

    public function cruitUser()
    {
        return $this->hasOne(User::class, 'id', 'rc_user_id');
    }

    public function recruitUser()
    {
        return $this->hasMany(User::class, 'id', 'rc_user_id');
    }

    public function recruit_proposal()
    {
        return $this->hasMany(Proposal::class, 'pro_rc_id', 'rc_id');
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class, 'fav_favourite_id', 'rc_id');
    }

    public function recruit_area(){
        return $this->hasMany(RecruitArea::class, 'ra_recruit_id', 'rc_id');
    }

    public function getProposedSenpaiAttribute() {
        $ret = null;
        foreach ($this->recruit_proposal as $senpai_propose) {
            if ($senpai_propose->pro_state == config('const.prop_state.complete')) {
                $ret = $senpai_propose;
            }
        }
        return $ret;
    }

    public function getRecruitAreaNamesAttribute() {
        $result = [];
        if (count($this->recruit_area) > 0) {
            foreach ($this->recruit_area as $area) {
                if ($area->position) {
                    $position = json_decode($area->position);
                    $result[] = $position->prefecture.$position->county.$position->locality.$position->sublocality;
                }
            }
        }
        return $result;
    }

    public function getLessonStartDateAttribute() {
        return Carbon::parse($this->rc_date)->format('n月j日').CommonService::getStartAndEndTime($this->rc_start_time, $this->rc_end_time);
    }

    public function getStatusNameAttribute()
    {
        $ret = "";
        switch ($this->rc_state) {
            case config('const.recruit_state.complete'):
                $ret = "終了レッスン";
                break;
            case config('const.recruit_state.request'):
            case config('const.recruit_state.recruiting'):
                $ret = "予約中";
                if (isset($this->proposed_senpai) && $this->proposed_senpai) {
                    $ret = "提案有";
                }
                break;
            case config('const.recruit_state.cancel'):
                $ret = "予約キャンセル済";
                break;
            case config('const.recruit_state.past'):
                if (isset($this->proposed_senpai) && $this->proposed_senpai) {
                    // $ret = "提案有";
                } else {
                    $ret = "予約不成立";
                }
                break;
            /*case config('const.recruit_state.reserve_not_success'):
                $ret = "提案有";
                break;*/
            /*case config('const.recruit_state.reserve_not_success'):
                $ret = "予約不成立";
                break;*/
            default:
                break;
        }
        // 1:公開停止, 2: レッスンを中断
        if ($this->rc_stop == config('const.lesson_stop_code.stop_lesson')) {
            $ret = "公開停止";
        }
        if($this->rc_stop == config('const.lesson_stop_code.break_lesson')) {
            $ret = "レッスン中断";
        }
        return $ret;
    }
}
