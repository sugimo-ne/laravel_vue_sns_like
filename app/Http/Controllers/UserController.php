<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Comment;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id' , $id)->with(['followers' , 'followings'])->first();
        $posts = Post::where('user_id' , $id)->with(['user' , 'comments.commenter' , 'likes'])->get();
        $Comments = Comment::where('user_id' , $id)->get();
        return ['user' => $user , 'posts' => $posts , 'Comments' => $Comments];
    }

    public function Comments(string $id)
    {
        $Comments = Comment::where('user_id' , $id)->get();
        
        return $Comments;
    }

    public function likes(string $id)
    {
        $user = User::where('id' , $id)->first();
        $likes = $user->likes->sortByDesc('created_at');

        return  $likes;
    }

    public function info(string $id)
    {
        $user = User::where('id' , $id)->first();
        return $user;
    }

    public function follow(string $id)
    {
        $current_user = User::where('id',Auth::user()->id)->first();
        $user = User::where('id' , $id)->first();

        if(!$user){
            abort(404);
        }

        $current_user->followings()->detach($user);
        $current_user->followings()->attach($user);

        return ['follow' , $id];
    }
    public function unfollow(string $id)
    {
        $current_user = User::where('id',Auth::user()->id)->first();
        $user = User::where('id' , $id)->first();

        if(!$user){
            abort(404);
        }

        $current_user->followings()->detach($user);

        return ['follow' , $id];
    }

    public function followList(string $id){
        $user = User::where('id' , $id)->with(['followers' , 'followings'])->first();
        $current_user = User::where('id',Auth::user()->id)->with(['followers' , 'followings'])->first();
        return ['about_user' => $user , 'current_user' => $current_user];
    }

    // public function ()
    // {
    //     $user = User::where('id' , 4)->first();
    //     $following= $user->followings()->get();
    //     $follower= $user->followers()->get();
    //     return ['following(フォロー中)' => $following , 'user' => $user , 'follower(フォロワー)' => $follower];
    // }
    

}
