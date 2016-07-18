<?php

Route::auth();

Route::get('/', ['uses' => 'htmlController@index', 'as' => 'web_home']);

//Route::get('/', ['uses' => 'HomepageController@index', 'as' => 'web_home']);

Route::resource('photo', 'PhotoController');
Route::resource('event', 'EventController');
Route::resource('event.photo', 'EventPhotoController');
Route::resource('event.tag', 'EventTagController');

Route::any('allEvents', ['uses' => 'EventController@allEvents']);
Route::any('events', ['uses' => 'EventController@allEvents']);

Route::any('adminEvents', 'EventController@adminEvents');
Route::any('sharedEvents', 'EventController@sharedEvents');


Route::resource('user.event', 'UserEventController');

Route::controller('photos', 'PhotoController', [
    'getUpload' => 'photos.upload'
]);

Route::controller('user', 'UserController');
Route::controller('events', 'EventController', [
    'anyAdmin' => 'events.admin',
    'anyShared' => 'events.shared'
]);
Route::controller('comments', 'CommentController');

Route::controller('auth', 'Auth\AuthController');
Route::controller('password', 'Auth\PasswordController');


Route::get('_tokendic', ['uses' => 'HomeController@tokendic']);

Route::any('token', ['uses' => 'HomeController@tokendic']);

Route::get('progress', ['uses' => 'HomeController@progress']);

Route::get('gettoken', ['uses' => 'HomeController@gettoken']);

Route::controller('policies', 'PolicyController');

//Route::group(['middleware' => 'web'], function () {

Route::get('home', 'HomeController@index');
//});
Route::get('feedback', 'FeedbackController@index');
Route::post('feedback', 'FeedbackController@store');