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

Route::get('home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('notifications', 'NotificationController@index');

    Route::post('p/{post}/like', 'Post\LikeController@like');
    Route::delete('p/{post}/like', 'Post\LikeController@unlike');

    Route::post('p/{post}/report', 'Post\ReportController@store');

    Route::post('u/{user}/follow', 'User\FollowController@follow');
    Route::post('u/{user}/unfollow', 'User\FollowController@unfollow');
});

Route::resource('p', 'PostController');

Route::get('{username}', 'UserController@show');
Route::get('{username}/followers', 'UserController@followers');
Route::get('{username}/following', 'UserController@following');

Route::resource('api/{user_id}/timeline', 'Api\TimelineController');
Route::get('api/{user_id}/p/liked', 'Api\Post\LikeController@index');
