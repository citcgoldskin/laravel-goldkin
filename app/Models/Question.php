<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends BaseModel
{
    use SoftDeletes;
    protected $primaryKey = 'que_id';
    protected $fillable = [
        'que_qc_id',
        'que_ask',
        'que_answer',
        'que_public',
        'que_public_at',
        'que_update_at',
        'que_update_data',
        'que_delete_at',
        'que_status',
        'que_frequent',
        'from_user_id'
    ];

    public function question_category()
    {
        return $this->belongsTo(QuestionClass::class,  'que_qc_id', 'qc_id');
    }

    public function getQuestionParentCategoryAttribute()
    {
        $ret = QuestionClass::where('qc_id', $this->question_category->qc_parent)->first();
        return is_object($ret) ? $ret->qc_id : '';
    }

    public function getQuestionParentCategoryNameAttribute()
    {
        $ret = QuestionClass::where('qc_id', $this->question_category->qc_parent)->first();
        return is_object($ret) ? $ret->qc_name : '';
    }

}
