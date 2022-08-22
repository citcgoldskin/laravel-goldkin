<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonRequestSchedule extends Model
{
    use SoftDeletes;
    public $primaryKey = 'lrs_id';
    protected $fillable =
        [
        'lrs_id',
        'lrs_lr_id',
        'lrs_date',
        'lrs_start_time',
        'lrs_end_time',
        'lrs_amount',
        'lrs_fee_type',
        'lrs_fee',
        'lrs_service_fee',
        'lrs_state',
        'lrs_cancel_date',
        'lrs_complete_date',
        'lrs_reserve_date',
        'lrs_request_date',
        'lrs_cancel_reason',
        'lrs_cancel_note',
        'lrs_cancel_fee',
        'lrs_no_confirm',
        'lrs_old_schedule',
        'lrs_senpai_id',
        'lrs_senpai_is_share_position',
        'lrs_kouhai_id',
        'lrs_kouhai_is_share_position',
        ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
    protected $hidden = [
//        'lrs_cancel_reason',
        'created_at',
        'updated_at',
    ];

    public function lesson_request(){
        return $this->belongsTo(LessonRequest::class, 'lrs_lr_id', 'lr_id');
    }

    public function senpai(){
        return $this->belongsTo(User::class, 'lrs_senpai_id', 'id');
    }

    public function kouhai(){
        return $this->belongsTo(User::class, 'lrs_kouhai_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        $ret = "";
        switch ($this->lrs_state) {
            case config('const.schedule_state.complete'):
                $ret = "終了レッスン";
                break;
            case config('const.schedule_state.request'):
            case config('const.schedule_state.confirm'):
                $ret = "予約中";
                break;
            case config('const.schedule_state.cancel_senpai'):
            case config('const.schedule_state.cancel_kouhai'):
                $ret = "予約キャンセル済";
                break;
            case config('const.schedule_state.reserve'):
                if ($this->lesson_request->lr_type == config('const.request_type.reserve')) {
                    $ret = "予約リクエスト済";
                }
                if ($this->lesson_request->lr_type == config('const.request_type.attend')) {
                    $ret = "出動リクエスト済";
                }
                break;
            case config('const.schedule_state.cancel_system'):
                if ($this->lesson_request->lr_type == config('const.request_type.reserve')) {
                    $ret = "予約リクエスト取消し済";
                }
                if ($this->lesson_request->lr_type == config('const.request_type.attend')) {
                    $ret = "出動リクエスト取消し済";
                }
                break;
            default:
                break;
        }
        return $ret;
    }

    public function getIsShareLocation($user_id) {
        $ret = 0;
        $compare_date = Carbon::now()->format('Y-m-d');
        $compare_datetime = Carbon::now()->addMinutes(-30)->format('H:i:s');
        if ($compare_date == $this->lrs_date && $this->lrs_start_time >= $compare_datetime && $this->lrs_state == config('const.schedule_state.reserve')) {
            if ($this->lrs_senpai_id == $user_id) { // if senpai
                if ($this->lrs_senpai_is_share_position == 1) {
                    $ret = 1;
                }
            } else { // if koupai
                if ($this->lrs_kouhai_is_share_position == 1) {
                    $ret = 1;
                }
            }
        }
        return $ret;
    }

    public function getCanShareLocationAttribute() {
        $ret = 0;
        $compare_date = Carbon::now()->format('Y-m-d');
        $compare_datetime = Carbon::now()->addMinutes(-30)->format('H:i:s');

        if ($compare_date == $this->lrs_date && $this->lrs_start_time >= $compare_datetime && $this->lrs_state == config('const.schedule_state.reserve')) {
            $ret = 1;
        }
        return $ret;
    }
}
