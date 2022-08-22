<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class LessonSchedule extends BaseModel
{
    //use SoftDeletes;
    public $primaryKey = 'ls_id';
    protected $fillable = [
        'ls_id',
        'ls_senpai_id',
        'ls_date',
        'ls_start_time',
        'ls_end_time'
    ];
}
