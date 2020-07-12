<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{

    protected $fillable = [
        'content',
        'filename',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id' , 'id' , 'users');
    }
    // public function user()
    // {

    // }
}
