<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'act_id';
    protected $fillable = [
        'act_user_id',
        'act_bank_id',
        'act_type',
        'act_suboffice_code',
        'act_number',
        'act_name'
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
    public function bank()
    {
        return $this->belongsTo(Bank::class,  'act_bank_id', 'bnk_id');
    }
}
