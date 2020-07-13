<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Post extends Model
{
    // protected $visible = [
    //     'id', 'user', 'url', 'commenter',
    // ];

    protected $fillable = [
        'content',
        'filename',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id' , 'id' , 'users');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
    // public function user()
    // {

    // }
}
