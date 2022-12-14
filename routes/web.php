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
    return redirect('auth/login');
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->name('register.post');
    Route::get('login', 'Auth\LoginController@showLoginform')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('login.post');
    Route::get('forgot-password', 'Auth\PasswordResetController@showForgotForm')->name('forgot');
    Route::post('forgot-password', 'Auth\PasswordResetController@sendForgotLink')->name('forgot.sendLink');
    Route::get('reset-password/{token}', 'Auth\PasswordResetController@showResetForm')->name('reset');
    Route::post('reset-password', 'Auth\PasswordResetController@reset')->name('reset.post');
});

Route::group([
    'middleware' => 'AuthCheck',
], function () {
    Route::post('token-refresh', 'Auth\LoginController@refresh')->name('token.refresh');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::group([
        'prefix' => 'dashboard',
    ], function () {
        Route::get('/', 'Admin\DashboardController@index')->name('dashboard');
        Route::get('summary', 'Admin\DashboardController@counter')->name('dashboard.counter');
        Route::get('list', 'Admin\DashboardController@list')->name('dashboard.list');
        Route::get('{id}', 'Admin\DashboardController@show')->name('dashboard.read');
        Route::post('create', 'Admin\DashboardController@store')->name('dashboard.create');
        Route::put('update/{id}', 'Admin\DashboardController@update')->name('dashboard.update');
        Route::delete('delete/{id}', 'Admin\DashboardController@destroy')->name('dashboard.destroy');
    });

    Route::group([
        'prefix' => 'banner',
    ], function () {
        Route::get('/', 'Admin\MasterData\BannerController@index')->name('banner');
        Route::get('list', 'Admin\MasterData\BannerController@list')->name('banner.list');
        Route::get('{id}', 'Admin\MasterData\BannerController@show')->name('banner.read');
        Route::post('create', 'Admin\MasterData\BannerController@store')->name('banner.create');
        Route::put('update/{id}', 'Admin\MasterData\BannerController@update')->name('banner.update');
        Route::delete('delete/{id}', 'Admin\MasterData\BannerController@destroy')->name('banner.destroy');
    });

    Route::group([
        'prefix' => 'cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\CutiController@index')->name('cuti');
        Route::get('list', 'Admin\MasterData\CutiController@list')->name('cuti.list');
        Route::get('{id}', 'Admin\MasterData\CutiController@show')->name('cuti.read');
        Route::post('create', 'Admin\MasterData\CutiController@store')->name('cuti.create');
        Route::put('update/{id}', 'Admin\MasterData\CutiController@update')->name('cuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\CutiController@destroy')->name('cuti.destroy');
    });

    Route::group([
        'prefix' => 'jenis-cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\JenisCutiController@index')->name('jenisCuti');
        Route::get('list', 'Admin\MasterData\JenisCutiController@list')->name('jenisCuti.list');
        Route::get('{id}', 'Admin\MasterData\JenisCutiController@show')->name('jenisCuti.read');
        Route::post('create', 'Admin\MasterData\JenisCutiController@store')->name('jenisCuti.create');
        Route::put('update/{id}', 'Admin\MasterData\JenisCutiController@update')->name('jenisCuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\JenisCutiController@destroy')->name('jenisCuti.destroy');
    });

    Route::group([
        'prefix' => 'alasan-presensi',
    ], function () {
        Route::get('/', 'Admin\MasterData\AlasanPresensiController@index')->name('alasanPresensi');
        Route::get('list', 'Admin\MasterData\AlasanPresensiController@list')->name('alasanPresensi.list');
        Route::get('{id}', 'Admin\MasterData\AlasanPresensiController@show')->name('alasanPresensi.read');
        Route::post('create', 'Admin\MasterData\AlasanPresensiController@store')->name('alasanPresensi.create');
        Route::put('update/{id}', 'Admin\MasterData\AlasanPresensiController@update')->name('alasanPresensi.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AlasanPresensiController@destroy')->name('alasanPresensi.destroy');
    });

    Route::group([
        'prefix' => 'alasan-cuti',
    ], function () {
        Route::get('/', 'Admin\MasterData\AlasanCutiController@index')->name('alasanCuti');
        Route::get('list', 'Admin\MasterData\AlasanCutiController@list')->name('alasanCuti.list');
        Route::get('{id}', 'Admin\MasterData\AlasanCutiController@show')->name('alasanCuti.read');
        Route::post('create', 'Admin\MasterData\AlasanCutiController@store')->name('alasanCuti.create');
        Route::put('update/{id}', 'Admin\MasterData\AlasanCutiController@update')->name('alasanCuti.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AlasanCutiController@destroy')->name('alasanCuti.destroy');
    });

    Route::group([
        'prefix' => 'hari-libur',
    ], function () {
        Route::get('/', 'Admin\MasterData\HariLiburController@index')->name('hariLibur');
        Route::get('list', 'Admin\MasterData\HariLiburController@list')->name('hariLibur.list');
        Route::get('{date}', 'Admin\MasterData\HariLiburController@show')->name('hariLibur.read');
        Route::post('create', 'Admin\MasterData\HariLiburController@store')->name('hariLibur.create');
        Route::put('update/{id}', 'Admin\MasterData\HariLiburController@update')->name('hariLibur.update');
        Route::delete('delete/{id}', 'Admin\MasterData\HariLiburController@destroy')->name('hariLibur.destroy');
    });

    Route::group([
        'prefix' => 'app-version',
    ], function () {
        Route::get('/', 'Admin\MasterData\AppVersionController@index')->name('appVersion');
        Route::get('list', 'Admin\MasterData\AppVersionController@list')->name('appVersion.list');
        Route::get('{date}', 'Admin\MasterData\AppVersionController@show')->name('appVersion.read');
        Route::post('create', 'Admin\MasterData\AppVersionController@store')->name('appVersion.create');
        Route::put('update/{id}', 'Admin\MasterData\AppVersionController@update')->name('appVersion.update');
        Route::delete('delete/{id}', 'Admin\MasterData\AppVersionController@destroy')->name('appVersion.destroy');
    });

    Route::group([
        'prefix' => 'laporan',
    ], function () {
        Route::get('presensi-tahunan-user', 'Admin\Report\PresensiTahunanUserController@index')->name('report.presensiTahunanUser');
        Route::get('presensi-tahunan-user/list', 'Admin\Report\PresensiTahunanUserController@list')->name('report.presensiTahunanUser.list');
        Route::get('presensi-tahunan-user/download', 'Admin\Report\PresensiTahunanUserController@export')->name('report.presensiTahunanUser.download');

        Route::get('presensi-bulanan-user', 'Admin\Report\PresensiBulananUserController@index')->name('report.presensiBulananUser');
        Route::get('presensi-bulanan-user/list', 'Admin\Report\PresensiBulananUserController@list')->name('report.presensiBulananUser.list');
        Route::get('presensi-bulanan-user/download', 'Admin\Report\PresensiBulananUserController@export')->name('report.presensiBulananUser.download');

        Route::get('presensi-user', 'Admin\Report\PresensiUserController@index')->name('report.presensiUser');
        Route::get('presensi-user/list', 'Admin\Report\PresensiUserController@list')->name('report.presensiUser.list');
        Route::get('presensi-user/download', 'Admin\Report\PresensiUserController@export')->name('report.presensiUser.download');

        Route::get('cuti-user', 'Admin\Report\CutiUserController@index')->name('report.cutiUser');
        Route::get('cuti-user/list', 'Admin\Report\CutiUserController@list')->name('report.cutiUser.list');
        Route::get('cuti-user/download', 'Admin\Report\CutiUserController@export')->name('report.cutiUser.download');
    });
});
