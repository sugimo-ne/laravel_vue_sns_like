<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Requests\StoreComment;
use App\Post;
use App\User;
use App\Comment;
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

        $posts = Post::with(['user' , 'likes' , 'comments'])
        ->orderBy(Post::CREATED_AT, 'desc')->paginate(15);

        return $posts;
    }

    public function show(string $id)
    {
        $post = Post::where('id' , $id)->with(['user' , 'comments.commenter' , 'likes'])->first();

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
    public function addComment(StoreComment $request , Post $post){
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $post->comments()->save($comment);

        $new_comment = Comment::where('id', $comment->id)->with('commenter')->first();

        return response($new_comment, 201);
    }

    public function deleteComment(string $id)
    {
        $user = Auth::user()->id;
        $comment = Comment::where('id' , $id)->first();
        if($comment->user_id === $user){
            $comment->delete();
        }else{
            return false;
        }
    }

    public function like(string $id)
    {
        $post = Post::Where('id' , $id)->with('likes')->first();

        if(!$post){
            abort(404);
        }

        $post->likes()->detach(Auth::user()->id);
        $post->likes()->attach(Auth::user()->id);

        return ["post" => $id];
    }

    public function unlike(string $id)
    {
        $post = Post::Where('id' , $id)->with('likes')->first();

        if(!$post){
            abort(404);
        }

        $post->likes()->detach(Auth::user()->id);

        return ["post" => $id];
    }

}
