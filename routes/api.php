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

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'master-data'
], function () {
    Route::get('banner/list', 'BannerController@list')->name('banner.list');
    Route::get('banner/{id}', 'BannerController@show')->name('banner.read');
    Route::post('banner/create', 'BannerController@store')->name('banner.create');
    Route::put('banner/update/{id}', 'BannerController@update')->name('banner.update');
    Route::delete('banner/delete/{id}', 'BannerController@destroy')->name('banner.destroy');

    Route::get('jenis-cuti/list', 'JenisCutiController@list')->name('jenisCuti.list');
    Route::get('jenis-cuti/{id}', 'JenisCutiController@show')->name('jenisCuti.read');
    Route::post('jenis-cuti/create', 'JenisCutiController@store')->name('jenisCuti.create');
    Route::put('jenis-cuti/update/{id}', 'JenisCutiController@update')->name('jenisCuti.update');
    Route::delete('jenis-cuti/delete/{id}', 'JenisCutiController@destroy')->name('jenisCuti.destroy');

    Route::get('alasan-presensi/list', 'AlasanPresensiController@list')->name('alasanPresensi.list');
    Route::get('alasan-presensi/{id}', 'AlasanPresensiController@show')->name('alasanPresensi.read');
    Route::post('alasan-presensi/create', 'AlasanPresensiController@store')->name('alasanPresensi.create');
    Route::put('alasan-presensi/update/{id}', 'AlasanPresensiController@update')->name('alasanPresensi.update');
    Route::delete('alasan-presensi/delete/{id}', 'AlasanPresensiController@destroy')->name('alasanPresensi.destroy');
});

Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');
