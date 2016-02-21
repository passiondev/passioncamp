<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('register', 'RegisterController@create')->name('register.create');
    Route::post('register', 'RegisterController@store')->name('register.store');
    Route::get('register/confirmation', 'RegisterController@confirmation')->name('register.confirmation');
});
