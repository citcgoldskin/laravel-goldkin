<?php

namespace App\Models;
use App\Models\User;
use App\Models\Recruit;
use App\Service\CommonService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferApplication extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'ta_user_id',
        'ta_bank_id',
        'ta_bank_account_type',
        'ta_bank_branch',
        'ta_bank_account_no',
        'ta_bank_account_name',
        'ta_send_total_price',
        'ta_fee',
        'ta_profit_price',
        'ta_transfer_status',
        'ta_schedule_date',
        'ta_application_date'
    ];

}
