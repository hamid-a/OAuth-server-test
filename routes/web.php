<?php

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::prefix('oauth')->group(function () {
    Route::get('authorize', 'Auth\OauthController@auth');
    Route::post('login', 'Auth\OauthController@login')->name('oauth-login');
});
