<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Favourite extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'fav_id';

    protected $fillable = [
        'fav_id',
        'fav_user_id',
        'fav_type',
        'fav_favourite_id'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'fav_user_id', 'id');
    }

    public function f_user()
    {
        return $this->belongsTo(User::class, 'fav_favourite_id', 'id');
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'fav_favourite_id', 'lesson_id');
    }

    public function followSenpai()
    {
        return $this->belongsTo(User::class, 'fav_favourite_id', 'id');
    }
}
