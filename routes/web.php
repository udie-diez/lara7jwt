<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'web',
    'prefix' => 'auth'
], function () {
    Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('register');
    Route::get('login', 'Auth\LoginController@showLoginform')->name('login');
    Route::get('forgot-password', 'Auth\PasswordResetController@showForgotForm')->name('forgot');
    Route::get('reset-password/{token}', 'Auth\PasswordResetController@showResetForm')->name('reset');
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'master-data'
], function () {
    Route::get('banner', 'BannerController@index')->name('banner');
    Route::get('jenis-cuti', 'JenisCutiController@index')->name('jenisCuti');
    Route::get('alasan-presensi', 'AlasanPresensiController@index')->name('alasanPresensi');
});
