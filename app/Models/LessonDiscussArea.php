<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LessonDiscussArea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $primaryKey = 'id';
    protected $fillable = [
        'lda_lesson_id',
        'lda_deep1_id',
        'lda_deep2_id',
        'lda_deep3_id',
        'position'
    ];

    public function lesson(){
        return $this->belongsTo(Lesson::class, 'lda_lesson_id', 'lesson_id');
    }
}
