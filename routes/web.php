<?php

use App\Mail\AccountUserCreated;

Route::get('test', function () {
    return new AccountUserCreated(App\User::find(358));
});

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register/{user}/{hash?}', 'Auth\RegisterController@showRegistrationForm')->name('complete.registration');
Route::get('register/{user}', 'Auth\RegisterController@showRegistrationForm')->name('auth.register.create');
Route::post('register/{user}', 'Auth\RegisterController@register')->name('auth.register.store');
Route::post('register/{provider}/{user}/{hash}', 'Auth\RegisterController@registerWithSocial');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::any('webhooks/adobesign', 'Webhooks\AdobeSignController');

Route::get('/', 'RedirectController@home');

Route::namespace('User')->as('user.')->group(function () {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::resource('payments', 'PaymentController')->only('index', 'store');
});

Route::prefix('account')->namespace('Account')->as('account.')->group(function () {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::get('settings', 'SettingsController')->name('settings');
    Route::resource('payments', 'PaymentController')->only('index', 'store');
    Route::resource('users', 'UserController')->only('create', 'store', 'destroy');
    Route::resource('tickets', 'TicketController')->only('create', 'store');
});

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('adobesign', 'Super\AdobeSignController')->middleware(['auth', 'super']);

    Route::get('/', 'Super\DashboardController')->middleware(['auth', 'super']);

    Route::get('roominglists', 'Super\RoominglistsController@index');
    Route::post('roominglists/export', 'RoominglistExportController@create');
    Route::get('roominglists/{version}/download', 'RoominglistExportController@download');

    Route::resource('organizations', 'OrganizationController');
    Route::get('organizations/search', 'OrganizationController@search');
    Route::resource('organizations.users', 'OrganizationUserController')->only('create', 'store', 'destroy');
    Route::resource('organizations.items', 'OrganizationItemController')->only('create', 'store', 'edit', 'update', 'destroy');
    Route::resource('organizations.payments', 'OrganizationPaymentController')->only('index', 'store', 'edit', 'update', 'destroy');

    Route::resource('hotels', 'HotelController')->only('index', 'show');
    Route::resource('tickets', 'Super\TicketController')->only('index');
    Route::resource('users', 'Super\UserController')->only('index', 'create', 'store', 'edit', 'update');
    Route::get('rooms', 'RoomController@index')->name('rooms.index');
});

if (config('passioncamp.enable_rooms')) {
    Route::get('roominglist', 'RoomingListController@index')->name('roominglist.index');

    Route::resource('rooms', 'RoomController')->only('edit', 'update');

    Route::post('rooms/{room}/assignments', 'RoomAssignmentController@store')->name('rooms.assignments.store');
    Route::delete('tickets/{ticket}/assignments', 'TicketAssignmentController@destroy');

    Route::post('rooms/{room}/check-in', 'RoomController@checkin');
    Route::post('rooms/{room}/key-received', 'RoomController@keyReceived');

    Route::get('rooms/{room}/label', 'RoomLabelController@show')->name('room.label.show');
    Route::post('rooms/{room}/print-label', 'RoomLabelController@printnode');
    Route::post('rooms/print-all', 'RoomLabelController@printAll');
}

Route::resource('orders', 'OrderController')->only('show');
Route::post('orders/exports', 'OrderExportController@store')->name('orders.exports.store');
Route::resource('orders.tickets', 'OrderTicketController')->only('create', 'store');
Route::resource('orders.transactions', 'OrderTransactionController')->only('create', 'store');
Route::post('orders/{order}/notes', 'OrderNoteController@store');

Route::resource('tickets', 'TicketController')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
Route::get('tickets/search', 'TicketController@search')->name('tickets.search');
Route::match(['put', 'patch'], 'tickets/{ticket}/cancel', 'TicketController@cancel')->name('tickets.cancel');
Route::post('tickets/export', 'TicketExportController@store');
Route::post('tickets/{ticket}/waivers', 'TicketWaiverController@store')->name('tickets.waivers.store');
Route::get('tickets/{ticket}/wristband', 'TicketWristbandsController@show')->name('tickets.wristband.show');

Route::get('transactions/{split}/refund', 'TransactionRefundController@create')->name('transactions.refunds.create');
Route::post('transactions/{split}/refund', 'TransactionRefundController@store')->name('transactions.refunds.store');
Route::get('transactions/{split}/edit', 'TransactionController@edit')->name('transactions.edit');
Route::patch('transactions/{split}', 'TransactionController@update')->name('transactions.update');
Route::delete('transactions/{split}', 'TransactionController@destroy')->name('transactions.delete');

Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
Route::patch('users/{user}', 'UserController@update')->name('users.update');

Route::get('person/{person}/edit', 'PersonController@edit')->name('people.edit');
Route::patch('person/{person}', 'PersonController@update')->name('people.update');

Route::post('organization/{organization}/notes', 'OrganizationNoteController@store')->name('organizations.notes.store');

Route::get('profile', 'ProfileController@show')->name('profile.show');
Route::patch('profile', 'ProfileController@update')->name('profile.update');

Route::get('impersonate/{user}', 'Auth\ImpersonationController@impersonate');
Route::get('stop-impersonating', 'Auth\ImpersonationController@stopImpersonating');

if (config('passioncamp.enable_waivers')) {
    Route::resource('waivers', 'WaiverController')->only('index', 'destroy');
    Route::post('waivers/{waiver}/reminder', 'WaiverController@reminder');
    Route::post('waivers/{waiver}/refresh', 'WaiverController@refresh');
}

Route::get('ticket-items', 'TicketItemController@index')->name('ticket-items.index');

Route::get('printers', 'PrinterController@index')->name('printers.index');
Route::delete('printers', 'PrinterController@refresh')->name('printers.refresh');
Route::post('printers/{printer}/test', 'PrinterController@test')->name('printers.test');
Route::post('selected-printer', 'SelectedPrinterController@store')->name('selected-printer.store');
Route::delete('selected-printer', 'SelectedPrinterController@destroy')->name('selected-printer.destroy');

Route::get('checkin', 'CheckinController@index');
Route::get('checkin/all-leaders', 'CheckinController@allLeaders');
Route::post('checkin/{ticket}', 'CheckinController@create');
Route::delete('checkin/{ticket}', 'CheckinController@destroy');
