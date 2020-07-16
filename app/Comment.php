<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $visible = [
        'commenter', 'content','created_at','id' , 'post_id'
    ];
    public function commenter(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id' , 'id' , 'users');
    }

    // public function post(): BelongsTo
    // {
    //     return $this->belongsTo('App\Post');
    // }
}
