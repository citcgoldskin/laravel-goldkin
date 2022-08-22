<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonConfirm extends BaseModel
{
    use SoftDeletes;
//    public $table = 'sp_recruit';
    public $primaryKey = 'pc_id';
    protected $fillable = ['pc_user_id',
        'pc_confirm_doc',
        'pc_state',
        'pc_confirm_type',
        'pc_reject_reason'];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class, 'pc_user_id', 'id');
    }

    public function getConfirmCardImageAttribute() {
        $result = "";
        switch ($this->pc_confirm_type) {
            case config('const.person_confirm_type_code.driver_license'):
                $result = asset('assets/user/img/coupon/img_license.png');
                break;
            case config('const.person_confirm_type_code.insurance_card'):
                $result = asset('assets/user/img/coupon/img_health_card.png');
                break;
            case config('const.person_confirm_type_code.number_card'):
                $result = asset('assets/user/img/coupon/img_mynumber.png');
                break;
            case config('const.person_confirm_type_code.student_card'):
                $result = asset('assets/user/img/coupon/img_student.png');
                break;
            case config('const.person_confirm_type_code.passport'):
                $result = asset('assets/user/img/coupon/img_passport.png');
                break;
            case config('const.person_confirm_type_code.resident_card'):
                $result = asset('assets/user/img/coupon/img_resident_card.png');
                break;
        }
        return $result;
    }
}
