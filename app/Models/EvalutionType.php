<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class EvalutionType extends BaseModel
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'et_id',
        'et_kind',
        'et_question'
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

    public function evalution()
    {
        return $this->hasMany(Evalution::class,  'eval_type_id', 'et_id');
    }
}
