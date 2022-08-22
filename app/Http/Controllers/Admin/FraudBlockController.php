<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Service\BlockService;
use App\Service\UserService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Storage;
use Session;
use DB;

class FraudBlockController extends AdminController
{

    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.block', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $tmp_chk_not_read = null;
            if (isset($condition['chk_not_read']) && $condition['chk_not_read']) {
                $tmp_chk_not_read = $condition['chk_not_read'];
            }
            $tmp_order = null;
            if (isset($condition['order']) && $condition['order']) {
                $tmp_order = $condition['order'];
            }
            $condition = [
                "user_type" => null,
                "user_sex" => null,
                "user_id" => null,
                "nickname" => null
            ];
            if (!is_null($tmp_chk_not_read)) {
                $condition['chk_not_read'] = $tmp_chk_not_read;
            }
            if (!is_null($tmp_order)) {
                $condition['order'] = $tmp_order;
            }
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        }

        if (isset($params['chk_not_read'])) {
            $condition['chk_not_read'] = $params['chk_not_read'];
        }

        Session::put('admin.fraud.block', $condition);

        $obj_users = BlockService::doSearch($condition)->paginate($this->per_page);

        // check ソート
        /*if (isset($condition['order']) && $condition['order']) {
            if ($condition['order'] == config('const.report_sort_code.register_new')) {
                $obj_users->setCollection(
                    $obj_users->sortByDesc('last_updated_at')
                );
            } else if ($condition['order'] == config('const.report_sort_code.register_old')) {
                $obj_users->setCollection(
                    $obj_users->sortBy('last_updated_at')
                );
            } else if ($condition['order'] == config('const.report_sort_code.report_count')) {
                $obj_users->setCollection(
                    $obj_users->sortByDesc('block_count')
                );
            }
        }*/

        // check 期間 => 最終更新日
        /*if((isset($condition['from_date']) && $condition['from_date']) || (isset($condition['to_date']) && $condition['to_date'])) {
            $obj_users->setCollection(
                $obj_users->filter(function ($obj_user) use($condition) {
                    if ((isset($condition['from_date']) && $condition['from_date']) && (isset($condition['to_date']) && $condition['to_date'])) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') >= Carbon::parse($condition['from_date'])->format('Y-m-d') && Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') <= Carbon::parse($condition['to_date'])->format('Y-m-d');
                    } else if(isset($condition['from_date']) && $condition['from_date']) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') >= Carbon::parse($condition['from_date'])->format('Y-m-d');
                    } else if(isset($condition['to_date']) && $condition['to_date']) {
                        return Carbon::parse($obj_user->last_updated_at)->format('Y-m-d') <= Carbon::parse($condition['to_date'])->format('Y-m-d');
                    }
                })
            );
        }*/

        return view('admin.fraud.block.index', [
            'obj_users' => $obj_users,
            'search_params' => $condition
        ]);
    }

    public function detail(Request $request, User $user)
    {
        $condition = Session::get('admin.fraud.block', []);
        $block_users = BlockService::getBlockUserInfo($user, $condition);
        BlockService::setBlockRead($block_users);

        return view('admin.fraud.block.detail', [
            'own_user' => $user,
            'block_users' => $block_users,
        ]);
    }

    public function doSetNotRead(Request $request)
    {
        $params = $request->all();
        $block_ids = $params['block_id'];
        if (BlockService::doSetNotRead($block_ids)) {
            return redirect()->route('admin.fraud_block.index');
        }
        return back();
    }

}
