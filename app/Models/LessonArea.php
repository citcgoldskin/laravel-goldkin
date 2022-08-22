<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LessonArea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $primaryKey = 'la_id';
    protected $fillable = [
        'la_lesson_id',
        'la_deep1_id',
        'la_deep2_id',
        'la_deep3_id',
        'position'
    ];
    public function lesson(){
        return $this->belongsTo(Lesson::class, 'la_lesson_id', 'lesson_id');
    }
}
