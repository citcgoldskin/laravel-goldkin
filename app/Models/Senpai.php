<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Senpai extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $primaryKey = 'senpai_id';
    protected $fillable =
        [
        'senpai_user_id',
        'senpai_mail',
        'senpai_area_id',
        'senpai_county',
        'senpai_village',
        'senpai_mansyonn'
        ];

    public function user()
    {
        return $this->belongsTo(User::class, 'senpai_user_id', 'id');
    }

    public function area(){
        return $this->hasOne(Area::class, 'area_id', 'senpai_area_id');
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class, 'fav_favourite_id', 'senpai_id');
    }

    public function lesson(){
        return $this->hasMany(Lesson::class, 'lesson_senpai_id', 'senpai_id');
    }
}
