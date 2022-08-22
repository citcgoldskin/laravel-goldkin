<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalkroomMessage extends BaseModel
{
    use SoftDeletes;
    protected $fillable =
        [
          'id',
          'talkroom_id',
          'schedule_id',
          'msg_type',
          'message',
          'state'
        ];

    public function talkroom()
    {
        return $this->belongsTo(Talkroom::class, 'talkroom_id', 'id');
    }
}
