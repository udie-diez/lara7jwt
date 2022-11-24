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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('register', 'Auth\RegisterController@register')->name('register.post');
    Route::post('login', 'Auth\LoginController@login')->name('login.post');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('token-refresh', 'Auth\LoginController@refresh')->name('token.refresh');
    Route::get('me', 'Auth\LoginController@me')->name('me');
    Route::post('forgot-password', 'Auth\PasswordResetController@sendForgotLink')->name('forgot.sendLink');
    Route::post('reset-password', 'Auth\PasswordResetController@reset')->name('reset.post');
});

Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');
