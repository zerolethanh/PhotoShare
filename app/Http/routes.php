<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != '443')) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

DB::connection()->enableQueryLog();

Route::group(['middleware' => 'web'], function () {
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

    Route::any('/', ['uses' => 'htmlController@index', 'as' => 'web_home']);

//    Route::any('user', ['uses' => 'UserController@getInfo']);


    Route::controller('auth', 'Auth\AuthController');
    Route::controller('password', 'Auth\PasswordController');

    Route::auth();

    Route::get('_tokendic', function () {
        $_token = csrf_token();
        return compact('_token');
    });
    Route::any('token', function () {
        return csrf_token();
    });
    Route::get('progress', function () {
        return view('progress');
    });

    Route::get('gettoken', function () {
        return ['X-CSRF-TOKEN' => csrf_token()];
    });


    Route::controller('policies', 'PolicyController');

});
