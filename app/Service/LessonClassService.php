<?php

namespace App\Service;

use App\Models\Lesson;
use App\Models\LessonClass;
use DB;

class LessonClassService
{
    public function __construct()
    {
        $this->lessonClassQuery = LessonClass::query();
    }

    public static function getAllLessonClasses() {
        return LessonClass::orderBy('class_sort')
            ->select('class_id','class_name', 'class_image', 'class_icon', 'class_sort')
            ->get();
    }

    public static function getLessonClasses() {
        $result = LessonClass::withCount(['lesson as cnt' => function ($query){
            $query->where('lesson_state', config('const.lesson_state.public'));
        }])->get();
        return $result;
    }

    public static function getClass($id)
    {
        return LessonClass::where("class_id", $id)
            ->first();
    }
}
