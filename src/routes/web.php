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

//    Route::get('/index', 'Aphly\Laravel\Controllers\HomeController@index');
//    Route::match(['get'],'/autologin/{token}', 'Aphly\Laravel\Controllers\HomeController@autoLogin');

//    Route::get('/userauth/{id}/verify/{token}', 'Aphly\Laravel\Controllers\HomeController@mailVerifyCheck');
//
//    Route::match(['get', 'post'],'/forget', 'Aphly\Laravel\Controllers\HomeController@forget');
//    Route::match(['get', 'post'],'/forget-password/{token}', 'Aphly\Laravel\Controllers\HomeController@forgetPassword');
//
//    Route::middleware(['userAuth'])->group(function () {
//        Route::match(['get'],'/email/verify', 'Aphly\Laravel\Controllers\HomeController@mailVerify');
//
//        Route::match(['get', 'post'],'/register', 'Aphly\Laravel\Controllers\HomeController@register');
//        Route::match(['get', 'post'],'/login', 'Aphly\Laravel\Controllers\HomeController@login');
//
//        Route::get('/logout', 'Aphly\Laravel\Controllers\HomeController@logout');
//    });

});
