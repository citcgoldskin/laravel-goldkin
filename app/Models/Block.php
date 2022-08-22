<?php

namespace App\Models;

class Block extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'bl_id';

    protected $fillable = [
        'bl_user_id',
        'bl_block_id',
        'status',
        'reported_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    protected $appends = [
        'block_count',
        'last_updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function block_user(){
        return $this->belongsTo(User::class, 'bl_block_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'bl_user_id', 'id');
    }

    public function getLastUpdatedAtAttribute() {
        $obj_last = Block::where('bl_block_id', $this->bl_block_id)->orderByDesc('updated_at')->first();
        return is_object($obj_last) ? $obj_last->updated_at : '';
    }

    public function getBlockCountAttribute() {
        return Block::where('bl_block_id', $this->bl_block_id)->get()->count();
    }
}
