<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class RecruitVisit extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'user_id',
        'recruit_id'
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

    public function obj_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function obj_recruit()
    {
        return $this->belongsTo(Recruit::class, 'recruit_id', 'rc_id');
    }
}
