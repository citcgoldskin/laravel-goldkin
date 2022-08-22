<?php

namespace App\Service;

use App\Service\BaseCateService;
use App\Models\CardCredit;
use App\Models\CardCompany;
use App\Models\Account;
use App\Models\Bank;
use Auth;
use DB;

class BankService extends BaseCateService
{
    //CardCompany
    public static function getCompanyList() {
        return CardCompany::orderBy('company_id')
            ->get();
    }

    //CardCredit
    public static function doCreateCardCredit($data) {
        if($obj_credit = CardCredit::create($data))
        {
            return $obj_credit;
        } else {
            return null;
        }
    }

    public static function getCardCredit($user_id) {
        return CardCredit::orderBy('cc_id')
            ->where('cc_user_id', $user_id)
            ->with('company')
            ->first();
    }

    //account
    public static function getAccount($user_id) {
        return Account::where('act_user_id', $user_id)
            ->with('bank')
            ->first();
    }

    public static function doCreateAccount($data) {
        if($obj_account = Account::create($data))
        {
            return $obj_account;
        } else {
            return null;
        }
    }

    public static function doUpdateAccount($data=array(), $act_id)
    {
        return Account::where('act_id', $act_id)
            ->update($data);
    }

    //bank
    public static function getBankList($bnk_fav) {
        return Bank::orderBy('bnk_name')
            ->where('bnk_fav', $bnk_fav)
            ->get();
    }

    public static function getBank($bnk_id) {
        return Bank::where('bnk_id', $bnk_id)
            ->first();
    }

    public static function getBankId($bnk_name) {
        $bank =  Bank::where('bnk_name', $bnk_name)
            ->first();
        if(empty($bank)){
            return 0;
        }else{
            return $bank['bnk_id'];
        }
    }

    public static function getBankByKeyword($search_key) {
        $bank_master = Bank::select('bank_code', 'branch_code', 'id', 'name', 'name_kana', 'type')
            ->where('branch_code', '000');
        if ($search_key) {
            $key_arr = explode(',', $search_key);
            $bank_master->where(function ($query) use ($key_arr){
                foreach ($key_arr as $value) {
                    $query->orWhere('name_kana', 'like', $value . '%');
                }
            });
        }
        return $bank_master->orderBy('name')->get();
    }

    public static function getBankName($bank_id) {
        $obj_bank = Bank::find($bank_id);
        if (is_object($obj_bank)) {
            return $obj_bank->name;
        }
        return "";
    }

    public static function getBranchName($bank_id) {
        $obj_bank = Bank::find($bank_id);
        if (is_object($obj_bank)) {
            return $obj_bank->name;
        }
        return "";
    }

}
