<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionClass extends BaseModel
{
    use SoftDeletes;
    protected $primaryKey = 'qc_id';
    protected $fillable = [
        'qc_name',
        'qc_parent',
        'qc_name',
        'qc_public',
        'qc_sort'
    ];
}
