<?php

Route::any('/', function() {
    return 'home';
    return redirect('http://passioncitychurch.com/students');
});

Route::any('test', function () {
    return App\Organization::with('contact')->get()->toJson();
    return view();
});

Route::group(['middleware' => 'web'], function () {
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    Route::get('register', 'RegisterController@create')->name('register.create');
    Route::post('register', 'RegisterController@store')->name('register.store');
    Route::get('register/confirmation', 'RegisterController@confirmation')->name('register.confirmation');

    Route::group(['middleware' => 'auth'], function () {
        Route::resource('admin/organization', 'OrganizationController');

        Route::get('admin/organization/{organization}/item/create', 'Organization\ItemController@create')->name('admin.organization.item.create');
        Route::post('admin/organization/{organization}/item/store', 'Organization\ItemController@store')->name('admin.organization.item.store');
        Route::get('admin/organization/{organization}/item/{item}/edit', 'Organization\ItemController@edit')->name('admin.organization.item.edit');
        Route::put('admin/organization/{organization}/item/{item}/update', 'Organization\ItemController@update')->name('admin.organization.item.update');

        Route::get('admin/organization/{organization}/payment/create', 'Organization\PaymentController@create')->name('admin.organization.payment.create');
        Route::post('admin/organization/{organization}/payment/store', 'Organization\PaymentController@store')->name('admin.organization.payment.store');

        Route::get('admin/organization/{organization}/user/create', 'Organization\UserController@create')->name('admin.organization.user.create');
        Route::post('admin/organization/{organization}/user/store', 'Organization\UserController@store')->name('admin.organization.user.store');

        Route::get('admin/organization/{organization}/attendees', 'Organization\TicketController@index')->name('admin.organization.ticket.index');

        Route::get('admin/organization/{organization}/registrations', 'Organization\OrderController@index')->name('admin.organization.order.index');

        Route::get('registrations', 'OrderController@index')->name('order.index');
        Route::get('registration/create', 'OrderController@create')->name('order.create');
        Route::post('registration/store', 'OrderController@store')->name('order.store');
        Route::get('registration/{order}', 'OrderController@show')->name('order.show');

        Route::get('registration/{order}/ticket/create', 'Order\TicketController@create')->name('order.ticket.create');
        Route::post('registration/{order}/ticket/store', 'Order\TicketController@store')->name('order.ticket.store');

        Route::get('attendees', 'TicketController@index')->name('ticket.index');

        Route::get('users', 'UserController@index')->name('user.index');
    });
});
