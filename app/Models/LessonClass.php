<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class LessonClass extends BaseModel
{
    use SoftDeletes;
    public $primaryKey = 'class_id';
    protected $fillable = ['class_name',
        'class_image',
        'class_icon',
        'class_sort'
        ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];

    public function lesson()
    {
        return $this->hasMany(Lesson::class,  'lesson_class_id','class_id');
    }
}
