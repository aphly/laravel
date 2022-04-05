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
    Route::get('/index', 'Aphly\Laravel\Controllers\IndexController@index');

    Route::match(['get'],'/autologin/{token}', 'Aphly\Laravel\Controllers\IndexController@autoLogin');

    Route::middleware(['userAuth'])->group(function () {
        Route::match(['get', 'post'],'/register', 'Aphly\Laravel\Controllers\IndexController@register');
        Route::match(['get', 'post'],'/login', 'Aphly\Laravel\Controllers\IndexController@login');

        Route::get('/logout', 'Aphly\Laravel\Controllers\IndexController@logout');
    });

    Route::get('/home', function () {
        dd('ss');
    });
});
