<?php

namespace App\Service;

use App\Models\Favourite;
use App\Models\LessonClass;
use App\Models\Lesson;
use App\Models\RecruitVisit;
use Auth;
use DB;

class RecruitVisitService
{
    public static function setVisitRecruit($data)
    {
        $data_query = array();
        $data_query['user_id'] = $data['user_id'];
        $data_query['recruit_id'] = $data['recruit_id'];

        if(RecruitVisit::create($data_query)) {
            return true;
        }
        return false;
    }

    public static function getVisitInfo($rc_id, $user_id = 0)
    {
        $models = RecruitVisit::where('recruit_id', $rc_id);
        if($user_id != 0)
        {
            $models = $models->where('user_id', $user_id);
        }

        return $models->get();
   }

}
