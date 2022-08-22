<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class RecruitArea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $primaryKey = 'id';
    protected $fillable = [
        'ra_recruit_id',
        'ra_deep1_id',
        'ra_deep2_id',
        'ra_deep3_id',
        'position'
    ];
    public function recruit(){
        return $this->belongsTo(Recruit::class, 'ra_recruit_id', 'rc_id');
    }
}
