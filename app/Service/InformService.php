<?php

namespace App\Service;

use App\Models\Inform;
use Auth;
use DB;

class InformService
{
    public static function setInformState($inform_user_id, $state)
    {
        $user_id = Auth::user()->id;
        $model = Inform::where('user_id', $user_id)
            ->where('inform_user_id', $inform_user_id);

         if ( $state ) {
            if($model->count() <= 0) return true;
            return $model->delete();
        } else {
            if($model->count() > 0)
                return true;

            $data['user_id'] = $user_id;
            $data['inform_user_id'] = $inform_user_id;
            return Inform::create($data);
        }
        return true;
    }

    public static function isInformUser($inform_user_id) {
        $inform = Inform::where('user_id', Auth::user()->id)
            ->where('inform_user_id', $inform_user_id)
            ->first();

        if (is_null($inform))
            return true;

        return false;
    }
}
