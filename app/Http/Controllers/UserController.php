<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Comment;
use App\Post;
use App\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePhoto;
use App\Http\Requests\UserEdit;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id', $id)->with(['followers', 'followings'])->first();
        $posts = Post::where('user_id', $id)->with(['user', 'comments.commenter', 'likes'])->get();
        $Comments = Comment::where('user_id', $id)->get();
        return ['user' => $user, 'posts' => $posts, 'Comments' => $Comments];
    }

    public function Comments(string $id)
    {
        $Comments = Comment::where('user_id', $id)->get();

        return $Comments;
    }

    public function likes(string $id)
    {
        $user = User::where('id', $id)->first();
        $likes = $user->likes->sortByDesc('created_at');
        $likes_ = json_decode(json_encode($likes), true);
        $posts = [];
        foreach($likes_ as $like)
        {
           $info = Post::where('id' , $like['id'])->with(['user'])->first();
           $posts []= $info;
        }
        //$info = json_decode(json_encode($info), true);
        return  [
            //'likes' => $likes , 
            'posts' =>$posts ];//$posts];
    }

    public function info(string $id)
    {
        $user = User::where('id', $id)->first();
        return $user;
    }

    public function follow(string $id)
    {
        $current_user = User::where('id', Auth::user()->id)->first();
        $user = User::where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        $current_user->followings()->detach($user);
        $current_user->followings()->attach($user);

        return ['follow', $id];
    }
    public function unfollow(string $id)
    {
        $current_user = User::where('id', Auth::user()->id)->first();
        $user = User::where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        $current_user->followings()->detach($user);

        return ['follow', $id];
    }

    public function followList(string $id)
    {
        $user = User::where('id', $id)->with(['followers', 'followings'])->first();
        if(Auth::user()){
            $current_user = User::where('id', Auth::user()->id)->with(['followers', 'followings'])->first();
        }else{
            return ['about_user' => $user];
        }
        return ['about_user' => $user, 'current_user' => $current_user];
    }

    public function createPhoto(StorePhoto $request)
    {
        $extension = $request->photo->extension();

        $photo = new Photo();

        $photo->filename = $photo->id . '.' . $extension;
        Storage::cloud()
            ->putFileAs('', $request->photo, $photo->filename, 'public');
        DB::beginTransaction();

        try {
            $photo->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
   
            Storage::cloud()->delete($photo->filename);
            throw $exception;

            return response($photo, 201);
        }

        return ['photo_name' => $photo->filename];
    }
    public function editProfile(UserEdit $request , string $id)
    {
        $user = User::where('id' , $id)->first();
        $user->name = $request->name;
        $user->intro = $request->intro;
        $user->image = $request->image;
        $user->save();

        return response($user , 201);
    }
}
