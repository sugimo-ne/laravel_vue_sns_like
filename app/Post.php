<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{

    // protected $visible = [
    //     'id', 'user', 'comments','likes','content','created_at', 
    //     'likes_count', 'liked_by_user',
    // ];

    protected $appends = [
        'likes_count', 'liked_by_user',
    ];


    protected $fillable = [
        'content',
        'filename',
    ];

    public function getCommentsCountAttribute()
    {
        return $this->comments->count();
    }
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }
    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id' , 'id' , 'users');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }
}
