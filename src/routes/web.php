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

Route::middleware(['web'])->group(function () {
    Route::get('/seccode', 'Aphly\Laravel\Controllers\SeccodeController@index');
    Route::get('/seccode/{code}', 'Aphly\Laravel\Controllers\SeccodeController@check');

    Route::get('/userauth/{id}/verify/{token}', 'Aphly\Laravel\Controllers\IndexController@mailVerifyCheck');

    Route::match(['get', 'post'],'/forget', 'Aphly\Laravel\Controllers\IndexController@forget');
    Route::match(['get', 'post'],'/forget-password/{token}', 'Aphly\Laravel\Controllers\IndexController@forgetPassword');

    Route::get('/index', 'Aphly\Laravel\Controllers\IndexController@index');
    Route::match(['get'],'/autologin/{token}', 'Aphly\Laravel\Controllers\IndexController@autoLogin');

    Route::middleware(['userAuth'])->group(function () {
        Route::match(['get'],'/email/verify', 'Aphly\Laravel\Controllers\IndexController@mailVerify');

        Route::match(['get', 'post'],'/register', 'Aphly\Laravel\Controllers\IndexController@register');
        Route::match(['get', 'post'],'/login', 'Aphly\Laravel\Controllers\IndexController@login');

        Route::get('/logout', 'Aphly\Laravel\Controllers\IndexController@logout');
    });

});
