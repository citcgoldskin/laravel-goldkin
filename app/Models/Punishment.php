<?php

namespace App\Models;
use App\Service\AppealService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;


class Punishment extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'stop_period',
        'basis',
        'reason',
        'alert_title',
        'alert_text',
        'decided_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getStopPeriodDateAttribute() {
        // 7日間（2022.7.12/22:00～2022.7.19/23:59）
        return $this->stop_period."日間（".Carbon::parse($this->decided_at)->format('Y.n.j/H:i')."～".Carbon::parse($this->decided_at)->addDays($this->stop_period)->format('Y.n.j/H:i')."）";
    }

    public function getBeforePunishmentAtAttribute() {
        $obj_last = Punishment::where('user_id', $this->user_id)
            ->where('decided_at', '<', $this->decided_at)
            ->orderByDesc('decided_at')
            ->first();
        if (is_object($obj_last)) {
            return $obj_last->decided_at;
        }
        return "";
    }
}
