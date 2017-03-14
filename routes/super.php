<?php

Route::resource('organizations', 'OrganizationController');

Route::post('organizations/{organization}/note', 'Organization\NoteController@store');

Route::get('organizations/{organization}/item/create', 'Organization\ItemController@create');
Route::post('organizations/{organization}/item/store', 'Organization\ItemController@store');
Route::get('organizations/{organization}/item/{item}/edit', 'Organization\ItemController@edit');
Route::put('organizations/{organization}/item/{item}/update', 'Organization\ItemController@update');

Route::get('organizations/{organization}/payment/create', 'Organization\PaymentController@create');
Route::post('organizations/{organization}/payment/store', 'Organization\PaymentController@store');

Route::get('organizations/{organization}/user/create', 'Organization\UserController@create');
Route::post('organizations/{organization}/user/store', 'Organization\UserController@store');

Route::get('organizations/{organization}/attendees', 'Organization\TicketController@index');

Route::get('organizations/{organization}/registrations', 'Organization\OrderController@index');

Route::get('organizations/{organization}/rooms/checkin', 'Organization\RoomController@checkInAll');
Route::middleware('printer')->get('organizations/{organization}/rooms/print', 'Organization\RoomController@printAll');

Route::get('hotels', 'HotelController@index');
Route::get('hotels/{hotel}', 'HotelController@show');

Route::get('roominglist/issues', 'RoomingListController@issues');
