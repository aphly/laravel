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
Route::middleware(['throttle:5,10'])->match(['get', 'post'],'/admin/test', 'Aphly\Laravel\Controllers\IndexController@test');

//Route::get('/admin/init', 'Aphly\Laravel\Controllers\InitController@index');

Route::middleware(['web'])->group(function () {

    Route::match(['get', 'post'],'/register', 'Aphly\Laravel\Controllers\IndexController@register');

    Route::middleware(['userAuth'])->group(function () {
        Route::match(['get', 'post'],'/login', 'Aphly\Laravel\Controllers\IndexController@login');
        Route::get('/index', 'Aphly\Laravel\Controllers\IndexController@layout');
        Route::get('/logout', 'Aphly\Laravel\Controllers\IndexController@logout');


    });

});
