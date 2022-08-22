<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Lesson extends Model
{
    use SoftDeletes;
    public $primaryKey = 'lesson_id';
    protected $fillable = [
        'lesson_id',
        'lesson_senpai_id',
        'lesson_type',
        'lesson_class_id',
        'lesson_title',
        'lesson_image',
        'lesson_wish_sex',
        'lesson_wish_minage',
        'lesson_wish_maxage',
        'lesson_min_hours',
        'lesson_30min_fees',
        'lesson_person_num',
        'lesson_able_with_man',
        'lesson_accept_without_map',
        'lesson_latitude',
        'lesson_longitude',
        'lesson_map_address',
        'lesson_pos_detail',
        'lesson_able_discuss_pos',
        'lesson_discuss_pos_detail',
        'lesson_service_details',
        'lesson_other_details',
        'lesson_buy_and_attentions',
        'lesson_accept_attend_request',
        'lesson_cond_1',
        'lesson_cond_2',
        'lesson_cond_3',
        'lesson_cond_4',
        'lesson_cond_5',
        'lesson_cond_6',
        'lesson_cond_7',
        'lesson_cond_8',
        'lesson_cond_9',
        'lesson_cond_10',
        'lesson_cond_11',
        'lesson_cond_12',
        'lesson_state',
        'lesson_stop',
        'lesson_stopped_at',
        'lesson_stop_cancel_reverse_at',
        'lesson_coupon'
    ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = [
        'lesson_area_names',
        'lesson_discuss_area_names'
    ];

    public function senpai(){
        return $this->belongsTo(User::class, 'lesson_senpai_id', 'id');
    }

    public function favourite(){
        return $this->hasMany(Favourite::class, 'fav_favourite_id', 'lesson_id');
    }

    public function evalution(){
        return $this->hasMany(Evalution::class, 'eval_lesson_id', 'lesson_id');
    }

    public function lesson_schedule(){
        return $this->hasMany(LessonSchedule::class, 'ls_senpai_id', 'lesson_senpai_id');
    }

    public function lesson_class()
    {
        return $this->belongsTo(LessonClass::class,  'lesson_class_id', 'class_id');
    }

    public function lesson_area(){
        return $this->hasMany(LessonArea::class, 'la_lesson_id', 'lesson_id');
    }

    public function lesson_discuss_area(){
        return $this->hasMany(LessonDiscussArea::class, 'lda_lesson_id', 'lesson_id');
    }

    public function lesson_load() {
        return $this->hasOne( LessonLoad::class, 'lesson_id', 'lesson_id');
    }

    public function request_schedule(){
        return $this->hasManyThrough(LessonRequestSchedule::class, LessonRequest::class,
            'lr_lesson_id', 'lrs_lr_id',
            'lesson_id', 'lr_id');
    }

    public function getLessonAreaNamesAttribute() {
        $result = [];
        if (count($this->lesson_area) > 0) {
            foreach ($this->lesson_area as $area) {
                if ($area->position) {
                    $position = json_decode($area->position);
                    $result[] = $position->prefecture.$position->county.$position->locality.$position->sublocality;
                }
            }
        }
        return $result;
    }

    public function getLessonDiscussAreaNamesAttribute() {
        $result = [];
        if (count($this->lesson_discuss_area) > 0) {
            foreach ($this->lesson_discuss_area as $area) {
                if ($area->position) {
                    $position = json_decode($area->position);
                    $result[] = $position->area_name;
                }
            }
        }
        return $result;
    }

    public function getLastChangeHistoryAttribute() {
        return LessonChangeHistory::where('lesson_id', $this->lesson_id)->orderByDesc('updated_at')->first();
    }

    public function getChangeHistoryExistsAttribute() {
        return LessonChangeHistory::where('lesson_id', $this->lesson_id)->exists();
    }
}
