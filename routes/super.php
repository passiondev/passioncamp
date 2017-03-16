<?php

Route::resource('organizations', 'OrganizationController');

Route::post('organizations/{organization}/note', 'Organization\NoteController@store');

Route::get('organizations/{organization}/item/create', 'OrganizationItemController@create');
Route::post('organizations/{organization}/item/store', 'OrganizationItemController@store');
Route::get('organizations/{organization}/item/{item}/edit', 'OrganizationItemController@edit');
Route::put('organizations/{organization}/item/{item}/update', 'OrganizationItemController@update');

Route::get('organizations/{organization}/payment/create', 'OrganizationPaymentController@create');
Route::post('organizations/{organization}/payment/store', 'OrganizationPaymentController@store');

Route::get('organizations/{organization}/user/create', 'Organization\UserController@create');
Route::post('organizations/{organization}/user/store', 'Organization\UserController@store');

Route::get('organizations/{organization}/attendees', 'Organization\TicketController@index');

Route::get('organizations/{organization}/registrations', 'Organization\OrderController@index');

Route::get('organizations/{organization}/rooms/checkin', 'Organization\RoomController@checkInAll');
Route::middleware('printer')->get('organizations/{organization}/rooms/print', 'Organization\RoomController@printAll');

Route::get('hotels', 'HotelsController@index');
Route::get('hotels/{hotel}', 'HotelsController@show');

Route::get('roominglist/issues', 'RoomingListController@issues');
