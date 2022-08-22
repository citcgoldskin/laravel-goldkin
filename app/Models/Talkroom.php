<?php

namespace App\Models;
use \App\Service\TalkroomService;
use Illuminate\Database\Eloquent\SoftDeletes;

class Talkroom extends BaseModel
{
    use SoftDeletes;
    protected $fillable =
        [
            'id',
            'user_id',
            'menu_type',
            'talk_user_id',
            'state'
        ];

    public function unreadMessages()
    {
        return $this->hasMany(TalkroomMessage::class)
            ->where('state', config('const.msg_state.unread'))
            ->orderBy('id');
    }

    public function readMessages()
    {
        return $this->hasMany(TalkroomMessage::class)
            ->where('state', config('const.msg_state.read'))
            ->orderBy('id');
    }

    public function talkUserInfo()
    {
        return $this->hasOne(User::Class, 'id', 'talk_user_id');
    }

}
