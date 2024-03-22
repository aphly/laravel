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

Route::prefix('comm')->middleware(['cross'])->group(function () {
    Route::get('module', 'Aphly\Laravel\Controllers\Front\CommController@module');
    Route::get('module/import', 'Aphly\Laravel\Controllers\Front\CommController@moduleImport');
    Route::get('module/install', 'Aphly\Laravel\Controllers\Front\CommController@moduleInstall');
    Route::get('module/uninstall', 'Aphly\Laravel\Controllers\Front\CommController@moduleUninstall');
});

Route::middleware(['web'])->group(function () {

    Route::prefix('center')->group(function () {
        Route::get('seccode', 'Aphly\Laravel\Controllers\Front\SeccodeController@index');
        Route::get('seccode/{code}', 'Aphly\Laravel\Controllers\Front\SeccodeController@check');
    });

    //404
    Route::get('404', 'Aphly\Laravel\Controllers\Front\NotfoundController@index');

    //oauth
//    Route::get('oauth/{driver}/callback', 'Aphly\Laravel\Controllers\OAuthController@handleProviderCallback');
//    Route::get('oauth/{driver}', 'Aphly\Laravel\Controllers\OAuthController@redirectToProvider');

    Route::get('upload_file/download', 'Aphly\Laravel\Controllers\Admin\UploadFileController@download');

    Route::prefix('account')->group(function () {
        Route::match(['get'],'autologin/{token}', 'Aphly\Laravel\Controllers\Front\AccountController@autoLogin');

        Route::match(['get'],'blocked', 'Aphly\Laravel\Controllers\Front\AccountController@blocked')->name('blocked');
        Route::match(['get'],'email-verify', 'Aphly\Laravel\Controllers\Front\AccountController@emailVerify')->name('emailVerify');
        Route::match(['get'],'email-verify/send', 'Aphly\Laravel\Controllers\Front\AccountController@emailVerifySend');
        Route::get('email-verify/{token}', 'Aphly\Laravel\Controllers\Front\AccountController@emailVerifyCheck');

        Route::match(['get', 'post'],'forget', 'Aphly\Laravel\Controllers\Front\AccountController@forget');
        Route::match(['get'],'forget/confirmation', 'Aphly\Laravel\Controllers\Front\AccountController@forgetConfirmation');
        Route::match(['get', 'post'],'forget-password/{token}', 'Aphly\Laravel\Controllers\Front\AccountController@forgetPassword');

        Route::get('logout', 'Aphly\Laravel\Controllers\Front\AccountController@logout');

        Route::middleware(['userAuth'])->group(function () {
            Route::match(['get', 'post'],'register', 'Aphly\Laravel\Controllers\Front\AccountController@register')->name('register');
            Route::match(['get', 'post'],'login', 'Aphly\Laravel\Controllers\Front\AccountController@login')->name('login');
            Route::match(['get', 'post'],'index', 'Aphly\Laravel\Controllers\Front\AccountController@index');
        });
    });


    Route::prefix(config('base.admin'))->group(function () {
        Route::get('blocked', 'Aphly\Laravel\Controllers\Admin\LoginController@blocked')->name('adminBlocked');
        Route::get('not_active', 'Aphly\Laravel\Controllers\Admin\LoginController@notActive')->name('adminNotActive');
        Route::middleware(['managerAuth'])->group(function () {
            Route::middleware(['rbac'])->group(function () {
                Route::get('cache', 'Aphly\Laravel\Controllers\Admin\HomeController@cache')->name('adminCache');
            });
            Route::match(['get', 'post'], '/login', 'Aphly\Laravel\Controllers\Admin\LoginController@index')->name('adminLogin');
            Route::get('logout', 'Aphly\Laravel\Controllers\Admin\LoginController@logout')->name('adminLogout');
            Route::get('role', 'Aphly\Laravel\Controllers\Admin\LoginController@role')->name('adminRole');
            Route::get('choose_role', 'Aphly\Laravel\Controllers\Admin\LoginController@chooseRole')->name('adminChooseRole');

            Route::get('index', 'Aphly\Laravel\Controllers\Admin\HomeController@layout')->name('adminIndex');
        });
    });
});
