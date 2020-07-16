<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Comment;
use App\Post;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id' , $id)->first();
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
}
