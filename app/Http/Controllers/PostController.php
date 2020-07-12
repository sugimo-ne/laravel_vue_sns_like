<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index' , 'show']);
    }

    public function index()
    {
        //$posts = Post::with(['user'])
        //->paginate();
        //$posts = Post::all()->sortByDesc('created_at');

        $posts = Post::with(['user'])
        ->orderBy(Post::CREATED_AT, 'desc')->paginate();

        return $posts;
    }

    public function show(string $id)
    {
        $post = Post::where('id' , $id)->with(['user'])->first();

        return $post ?? abort(404);
    }

    public function create(PostRequest $request , Post $post)
    {
        //$post = new Post();

        // $post->content = $request->content;
        // $post->filename = $request->filename;
        $post->fill($request->all());
        $post->user_id = $request->user_id;
        $post->save();

        return response($post , 201);
    }

    public function delete(string $id)
    {
        $user = Auth::user()->id;
        $post = Post::where('id' , $id)->first();
        if($post->user_id === $user){
            $post->delete();
        }else{
            return false;
        }
        
    }
}
