<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

*/


Route::prefix('post')->group(function () {
    Route::get('/','PostController@index')->name('post.index');
    Route::post('add','PostController@add')->name('post.add');
    Route::post('add/likes','PostController@addUsersWhoLikedPost')->name('post.add.likes');
});


Route::prefix('fan')->group(function () {
    Route::get('/','FanController@index')->name('fan.index');
    Route::post('add','FanController@add')->name('fan.add');
    Route::post('add/list','FanController@addManyFansByList')->name('fan.add.list');
    Route::post('update','FanController@addFollowersAndFriends')->name('fan.update');
    Route::post('delete','FanController@deleteFansWithStatusNone')->name('fan.delete');
});