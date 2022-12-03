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
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('token-refresh', 'Auth\LoginController@refresh')->name('token.refresh');
    Route::get('me', 'Auth\LoginController@me')->name('me');
    Route::post('forgot-password', 'Auth\PasswordResetController@sendForgotLink')->name('forgot.sendLink');
    Route::post('reset-password', 'Auth\PasswordResetController@reset')->name('reset.post');
});

Route::group([
    // 'middleware' => ['jwt.verify']
], function () {
    Route::get('banner/list', 'BannerController@list')->name('banner.list');
    Route::get('banner/search', 'BannerController@find')->name('banner.search');
    Route::get('banner/{id}', 'BannerController@show')->name('banner.read');
    Route::post('banner/create', 'BannerController@store')->name('banner.create');
    Route::put('banner/update/{id}', 'BannerController@update')->name('banner.update');
    Route::delete('banner/delete/{id}', 'BannerController@destroy')->name('banner.destroy');

    Route::get('jenis-cuti/list', 'JenisCutiController@list')->name('jenisCuti.list');
    Route::get('jenis-cuti/search', 'JenisCutiController@find')->name('jenisCuti.search');
    Route::get('jenis-cuti/{id}', 'JenisCutiController@show')->name('jenisCuti.read');
    Route::post('jenis-cuti/create', 'JenisCutiController@store')->name('jenisCuti.create');
    Route::put('jenis-cuti/update/{id}', 'JenisCutiController@update')->name('jenisCuti.update');
    Route::delete('jenis-cuti/delete/{id}', 'JenisCutiController@destroy')->name('jenisCuti.destroy');

    Route::get('alasan-presensi/list', 'AlasanPresensiController@list')->name('alasanPresensi.list');
    Route::get('alasan-presensi/search', 'AlasanPresensiController@find')->name('alasanPresensi.search');
    Route::get('alasan-presensi/{id}', 'AlasanPresensiController@show')->name('alasanPresensi.read');
    Route::post('alasan-presensi/create', 'AlasanPresensiController@store')->name('alasanPresensi.create');
    Route::put('alasan-presensi/update/{id}', 'AlasanPresensiController@update')->name('alasanPresensi.update');
    Route::delete('alasan-presensi/delete/{id}', 'AlasanPresensiController@destroy')->name('alasanPresensi.destroy');

    Route::get('alasan-cuti/list', 'AlasanCutiController@list')->name('alasanCuti.list');
    Route::get('alasan-cuti/search', 'AlasanCutiController@find')->name('alasanCuti.search');
    Route::get('alasan-cuti/{id}', 'AlasanCutiController@show')->name('alasanCuti.read');
    Route::post('alasan-cuti/create', 'AlasanCutiController@store')->name('alasanCuti.create');
    Route::put('alasan-cuti/update/{id}', 'AlasanCutiController@update')->name('alasanCuti.update');
    Route::delete('alasan-cuti/delete/{id}', 'AlasanCutiController@destroy')->name('alasanCuti.destroy');
});

Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'
    ], 404);
})->where('any', '.*');
