<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LessonChangeHistory extends Model
{
    use SoftDeletes;
    public $table = 'lesson_change_history';

    protected $fillable = [
        'lesson_id',
        'op_type',
        'changed_data',
        'origin_data',
        'updated_by'
    ];

    protected $casts = [
        'changed_data' => 'array',
        'origin_data' => 'array',
    ];

    public function getChangedDataAttribute($value)
    {
        return json_decode($value);
    }

    public function getOriginDataAttribute($value)
    {
        return json_decode($value);
    }

    public function obj_lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getChangeHistoryByAttribute()
    {
        $updated_by_arr = $this->updated_by ? explode('.', $this->updated_by) : [];
        $obj_updated_by = null;
        if (count($updated_by_arr) > 0) {
            if ($updated_by_arr[0] == 'user') {
                $obj_updated_by = Staff::find($updated_by_arr[1]);
            } else if($updated_by_arr[0] == 'admin') {
                $obj_updated_by = Admin::find($updated_by_arr[1]);
            }
        }
        if (is_object($obj_updated_by)) {
            return $obj_updated_by->name;
        }
        return "";
    }
}
