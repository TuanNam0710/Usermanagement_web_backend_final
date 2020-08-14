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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'api'], function () {
    Route::apiResource('users', 'UserController');
    Route::post('register', 'UserController@register');
    Route::apiResource('users', 'UserController');
});

Route::group(['middleware' => 'api', 'prefix' => 'i-forgot'], function () {
    Route::post('send-email', 'UserController@forgot');
});

Route::group(['middleware' => 'api', 'prefix' => 'reset-password'], function () {
    Route::post('check-otp', 'UserController@checkOTP');
    Route::post('reset', 'UserController@resetPassword');
});
