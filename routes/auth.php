<?php

Route::get('account', 'AccountController@index');
Route::get('account/dashboard', 'Account\DashboardController@index');
// Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
//     Route::get('settings', 'Account\SettingsController@index');
// });

Route::get('profile', 'ProfileController@show');
Route::patch('profile', 'ProfileController@update');

Route::get('users', 'UserController@index');
Route::get('users/create', 'UserController@create');
Route::post('users', 'UserController@store');
Route::get('users/{user}/edit', 'UserController@edit');
Route::put('users/{user}/update', 'UserController@update');
// Route::post('user/{user}/password/reset', 'User\PasswordController@reset');
Route::get('impersonate/{user}', 'ImpersonationController@impersonate');
Route::get('stop-impersonating', 'ImpersonationController@stopImpersonating');



// Route::get('roominglist', 'RoomingListController@index');
// Route::get('roominglist/{room}', 'RoomingListController@show');
// Route::get('roominglist/{room}/edit', 'RoomingListController@edit');
// Route::patch('roominglist/{room}', 'RoomingListController@update');
// Route::put('roominglist/{ticket}/assign/{room}', 'RoomingListController@assign');
// Route::put('roominglist/{ticket}/unassign', 'RoomingListController@unassign');
// Route::get('roominglist/overview', 'RoomingListController@overview');
// Route::delete('roominglist/{room}', 'RoomingListController@destroy');
// Route::get('roominglist/{room}/label', 'RoomingListController@label')->middleware('printer:roominglist');
// Route::get('roominglist/{room}/checkin', 'RoomingListController@checkin');
// Route::get('roominglist/{room}/key_received', 'RoomingListController@keyReceived');
// Route::get('roominglist/export', 'RoomingList\ExportController@index');
// Route::post('roominglist/export', 'RoomingList\ExportController@version');
// Route::get('roominglist/export/{version}/download', 'RoomingList\ExportController@download');
// Route::get('roominglist/export/generate', 'RoomingList\ExportController@generate');
// Route::get('roominglist/printers', 'RoominglistPrinterController@index');
// Route::post('roominglist/printer/{printer}/select', 'RoominglistPrinterController@select');
// Route::get('roominglist/printer/reset', 'RoominglistPrinterController@reset');
// Route::get('roominglist/printer/{printer}/test', 'RoominglistPrinterController@test');


// Route::get('registrations', 'OrderController@index');
// Route::get('registrations/create', 'OrderController@create');
// Route::post('registrations/store', 'OrderController@store');
// Route::get('registrations/{order}', 'OrderController@show');

// Route::post('registrations/{order}/note', 'Order\NoteController@store');

// Route::get('registration/{order}/contact/create', 'Order\ContactController@create');
// Route::post('registration/{order}/contact', 'Order\ContactController@store');
// Route::get('registration/{order}/contact/edit', 'Order\ContactController@edit');
// Route::patch('registration/{order}/contact', 'Order\ContactController@update');

// Route::get('registration/{order}/ticket/create', 'Order\TicketController@create');
// Route::post('registration/{order}/ticket', 'Order\TicketController@store');

// Route::get('registration/{order}/transaction/create', 'Order\TransactionController@create');
// Route::post('registration/{order}/transaction', 'Order\TransactionController@store');

// Route::get('registration/{order}/payment/create', 'Order\PaymentController@create');
// Route::post('registration/{order}/payment', 'Order\PaymentController@store');

Route::get('tickets', 'TicketsController@index');
// Route::get('tickets/export', 'Ticket\ExportController@index');
// Route::get('ticket/{ticket}', 'TicketController@show');
// Route::get('ticket/{ticket}/edit', 'TicketController@edit');
// Route::patch('ticket/{ticket}', 'TicketController@update');
// Route::delete('ticket/{ticket}', 'TicketController@delete');
// Route::patch('ticket/{ticket}/cancel', 'TicketController@cancel');

// Route::get('ticket/{ticket}/waiver/create', 'Ticket\WaiverController@create');
// Route::get('ticket/{ticket}/waiver/reminder', 'Ticket\WaiverController@reminder');
// Route::get('ticket/{ticket}/waiver/cancel', 'Ticket\WaiverController@cancel');
// Route::get('ticket/{ticket}/waiver/complete', 'Ticket\WaiverController@complete');

// // Route::any('stripe/connect', 'StripeController@connect');

// Route::get('payment/create', 'Organization\PaymentController@create');
// Route::post('payment', 'Organization\PaymentController@store');

// Route::get('transaction/{transaction}/edit', 'TransactionController@edit');
// Route::get('transaction/{transaction}/refund', 'TransactionController@refund');
// Route::post('transaction/{transaction}/refund', 'TransactionController@storeRefund');
// Route::patch('transaction/{transaction}', 'TransactionController@update');
// Route::delete('transaction/{transaction}', 'TransactionController@delete');

// Route::get('checkin/printers', 'CheckinPrinterController@index');
// Route::post('checkin/printer/{printer}/select', 'CheckinPrinterController@select');
// Route::get('checkin/printer/{printer}/test', 'CheckinPrinterController@test');
// Route::get('checkin/printer/reset', 'CheckinPrinterController@reset');

// Route::get('checkin', 'CheckinController@index');
// Route::any('checkin/{ticket}', 'CheckinController@doCheckin');
// Route::any('checkin/{ticket}/undo', 'CheckinController@undoCheckin');

Route::get('/', function () {
    // if (Auth::user()->isOrderOwner()) {
    //     return redirect()->route('account');
    // }

    // if (Auth::user()->isChurchAdmin()) {
    //     return redirect()->route('account.dashboard');
    // }

    return redirect()->action('OrganizationController@index');
});
