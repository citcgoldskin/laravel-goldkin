<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Area extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'area_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function senpai(){
        return $this->hasMany(User::class, 'user_area_id', 'area_id')->where('user_is_senpai', '1'); //todo test
    }

    public function lesson_area1(){
        return $this->hasMany(LessonArea::class, 'la_deep1_id', 'area_id');
    }
    public function lesson_area2(){
        return $this->hasMany(LessonArea::class, 'la_deep2_id', 'area_id');
    }
    public function lesson_area3(){
        return $this->hasMany(LessonArea::class, 'la_deep3_id', 'area_id');
    }

}
