<?php

namespace App\Models;
use App\Models\User;


class Coupon extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'cup_id';

    protected $fillable = [
        'cup_from_user_id',
        'cup_code',
        'cup_name',
        'cup_apply_condition',
        'cup_cnt_origin',
        'cup_reduce_money',
        'cup_sell_money',
        'cup_period',
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

    public function user()
    {
        return $this->hasMany(User::class,  'id','cup_from_user_id');
    }

}
