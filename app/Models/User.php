<?php

namespace App\Models;

use App\Models\Area;
use App\Models\PersonConfirm;
use App\Notifications\User\ResetPasswordNotification;
use App\Service\AreaService;
use App\Service\CommonService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'user_no',
        'email_verified_at',
        'email_verify_token',
        'password',
        'user_is_senpai',
        'user_firstname',
        'user_lastname',
        'user_sei',
        'user_mei',
        'user_birthday',
        'user_sex',
        'user_mail',
        'user_area_id',
        'user_county',
        'user_village',
        'user_mansyonn',
        'user_phone',
        'user_intro',
        'bank',
        'bank_branch',
        'bank_account_type',
        'bank_account_no',
        'bank_account_name',
        'user_code',
        'current_location',
        'caution_cnt',
        'punishment_cnt',
        'user_avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'age_years'
    ];

    public function senpai()
    {
        return $this->hasOne(Senpai::class, 'senpai_user_id', 'id');
    }

    //kh
    public function area()
    {
        return $this->hasOne(Area::class, 'area_id', 'user_area_id');
    }

    public function favourite() {
        return $this->hasMany(Favourite::class, 'fav_favourite_id', 'id');
    }

    public function lesson() {
        return $this->hasMany(Lesson::class, 'lesson_senpai_id', 'id');
    }

    public function userConfirm()
    {
        return $this->hasOne(PersonConfirm::class, 'pc_user_id', 'id')->orderByDesc('updated_at');
    }

    public function ob_bank()
    {
        return $this->belongsTo(Bank::class, 'bank', 'id');
    }

    public function ob_bank_branch()
    {
        return $this->belongsTo(Bank::class, 'bank_branch', 'id');
    }

    public function getAgeAttribute()
    {
        return CommonService::getAge($this->user_birthday);
    }

    public function sendPasswordResetNotification($token)
    {
        try {
            $this->notify(new ResetPasswordNotification($token));
        } catch (\Exception $exception) {
            Log::error("EMail Sending Failed");
        }
    }

    public function getAvatarPathAttribute()
    {
        return CommonService::getUserAvatarUrl($this->user_avatar);
    }

    public function getUserAreaNameAttribute()
    {
        return AreaService::getOneAreaFullName($this->user_area_id);
    }

    public function getUserNameAttribute()
    {
        return $this->user_firstname.'　'.$this->user_lastname;
    }

    public function getUserNameKanaAttribute()
    {
        return $this->user_sei.'　'.$this->user_mei;
    }

    public function getAgeYearsAttribute()
    {
        $timestamp = strtotime($this->user_birthday);
        $year = date('Y', time()) - date('Y', $timestamp);
        $age = $year > 0 ? $year : 0;
        $age = (int)($age / 10);
        if ($age == 0) {
            $age = 10;
        } else if($age > 7) {
            $age = 70;
        } else {
            $age = $age * 10;
        }
        return $age;
    }

    public function getLastPunishmentAtAttribute()
    {
        $obj_last = Punishment::where('user_id', $this->id)
            ->orderByDesc('decided_at')
            ->first();
        if (is_object($obj_last)) {
            return $obj_last->decided_at;
        }
        return "";
    }

    public function getIsPersonConfirmAttribute()
    {
        $ret =  PersonConfirm::where('pc_user_id', $this->id)
            ->whereIn('pc_user_id', function($query) {
            $query->select('pc_user_id')
                ->from('person_confirms')
                ->where('pc_state', config('const.pers_conf.confirmed'));
        });
        if (is_object($ret)) {
            return true;
        }
        return false;
    }

}
