<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionAccessHistory extends BaseModel
{
    use SoftDeletes;
    protected $table = 'question_access_history';
    protected $fillable = [
        'user_id',
        'question_id',
    ];

}
