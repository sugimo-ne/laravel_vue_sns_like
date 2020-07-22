<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//認証系
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user' , function(){
   return Auth::user();
})->name('user');

//投稿系
Route::get('/posts', 'PostController@index')->name('post.index');
Route::get('/posts/{id}' , 'PostController@show')->name('post.show');
Route::post('/posts', 'PostController@create')->name('post.create');
Route::post('/posts/delete/{id}' , 'PostController@delete')->name('post.delete');
Route::post('/posts/{post}/comments' , 'PostController@addComment')->name('post.comment');
Route::post('/posts/{id}/comments/delete' , 'PostController@deleteComment')->name('post.deleteComment');
Route::put('/posts/{id}/like' , 'PostController@like')->name('post.like');
Route::delete('/posts/{id}/like' , 'PostController@unlike');

//ユーザー系
Route::get('/user/{id}' , 'UserController@show')->name('user.show');
Route::get('/user/likes/{id}' , 'UserController@likes')->name('user.likes');
Route::get('/{user_id}' , 'UserController@info');

//フォロー系
Route::put('/user/{id}/follow' , 'UserController@follow')->name('user.follow');//->middleware('auth');
Route::delete('/user/{id}/unfollow' , 'UserController@unfollow')->name('user.unfollow');//->middleware('auth');
Route::get('/user/{id}/follows' , 'UserController@followList')->name('user.followList');

//Route::get('/test/follow' , 'UserController@fuck')->name('user.fuck');


