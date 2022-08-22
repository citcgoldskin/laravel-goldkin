<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class News extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'news';

    protected $fillable = [
        'category',
        'title',
        'content',
        'status',
        'publish_time',
        'dis_publish_time'
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
    protected $casts = [

    ];


}
