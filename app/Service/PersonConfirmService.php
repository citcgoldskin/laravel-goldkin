<?php

namespace App\Service;

use App\Models\PersonConfirm;


class PersonConfirmService
{
    public static function doSearch($condition)
    {
        $person_confirms = PersonConfirm::select('person_confirms.*');
        if (isset($condition['status'])) {
            $person_confirms->where('pc_state', $condition['status']);
        }

        if (isset($condition['onof-line']) && $condition['onof-line']) {
            if ($condition['onof-line'] == config('const.person_confirm_browser.new')) { // 新規
                // $person_confirms->where('pc_state', $condition['status']);
                $person_confirms->whereNotIn('pc_user_id', function($query) {
                    $query->select('pc_user_id')
                        ->from('person_confirms')
                        ->where('pc_state', config('const.pers_conf.confirmed'));
                });
                /*$person_confirms->whereHas('pc_user_id',function($q) use ($filters){
                    return $q->where('state_id','=',$filters['state_id']);
                });*/
            } else if($condition['onof-line'] == config('const.person_confirm_browser.change')) { // 差し替え
                // $person_confirms->where('pc_state', $condition['status']);
                $person_confirms->whereIn('pc_user_id', function($query) {
                    $query->select('pc_user_id')
                        ->from('person_confirms')
                        ->where('pc_state', config('const.pers_conf.confirmed'));
                });
            }
        }
        $person_confirms->where('pc_state', config('const.pers_conf.make'));
        return $person_confirms;
    }
    public static function createPersonConfirm($data)
    {
        PersonConfirm::create($data);
    }

    public static function updatePersonConfirm($id, $data)
    {

    }

    public static function getUserConfirmedBefore($user_id) {
        return PersonConfirm::where('pc_state', config('const.pers_conf.confirmed'))
                ->where('pc_user_id', $user_id)
                ->get()
                ->count() > 0;
    }

    public static function getRequestPersonConfirmCount($type) {
        $condition = [
            'onof-line' => $type
        ];

        return self::doSearch($condition)
                ->get()
                ->count();
    }

    public static function doPersonConfirm($condition) {
        $obj_pc = PersonConfirm::find($condition['person_confirm_id']);
        if (is_object($obj_pc)) {
            if ($condition['agree_type'] == config('const.person_confirm_agree_category.agree')) {
                $obj_pc->pc_state = config('const.pers_conf.confirmed');
            } else if($condition['agree_type'] == config('const.person_confirm_agree_category.disagree')) {
                $obj_pc->pc_state = config('const.pers_conf.rejected');
            }
            return $obj_pc->save();
        }
        return false;
    }
}
