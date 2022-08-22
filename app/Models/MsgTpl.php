<?php

namespace App\Models;

class MsgTpl extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'mt_code',
        'mt_name',
        'mt_msg_content',
        'mt_sms_content',
        'mt_mail_subject',
        'mt_mail_content'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

}
