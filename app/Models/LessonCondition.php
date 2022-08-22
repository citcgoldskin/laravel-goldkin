<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LessonCondition extends Model
{
    use SoftDeletes;
//    public $primaryKey = 'lesson_id';
//    protected $fillable =
//        ['class_name',
//        'class_image',
//        'class_icon',
//        'class_sort'
//        ];
//    const CREATED_AT = 'create_at';
//    const UPDATED_AT = 'update_at';
//    protected $hidden = ['created_at', 'updated_at'];
}
