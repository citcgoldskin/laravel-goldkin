<?php

namespace App\Models;
use App\Models\Coupon;

class CouponUsage extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'cpu_id';

    protected $fillable = [
        'cpu_cup_id',
        'cpu_user_id',
        'cpu_lrs_id',
        'cpu_date_get',
        'cpu_date_to',
        'cpu_order',
        'cpu_state',
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class,  'cpu_cup_id','cup_id');
    }
}
