<?php

namespace App\Models;
use App\Models\User;
use App\Models\Recruit;
use App\Service\CommonService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;


class Proposal extends BaseModel
{
    use SoftDeletes;
//    public $table = 'sp_recruit';
    public $primaryKey = 'pro_id';
    protected $fillable = ['pro_user_id',
        'pro_rc_id',
        'pro_money',
        'pro_fee_type',
        'pro_fee',
        'pro_service_fee',
        'pro_traffic_fee',
        'pro_start_time',
        'pro_end_time',
        'pro_lesson_period_start',
        'pro_lesson_period_end',
        'pro_msg',
        'pro_buy_datetime',
        'pro_request_time',
        'pro_proposing_time',
        'pro_complete_time',
        'pro_state'];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];

    public function proposalUser()
    {
        return $this->hasOne(User::class, 'id', 'pro_user_id');
    }

    public function recruit()
    {
        return $this->hasOne(Recruit::class, 'rc_id', 'pro_rc_id');
    }

    public function getLessonStartDateAttribute() {
        return Carbon::parse($this->recruit->rc_date)->format('n月j日').CommonService::getStartAndEndTime($this->pro_start_time, $this->pro_end_time);
    }

    public function getLessonPeriodAttribute() {
        return CommonService::getTimeUnit($this->pro_lesson_period_start);
    }

}
