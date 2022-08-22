<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionFrequent extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'question_id',
        'class',
        'sort'
    ];

    public function obj_question()
    {
        return $this->belongsTo(Question::class,  'question_id', 'que_id');
    }

}
