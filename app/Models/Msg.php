<?php

namespace App\Models;

class Msg extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'msg_id';
    protected $fillable = [
        'msg_from_user_id',
        'msg_to_user_id',
        'msg_mc_id',
        'msg_mt_code',
        'msg_mt_name',
        'msg_open',
        'msg_content'
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
    public function user(){
        return $this->belongsTo(User::class, 'msg_from_user_id', 'id');
    }
    public function msg_tpl(){
        return $this->belongsTo(MsgTpl::class, 'msg_mt_code', 'mt_code');
    }
}
