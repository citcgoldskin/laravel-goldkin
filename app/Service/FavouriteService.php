<?php

namespace App\Service;

use App\Models\Favourite;
use App\Models\LessonClass;
use App\Models\Lesson;
use Auth;
use DB;

class FavouriteService
{
    public static function setFavoriteRecruit($data)
    {
        //$op == 1 : insert, $op == 0 : delete
        if($data['bSelected'] == 1)
        {
            $data_query = array();
            $data_query['fav_user_id'] = $data['user_id'];
            $data_query['fav_type'] = config('const.fav_type.recruit');
            $data_query['fav_favourite_id'] = $data['id'];

            if($obj_recruit = Favourite::create($data_query))
            {
                return $obj_recruit;
            } else {
                return null;
            }
        } else
        {
            $objs = Favourite::where('fav_user_id', $data['user_id'])
                    ->where('fav_favourite_id', $data['id'])
                    ->delete();
        }
    }

    public static function getFavouriteRecruit($rc_id, $user_id = 0)
    {
        $models = Favourite::where('fav_favourite_id', $rc_id)
            ->where('fav_type', config('const.fav_type.recruit'));
        if($user_id != 0)
        {
            $models = $models->where('fav_user_id', $user_id);
        }
        $objs = $models->select('fav_id')
            ->get()->toArray();
        return $objs;
   }

   public static function getFavLessons($user_id)
   {
       return Favourite::where('fav_user_id', $user_id)
                        ->where('fav_type', config('const.fav_type.lesson'))
                        ->wherehas('lesson', function ($query){
                            $query->where('lesson_state', config('const.lesson_state.public'));
                        })
                        ->with('lesson', function ($query) {
                            $query
                                ->with('lesson_class')
                                ->with('senpai');
                        });
   }

   public static function getFavFollows($user_id)
   {
       return Favourite::where('fav_user_id', $user_id)
                        ->where('fav_type', config('const.fav_type.user'))
                        ->with('followSenpai');
   }

   public static function getFavFollowers($user_id)
   {
       return Favourite::where('fav_favourite_id', $user_id)
                        ->where('fav_type', config('const.fav_type.user'))
                        ->with('user');
   }

    public static function getFavouriteState($user_id, $senpai_user_id)
    {
        $results = Favourite::where('fav_type', config('const.fav_type.user'))
            ->where('fav_favourite_id', $senpai_user_id)
            ->where('fav_user_id', $user_id)
            ->count();
        return $results;

    }

    public static function setSenpaiFavourite($data)
    {
        if ($data['bSelected'] == 1) {
            $data_query = array();
            $data_query['fav_user_id'] = $data['user_id'];
            $data_query['fav_type'] = config('const.fav_type.user');
            $data_query['fav_favourite_id'] = $data['senpai_id'];

            if ($obj_recruit = Favourite::create($data_query)) {
                return $obj_recruit;
            } else {
                return null;
            }
        } else {
            $objs = Favourite::where('fav_type', config('const.fav_type.user'))
                ->where('fav_user_id', $data['user_id'])
                ->where('fav_favourite_id', $data['senpai_id'])
                ->select('fav_id')
                ->get();
            foreach ($objs as $obj) {
                Favourite::destroy($obj['fav_id']);
            }
        }
    }

    // count of favorites by user id
    public static function getFavoriteCountsByUerId($user_id) {
        $fav_counts = array();
        $fav_counts[0] = Favourite::where('fav_user_id', $user_id)->where('fav_type', config('const.fav_type.lesson'))->count();
        $fav_counts[1] = Favourite::where('fav_user_id', $user_id)->where('fav_type', config('const.fav_type.user'))->count();
        $fav_counts[2] = Favourite::where('fav_favourite_id', $user_id)->where('fav_type', config('const.fav_type.user'))->count();

        return $fav_counts;
    }
}
