<?php
namespace App\Models;

use App\Service\AppealService;

class Appeal extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'user_id',
        'appeal_user_id',
        'status',
        'note',
        'reported_at'
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

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function appeal_user(){
        return $this->belongsTo(User::class, 'appeal_user_id', 'id');
    }

    public function report(){
        return $this->hasMany(AppealReport::class, 'appeal_id', 'id');
    }

    public function getLastUpdatedAtAttribute() {
        $obj_last = Appeal::where('appeal_user_id', $this->appeal_user_id)->orderByDesc('updated_at')->first();
        return is_object($obj_last) ? $obj_last->updated_at : '';
    }

    public function getAllReasonAttribute() {
        $result = "";
        $appealClasses = AppealService::getAppealClasses();
        $all_reports = $this->report;
        foreach ($all_reports as $key=>$report) {
            $result .= AppealService::getAppealClassNameByType($report->type);
            if ($key < count($all_reports) - 1) {
                $result .= "・";
            }
        }
        return $result;
    }
}
