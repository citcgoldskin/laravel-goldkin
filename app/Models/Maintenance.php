<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'maintenance';

    protected $fillable = [
        'start_time',
        'end_time',
        'services',
        'notice_time'
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

    public function getArrServicesAttribute() {
        return explode(',', $this->services);
    }

}
