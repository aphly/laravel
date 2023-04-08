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

Route::prefix('center')->middleware(['web'])->group(function () {
    Route::get('seccode', 'Aphly\Laravel\Controllers\SeccodeController@index');
    Route::get('seccode/{code}', 'Aphly\Laravel\Controllers\SeccodeController@check');
    Route::get('banned', 'Aphly\Laravel\Controllers\BannedController@index')->name('banned');
});

Route::middleware(['web'])->group(function () {
    //oauth
    Route::get('oauth/{driver}/callback', 'Aphly\Laravel\Controllers\OAuthController@handleProviderCallback');
    Route::get('oauth/{driver}', 'Aphly\Laravel\Controllers\OAuthController@redirectToProvider');

});
