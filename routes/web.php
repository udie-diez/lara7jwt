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
    // Route::post('register', 'Auth\RegisterController@register')->name('register.post');

    Route::get('login', 'Auth\LoginController@showLoginform')->name('login');
    Route::post('login', 'Auth\LoginController@loginSubmit');

    Route::get('forgot-password', 'Auth\PasswordResetController@showForgotForm')->name('forgot');
    // Route::post('forgot-password', 'Auth\PasswordResetController@sendForgotLink')->name('forgot.sendLink');
    Route::get('reset-password/{token}', 'Auth\PasswordResetController@showResetForm')->name('reset');
    // Route::post('reset-password', 'Auth\PasswordResetController@reset')->name('reset.post');
});
