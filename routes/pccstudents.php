<?php

Route::redirect('/', '/dashboard');

Route::get('login', 'Auth\MagicLinkLoginController@show')->name('magic.login');
Route::post('login', 'Auth\MagicLinkLoginController@sendToken');
Route::get('login/{token}', 'Auth\MagicLinkLoginController@authenticate')->name('magic.authenticate');

Route::get('register', 'RegisterController@create')->name('register.create');
Route::post('register', 'RegisterController@store')->name('register.store');
Route::get('register/confirmation', 'RegisterController@confirmation')->name('register.confirmation');

Route::namespace('User')->as('user.')->group(function () {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::resource('payments', 'PaymentController')->only('index', 'store');
});

Route::get('profile', 'ProfileController@show')->name('profile.show');
Route::patch('profile', 'ProfileController@update')->name('profile.update');
