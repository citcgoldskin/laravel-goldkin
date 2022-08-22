<?php

namespace App\Service;
use App\Service\CommonService;
use App\Service\SettingService;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;
use Auth;
use DB;
use function PHPUnit\Framework\isNull;

class CouponService
{
    //senpai functions
    public static function doCreateCoupon($data){
        $data_query = array();
        $data_query['cup_from_user_id'] = $data['user_id'];
        $data_query['cup_name'] = $data['coupon_name'];
        $data_query['cup_apply_condition'] = $data['coupon_condition'];
        $data_query['cup_cnt_origin'] = $data['coupon_number'];
        $data_query['cup_cnt_now'] = $data['coupon_number'];
        $data_query['cup_reduce_money'] = $data['coupon_discount'];
        $data_query['cup_sell_money'] = $data['coupon_buy'];
        $data_query['cup_period'] = $data['coupon_period'];
        $data_query['cup_code'] = CommonService::createCouponCode();

        if($obj_recruit = Coupon::create($data_query))
        {
            return $obj_recruit;
        } else {
            return null;
        }
    }

    public static function getCouponList($user_id)
    {
        return Coupon::where('cup_from_user_id', $user_id)
            ->where('cup_visible', 0)
            ->orderByDesc('created_at')
            ;
    }

    //Kouhai functions
    public static function getCouponBox($user_id, $state, $order = 0, $code = '')
    {
         $coupons = CouponUsage::whereHas('coupon', function($query) use ($code, $order){
            $result = $query->with('user')->where('cup_code', 'like', '%' . $code . '%');
            if($order == config('const.coupon_sort.money_large'))
            {
                $result->orderByDesc('cup_reduce_money');
            } else if($order == config('const.coupon_sort.money_small'))
            {
                $result->orderBy('cup_reduce_money', 'asc');
            } else if($order == config('const.coupon_sort.condition_large'))
            {
                $result->orderBy('cup_apply_condition', 'desc');
            } else if($order == config('const.coupon_sort.condition_small'))
            {
                $result->orderBy('cup_apply_condition', 'asc');
            }
        })
            ->where('cpu_user_id', $user_id)
            ->where('cpu_state', $state);


            if($order == config('const.coupon_sort.period_short'))
            {
                $coupons = $coupons->orderBy('cpu_date_to', 'asc');
            } else if ($order == config('const.coupon_sort.period_long'))
            {
                $coupons = $coupons->orderBy('cpu_date_to', 'desc');
            }

            return $coupons
                ->get()
            ->groupBy('cpu_cup_id')
            ;
    }

    public static function getSysCoupon($user_id)
    {
        $data_query = array();
        $data_query['cup_from_user_id'] = 0;
        $data_query['cup_name'] = "system coupon";
        $data_query['cup_apply_condition'] = 0;
        $data_query['cup_cnt_origin'] = 1;
        $data_query['cup_reduce_money'] = SettingService::getSetting('coupon_value_system', 'int');
        $data_query['cup_sell_money'] = 0;
        $data_query['cup_period'] = 1;
        $data_query['cup_code'] = CommonService::createCouponCode();

        $coupon = Coupon::create($data_query);

        $data_query = array();
        $data_query['cpu_cup_id'] = $coupon->cup_id;
        $data_query['cpu_user_id'] = $user_id;
        $data_query['cpu_lrs_id'] = 0;
        $data_query['cpu_state'] = config('const.coupon_state.valid');
        $valid_date = SettingService::getSetting('coupon_sys_valid_date', 'int');
        $data_query['cpu_date_to'] = date('Y-m-d', strtotime("+$valid_date days"));

        CouponUsage::create($data_query);
    }

    public static function chooseCoupon($senpai_id, $kouhai_id, $money)
    {
        $coupon_usage = CouponUsage::whereHas('coupon', function($query) use($senpai_id){
            $query->where('cup_from_user_id', $senpai_id)
                ->orWhere('cup_from_user_id', 0);
        })
        ->where('cpu_user_id', $kouhai_id)
        ->where('updated_at', ">=", date('Y-m-d', time()))
        ->where('cpu_state', config('const.coupon_state.used'))
        ->count();

        if($coupon_usage > 0){
            return ['code' => 'none'];
        }

        $coupon_usage = CouponUsage::whereHas('coupon', function($query) use($senpai_id, $money){
            $query->where('cup_from_user_id', $senpai_id)
                ->orWhere('cup_from_user_id', 0)
            ->where('cup_apply_condition', ">=", $money);
        })
        ->where('cpu_state', config('const.coupon_state.valid'))
        ->where('cpu_user_id', $kouhai_id)
        ->where('cpu_date_to', '>=', date('Y-m-d', time()))
        ->with('coupon')
        ->first();

        $result = array();
        $result['code'] = 'exist';
        $result['obj'] = $coupon_usage;
        if($coupon_usage != null){
            $result['valid_cnt'] = CouponUsage::where('cpu_state', config('const.coupon_state.valid'))
                ->where('cpu_cup_id', $coupon_usage['cpu_cup_id'])
                ->where('cpu_user_id', $kouhai_id)
                ->count();
            return $result;
        }

        $coupon = Coupon::orderByDesc('cup_reduce_money')
            ->where(function($query) use($senpai_id){
                $query->where('cup_from_user_id', $senpai_id)
                    ->orWhere('cup_from_user_id', 0);
            })
            ->where('cup_apply_condition', '<=', $money)
            ->where('cup_visible', 0)
            ->first();

        if($coupon == null){
            return ['code' => 'null'];
        }
        $result['code'] = 'new';
        $result['obj'] = $coupon;
        $result['valid_cnt'] = $coupon['cup_cnt_origin'];
        return $result;
    }

    public static function setCouponNew($cup_id, $kouhai_id, $lrs_id)
    {
        $coupon = Coupon::where('cup_id', $cup_id)->first();
        $coupon_num = $coupon['cup_cnt_origin'];

        $data['cpu_cup_id'] = $cup_id;
        $data['cpu_user_id'] = $kouhai_id;
        $data['cpu_lrs_id'] = $lrs_id;

        $days = $coupon['cup_period'];
        $data['cpu_date_to'] = date('Y-m-d', strtotime("+$days days"));
        $data['cpu_state'] = config('const.coupon_state.used');
        $data['cpu_date_get'] = date('Y-m-d', time());
        $data['cpu_order'] = 1;


        CouponUsage::create($data);

        $data['cpu_lrs_id'] = 0;
        $data['cpu_state'] = config('const.coupon_state.valid');

        for ($i = 0; $i < $coupon_num - 1; $i++)
        {
            $data['cpu_order'] = $i + 2;
            CouponUsage::create($data);
        }
    }

    public static function setCouponUpdate($cpu_id, $lrs_id, $state)
    {
        $data['cpu_lrs_id'] = $lrs_id;
        $data['cpu_state'] = $state;

        CouponUsage::where('cpu_id', $cpu_id)
            ->update($data);
    }

    public static function updateCouponUsageOld()
    {
        $data['cpu_state'] = config('const.coupon_state.old');
        CouponUsage::where('cpu_date_to', '<', date('Y-m-d', time()))
            ->where('cpu_state', config('const.coupon_state.valid'))->update($data);
    }

    public static function getCouponValue($schedule_id) {
        $coupon_info = CouponUsage::where('cpu_lrs_id', $schedule_id)
            ->where('cpu_state', config('const.coupon_state.used'))
            ->with('coupon')
            ->first();

        if ( is_null($coupon_info) || is_null($coupon_info['coupon']) ) {
            return NULL;
        }

        $first_coupon_info = CouponUsage::where('cpu_cup_id', $coupon_info['cpu_cup_id'])
            ->where('cpu_state', config('const.coupon_state.used'))
            ->where('cpu_date_to', $coupon_info['cpu_date_to'])
            ->orderBy('updated_at')
            ->first();

        if ( $coupon_info['cpu_id'] == $first_coupon_info['cpu_id'] ) {
            return $coupon_info['coupon']['cup_sell_money'] - $coupon_info['coupon']['cup_reduce_money'];
        }

        return $coupon_info['coupon']['cup_reduce_money'] * (-1);
    }

    public static function cancelCoupon($schedule_id) {
        return;
    }

    public static function doDeleteCoupon($params) {
        if (isset($params['cup_id']) && $params['cup_id']) {
            $obj_coupon = Coupon::find($params['cup_id']);
            if (is_object($obj_coupon)) {
                return $obj_coupon->delete();
            }
        }
        return false;
    }
}
