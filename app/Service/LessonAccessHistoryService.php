<?php

namespace App\Service;

use App\Models\LessonAccessHistory;
use App\Models\LessonArea;
use Auth;
use DB;

class LessonAccessHistoryService
{
    public static function create(array $values)
    {
        if (!self::existLessonAccess($values)) {
            // create
            self::createLessonAccess($values);
        }
        return true;
    }

    public static function existLessonAccess($condition)
    {
        return LessonAccessHistory::where('lesson_id', $condition['lesson_id'])
            ->where('user_id', $condition['user_id'])
            ->where('token', $condition['token'])
            ->exists();
    }

    public static function createLessonAccess($values) {
        $obj = new LessonAccessHistory();
        $obj->user_id = $values['user_id'];
        $obj->lesson_id = $values['lesson_id'];
        $obj->token = $values['token'];
        return $obj->save();
    }

    public static function getLessonAccessHistoryInfo($user_id) {
        return LessonAccessHistory::orderBy('created_at')
            ->where('user_id', $user_id)
            ->get()
            ->groupBy('lesson_id')
            ->take(10);
    }
}
