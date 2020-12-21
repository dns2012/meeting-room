<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// \Api\Admin routes
Route::group(['namespace' => 'Api\Admin', 'prefix' => 'v1/admin'], function() {

    // \Api\Admin\AuthController routes
    Route::group(['middleware' => 'guest', 'prefix' => 'auth'], function() {
        Route::post('login', 'AuthController@login')->name('auth.login');
    });

    // \Api\Admin\RoomController routes
    Route::group(['middleware' => 'auth:api_admin', 'prefix' => 'room'], function() {
        Route::post('create', 'RoomController@create')->name('room.create');
    });

});

// \Api\User routes
Route::group(['namespace' => 'Api\User', 'prefix' => 'v1/user'], function() {

    // \Api\User\AuthController routes
    Route::group(['middleware' => 'guest', 'prefix' => 'auth'], function() {
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('register', 'AuthController@register')->name('auth.register');
    });

    // \Api\User\RoomController routes
    Route::group(['middleware' => 'auth:api', 'prefix' => 'room'], function() {
        Route::get('available', 'RoomController@getAvailableRooms')->name('room.available');
    });

    // \Api\User\BookingController
    Route::group(['middleware' => 'auth:api', 'prefix' => 'booking'], function() {
        Route::post('create', 'BookingController@create')->name('room.create');
        Route::get('notify', 'BookingController@sendTodayBookingNotification')->name('room.today.notification');
        Route::post('checkinout', 'BookingController@checkInOutBookingRoomUser')->name('room.checkinout');
    });

});
