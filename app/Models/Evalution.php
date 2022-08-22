<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Evalution extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $primaryKey = 'eval_id';
    protected $fillable = [
        'eval_id',
        'eval_user_id',
        'target_user_id',
        'eval_kind',
        'eval_lesson_id',
        'eval_lesson_request_id',
        'eval_schedule_id',
        'eval_type_id',
        'eval_val'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class,  'lesson_id', 'eval_lesson_id');
    }

    public function evalution_type()
    {
        return $this->belongsTo(EvalutionType::class,  'et_id', 'eval_type_id');
    }
}
