<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CardCredit extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'cc_user_id',
        'cc_square_card_id',
        'cc_is_default',
        'cc_data',
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

    public function company()
    {
        return $this->belongsTo(CardCompany::class,  'cc_company_id', 'company_id');
    }

    public function getCCDataAttribute($value)
    {
        return json_decode($value, true);
    }
}
