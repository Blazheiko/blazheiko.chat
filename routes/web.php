<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/newlogin', 'Auth\NewLoginController@newlogin')->name('newlogin');

Route::get('/profile',               ['as' => 'profile',   'uses' => 'UserController@profile']);
Route::post('profile',               ['as' => 'profile.update',  'uses' =>'UserController@update_avatar']);
Route::get('/profile/{profile}/show',['as' => 'profile.show',    'uses' => 'UserController@show']);
Route::get('/profile/{profile}/edit',['as' => 'profile.edit',    'uses' => 'UserController@edit']);
Route::post('/profile/store',        ['before' => 'csrf','as' => 'profile.store',   'uses' => 'ProfileController@store']);

Route::get('/blog',               ['as' => 'blog',   'uses' => 'UserController@blog']);

Route::get('/blogs',              ['as' => 'blogs',        'uses' => 'BlogController@index']);
Route::get('/post/create',        ['as' => 'post.create',  'uses' => 'BlogController@create']);
Route::post('/post',              ['as' => 'post.store',   'uses' => 'BlogController@store']);
Route::get('/post/{post}/show',   ['as' => 'post.show',    'uses' => 'BlogController@show']);
Route::get('/post/{post}/edit',   ['as' => 'post.edit',    'uses' => 'BlogController@edit']);
Route::post('/post/{post}',       ['as' => 'post.update',  'uses' => 'BlogController@update']);
Route::get('/post/{post}/delete', ['as' => 'post.destroy', 'uses' => 'BlogController@destroy']);

Route::get('/comment/create',           ['as' => 'comment.create',  'uses' => 'CommentController@create']);
Route::post('/comment/{post}/store',    ['as' => 'comment.store',   'uses' => 'CommentController@store']);
Route::get('/comment/{comment}/show',   ['as' => 'comment.show',    'uses' => 'CommentController@show']);
Route::get('/comment/{comment}/edit',   ['as' => 'comment.edit',    'uses' => 'CommentController@edit']);
Route::post('/comment/{comment}',       ['as' => 'comment.update',  'uses' => 'CommentController@update']);
Route::get('/comment/{comment}/delete', ['as' => 'comment.destroy', 'uses' => 'CommentController@destroy']);

Route::get('/chat', 'ChatsController@index');
Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');
Route::post('/photo',            ['as' => 'addphoto',  'uses' =>'UploadPhotoController']);

//Route::get('/facebook/redirect', 'Auth\SocialAuthFacebookController@redirectToProvider');
//Route::get('/facebook/callback', 'Auth\SocialAuthFacebookController@handleProviderCallback');
//
//Route::get('/google/redirect', 'Auth\SocialAuthGoogleController@redirectToProvider');
//Route::get('/google/callback', 'Auth\SocialAuthGoogleController@handleProviderCallback');

Route::get('/{provider}/redirect', 'Auth\SocialAuthController@redirectToProvider');
Route::get('/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');


Route::group( [ 'middleware' => 'admin', 'prefix' => 'admin' ], function () {
    Route::get('/', 'Admin\AdminController@index' );
});
