<?php

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

Auth::routes();

Route::prefix('oauth')->group(function () {
    Route::get('authorize', 'Auth\OauthController@auth');
    Route::post('login', 'Auth\OauthController@login')->name('oauth-login');
});

Route::get('/home', 'HomeController@index')->name('home');
