<?php

namespace App\Models;

class MsgClass extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'mc_id';

    protected $fillable = [
        'mc_name',
        'mc_sort'
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
    public function msg_setting(){
        return $this->hasOne(MsgSetting::class, 'ms_mc_id', 'mc_id');
    }
}
