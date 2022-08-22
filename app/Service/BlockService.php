<?php

namespace App\Service;

use App\Models\Block;
use Auth;
use Carbon\Carbon;
use DB;

class BlockService
{
    public static function doSearch($condition=[]) {
        $staffs = Block::selectRaw('count(blocks.bl_user_id) as cnt_block, MAX(blocks.reported_at) as end_reported_at, MIN(blocks.status) as exist_not_read, blocks.*')->with('block_user')->groupBy('blocks.bl_block_id');
        $staffs->leftJoin('users', function ($join) {
            $join->on('blocks.bl_block_id', 'users.id');
        });

        // ID
        if (isset($condition['user_no']) && $condition['user_no']) {
            $staffs->where("user_no", 'like', '%'.$condition['user_no'].'%');
        }

        // 未読のみを表示する
        if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
            $staffs->where("status", config('const.read_status.not_read'));
        }

        // エリア
        if (isset($condition['area']) && $condition['area']) {
            $staffs->whereIn('blocks.bl_block_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where('user_area_id', $condition['area']);
            });
        }
        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $staffs->whereDate('blocks.reported_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $staffs->whereDate('blocks.reported_at', '<=', $condition['to_date']);
        }

        // ニックネーム
        if (isset($condition['nickname']) && $condition['nickname']) {
            $staffs->whereRaw("name LIKE ?", '%'.$condition['nickname'].'%');
        }

        // 性別
        if (isset($condition['user_sex']) && $condition['user_sex']) {
            $staffs->where("user_sex", $condition['user_sex']);
        }

        // 会員種別
        if (isset($condition['user_type']) && !is_null($condition['user_type'])) {
            $staffs->where("user_is_senpai", $condition['user_type']);
        }

        // order
        if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.report_sort_code.register_new')) {
                $staffs->orderByDesc('end_reported_at');
            } else if($condition['order'] == config('const.report_sort_code.register_old')) {
                $staffs->orderBy('end_reported_at');
            } else if($condition['order'] == config('const.report_sort_code.report_count')) {
                $staffs->orderByDesc('cnt_block');
            }
        }

        // ぴろしきまるのみ表示
        if(isset($condition['punishment']) && $condition['punishment']) {
            $staffs->whereIn('blocks.bl_block_id', function($query) use($condition) {
                $query->select('id')
                    ->from('users')
                    ->where(function($q) {
                        $q->where('punishment_cnt', '>', 0);
                        $q->orWhere('caution_cnt', '>', 0);
                    });
            });
        }

        return $staffs;
    }

    public static function setBlockState($block_user_id, $state)
    {
        $user_id = Auth::user()->id;
        $model = Block::where('bl_user_id', $user_id)
            ->where('bl_block_id', $block_user_id);

        if ( !$state ) {
            if($model->count() <= 0) return true;
            return $model->delete();
        } else {
            if($model->count() > 0)
                return true;

            $data['bl_user_id'] = $user_id;
            $data['bl_block_id'] = $block_user_id;
            $data['reported_at'] = Carbon::now()->format('Y-m-d H:i:s');
            return Block::create($data);
        }
        return true;
    }

    public static function isBlockUser($block_user_id) {
        $block = Block::where('bl_user_id', Auth::user()->id)
            ->where('bl_block_id', $block_user_id)
            ->first();

        if (is_null($block))
            return false;

        return true;

    }

    public static function getWhoseBlock($block_user_id) {
        $user_id = Auth::user()->id;
        $block = Block::where('bl_user_id', $user_id)
            ->where('bl_block_id', $block_user_id)
            ->first();

        if (!is_null($block))
            return 'self_block';

        $user_block = Block::where('bl_user_id', $block_user_id)
            ->where('bl_block_id', $user_id)
            ->first();
        if (!is_null($user_block))
            return 'other_block';

        return 'no_block';

    }

    public static function getBlockList($user_id) {
        return Block::orderBy('bl_id', 'asc')
            ->where('bl_user_id', $user_id)
            ->with('user')
            ->get();
    }

    public static function getBlock($bl_id) {
        return Block::where('bl_id', $bl_id)
            ->with('user')
            ->first();
    }

    public static function deleteBlock($bl_id) {
        return Block::destroy($bl_id);
    }

    public static function getBlockUserInfo($obj_user, $condition=[]) {
        $ret = Block::where('bl_block_id', $obj_user->id);

        // 未読ステータス
        if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
            $ret->where('status', config('const.read_status.not_read'));
        }

        // 期間
        if(isset($condition['from_date']) && $condition['from_date']) {
            $ret->whereDate('reported_at', '>=', $condition['from_date']);
        }
        if(isset($condition['to_date']) && $condition['to_date']) {
            $ret->whereDate('reported_at', '<=', $condition['to_date']);
        }

        return $ret->orderByDesc('reported_at')->get();

        /*return Block::select('blocks.*')
            ->orderByDesc('updated_at')
            ->where('bl_block_id', $obj_user->id)
            ->get();*/
    }

    public static function getUnreadBlock($block_user_id=null) {
        $count = Block::orderByDesc('updated_at');
        if (!is_null($block_user_id)) {
            $count = Block::where('bl_block_id', $block_user_id);
        }
        $count->where(function ($query) {
            $query->where('status', config('const.read_status.not_read'));
            $query->orWhereNull('status');
        });
        $count = $count->get()->count();
        return $count;
    }

    public static function setBlockRead($block_users) {
        if (count($block_users) > 0) {
            foreach ($block_users as $block_user) {
                $block_user->status = config('const.read_status.read');
                $block_user->save();
            }
        }
    }

    public static function doSetNotRead($block_ids) {
        return Block::whereIn('bl_id', $block_ids)
            ->update([
                'status'=>config('const.read_status.not_read')
            ]);
    }

}
