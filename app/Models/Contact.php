<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_type',
        'contact_type',
        'content'
    ];

}
