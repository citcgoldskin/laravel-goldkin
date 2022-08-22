<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonAccessHistory extends Model
{
    use SoftDeletes;

    protected $table = 'lesson_access_histories';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'token'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }


}
