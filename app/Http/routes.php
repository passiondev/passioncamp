<?php

Route::any('/', function() {
    return redirect('http://passioncitychurch.com/students');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    Route::get('register', 'RegisterController@create')->name('register.create');
    Route::post('register', 'RegisterController@store')->name('register.store');
    Route::get('register/confirmation', 'RegisterController@confirmation')->name('register.confirmation');

    Route::get('admin/organizations', 'Admin\OrganizationController@index')->name('admin.organization.index');
    Route::get('admin/organization/{organization}', 'Admin\OrganizationController@show')->name('admin.organization.show');
});
