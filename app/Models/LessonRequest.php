<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonRequest extends Model
{
    use SoftDeletes;

    public $primaryKey = 'lr_id';
    protected $fillable =
        ['lr_lesson_id',
            'lr_user_id',
            'lr_type',
            'lr_hope_type',
            'lr_hope_mintime',
            'lr_hope_maxtime',
            'lr_target_reserve',
            'lr_pos_discuss',
            'lr_until_confirm',
            'lr_state',
            'lr_request_date',
            'lr_response_date',
            'lr_reserve_date',
            'lr_complete_date',
            'lr_cancel_date',
            'lr_man_num',
            'lr_woman_num',
            'lr_area_id',
            'lr_address',
            'lr_address_detail'
        ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['discuss_lesson_area'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lr_lesson_id', 'lesson_id');
    }

    public function lesson_discuss_area()
    {
        return $this->belongsTo(LessonDiscussArea::class, 'lr_area_id', 'id');
    }

    public function lesson_request_schedule()
    {
        return $this->hasMany(LessonRequestSchedule::class, 'lrs_lr_id', 'lr_id');
    }

    public function schedule_request()
    {
        return $this->hasMany(LessonRequestSchedule::class, 'lrs_lr_id', 'lr_id')
            ->where('lrs_state', config('const.schedule_state.request'));
    }

    public function schedule_confirm()
    {
        return $this->hasMany(LessonRequestSchedule::class, 'lrs_lr_id', 'lr_id')
            ->where('lrs_state', config('const.schedule_state.confirm'));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lr_user_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        $ret = "";
        switch ($this->lr_state) {
            case config('const.req_state.request'):
                $ret = "予約リクエスト済";
                if ($this->lr_type == config('const.request_type.attend')) {
                    $ret = "出動リクエスト済";
                }
                break;
            case config('const.req_state.response'):
                $ret = "予約リクエスト回答済";
                if ($this->lr_type == config('const.request_type.attend')) {
                    $ret = "出動リクエスト回答済";
                }
                break;
            case config('const.req_state.reserve'):
                $ret = "予約中";
                break;
            case config('const.req_state.complete'):
                $ret = "終了レッスン";
                break;
            case config('const.req_state.cancel'):
                $ret = "キャンセル済";
                break;
            default:
                break;
        }
        return $ret;
    }

    public function getDiscussLessonAreaAttribute()
    {
        $obj_discuss_area = $this->lesson_discuss_area;
        if (is_object($obj_discuss_area)) {
            $position = json_decode($obj_discuss_area->position);
            return $position->area_name;
        }
        return "";
    }

}
