<?php

namespace App\Service;

use App\Models\TransferApplication;
use App\Service\BaseCateService;
use App\Models\CardCredit;
use App\Models\CardCompany;
use App\Models\Account;
use App\Models\Bank;
use Auth;
use Carbon\Carbon;
use DB;

class TransferApplicationService extends BaseCateService
{
    public static function doApplication($params, $obj_user) {
        $transfer_application = new TransferApplication($params);

        if(!self::validateBankInfo($obj_user)) return false;

        $transfer_application->ta_bank_id = $obj_user->bank;
        $transfer_application->ta_bank_account_type = $obj_user->bank_account_type;
        $transfer_application->ta_bank_branch = $obj_user->bank_branch;
        $transfer_application->ta_bank_account_no = $obj_user->bank_account_no;
        $transfer_application->ta_bank_account_name = $obj_user->bank_account_name;
        $transfer_application->ta_transfer_status = config('const.transfer_status.apply');

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $transfer_application->ta_application_date = $now;

        $application_send_date = Carbon::now()->addMonths(1)->format('Y-m-'.str_pad(config('const.transfer_config.application_send_date'), 2, '0', STR_PAD_LEFT));
        $application_limit_date = Carbon::now()->format('Y-m-'.str_pad(config('const.transfer_config.application_end_date'), 2, '0', STR_PAD_LEFT).' 23:59');
        if ($now > $application_limit_date) {
            $application_send_date = Carbon::parse($application_send_date)->addMonths(1)->format('Y-m-d');
        }
        $transfer_application->ta_schedule_date = $application_send_date;

        return $transfer_application->save();
    }

    public static function validateBankInfo($obj_user) {
        if (!$obj_user->bank || !$obj_user->bank_account_type || !$obj_user->bank_branch || !$obj_user->bank_account_no || !$obj_user->bank_account_name) {
            return false;
        }
        return true;
    }

    // 振込合計
    public static function getAllTransferPrice($user_id) {
        $all_transfer_price = TransferApplication::select(DB::raw("SUM(ta_send_total_price) as all_amount"))
            ->where('ta_user_id', $user_id)
            ->get();

        if (is_object($all_transfer_price) && $all_transfer_price[0]) {
            return $all_transfer_price[0]->all_amount;
        }

        return 0;
    }

    public static function getTransferPriceAfterDate($user_id, $compare_date) {
        $all_transfer_price = TransferApplication::select(DB::raw("SUM(ta_send_total_price) as all_amount"))
            ->where('ta_user_id', $user_id)
            ->whereDate('ta_schedule_date', '>', $compare_date)
            ->get();

        if (is_object($all_transfer_price) && $all_transfer_price[0] && $all_transfer_price[0]->all_amount) {
            return $all_transfer_price[0]->all_amount;
        }

        return 0;
    }

    public static function getTransferAmountByPeriod($user_id, $from_date, $to_date) {
        $transfer_price = TransferApplication::select(DB::raw("SUM(ta_send_total_price) as all_amount"))
            ->where('ta_user_id', $user_id)
            ->where('ta_transfer_status', config('const.transfer_status.sent'))
            ->whereDate('ta_schedule_date', '>=', $from_date)
            ->whereDate('ta_schedule_date', '<=', $to_date)
            ->get();

        if (is_object($transfer_price) && $transfer_price[0] && $transfer_price[0]->all_amount) {
            return $transfer_price[0]->all_amount;
        }
        return 0;
    }
}
