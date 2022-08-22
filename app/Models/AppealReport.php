<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AppealReport extends BaseModel
{

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'appeal_id',
        'type'
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

    public function obj_appeal(){
        return $this->belongsTo(Appeal::class, 'appeal_id', 'id');
    }

    public function appeal_class() {
        return $this->belongsTo(AppealClass::class, 'type', 'id');
    }
}
