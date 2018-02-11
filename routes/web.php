<?php

Route::get('/', 'RedirectController@home');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register/{user}/{hash}', 'Auth\RegisterController@showRegistrationForm')->name('complete.registration');
Route::post('register/{user}/{hash}', 'Auth\RegisterController@register');
Route::post('register/{provider}/{user}/{hash}', 'Auth\RegisterController@registerWithSocial');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/', 'Super\DashboardController')->middleware(['auth', 'super']);

    Route::get('roominglists', 'Super\RoominglistsController@index');
    Route::post('roominglists/export', 'RoominglistExportController@create');
    Route::get('roominglists/{version}/download', 'RoominglistExportController@download');

    Route::resource('organizations', 'OrganizationController');
    Route::get('organizations/search', 'OrganizationController@search');
    Route::resource('organizations.users', 'OrganizationUserController')->only('create', 'store');
    Route::resource('organizations.items', 'OrganizationItemController')->only('create', 'store', 'edit', 'update');
    Route::resource('organizations.payments', 'OrganizationPaymentController')->only('index', 'store');

    Route::resource('hotels', 'HotelController')->only('index', 'show');
    Route::resource('tickets', 'Super\TicketController')->only('index');
    Route::resource('users', 'Super\UserController')->only('index', 'create', 'store', 'edit', 'update');
    Route::get('rooms', 'RoomController@index')->name('rooms.index');
});

Route::prefix('account')->as('account.')->group(function () {
    Route::get('dashboard', 'Account\DashboardController')->name('dashboard');
    Route::get('settings', 'Account\SettingsController')->name('settings');
    Route::resource('payments', 'Account\PaymentController')->only('index', 'store');
    Route::resource('users', 'Account\UserController')->only('create', 'store');
});

Route::get('roominglist', 'RoomingListController@index');

Route::resource('rooms', 'RoomController')->only('edit', 'update');
Route::resource('rooms.assignments', 'RoomAssignmentController')->only('store', 'update', 'delete');

Route::post('rooms/{room}/check-in', 'RoomController@checkin');
Route::post('rooms/{room}/key-received', 'RoomController@keyReceived');

// Route::get('rooms/{room}/label', 'RoomLabelController@show');
Route::post('rooms/{room}/print-label', 'RoomLabelController@printnode');
Route::get('rooms/{payload}/label', 'RoomLabelController@signedShow');

Route::resource('orders', 'OrderController')->only('index', 'show');
Route::post('orders/exports', 'OrderExportController@store')->name('orders.exports.store');
Route::resource('orders.tickets', 'OrderTicketController')->only('create', 'store');
Route::resource('orders.transactions', 'OrderTransactionController')->only('create', 'store');
Route::post('orders/{order}/notes', 'OrderNoteController@store');

Route::resource('tickets', 'TicketController')->only('index', 'create', 'store', 'edit', 'update', 'delete');
Route::get('tickets/search', 'TicketController@search');
Route::match(['put', 'patch'], 'tickets/{ticket}/cancel', 'TicketController@cancel');
Route::post('tickets/export', 'TicketExportController@store');
Route::post('tickets/{ticket}/waivers', 'TicketWaiverController@store');

Route::get('transactions/{split}/refund', 'TransactionRefundController@create');
Route::post('transactions/{split}/refund', 'TransactionRefundController@store');
Route::get('transactions/{split}/edit', 'TransactionController@edit');
Route::patch('transactions/{split}', 'TransactionController@update');
Route::delete('transactions/{split}', 'TransactionController@delete');

Route::get('users/{user}/edit', 'UserController@edit');
Route::patch('users/{user}', 'UserController@update');

Route::get('person/{person}/edit', 'PersonController@edit');
Route::patch('person/{person}', 'PersonController@update');

Route::post('organization/{organization}/notes', 'OrganizationNoteController@store');

Route::get('profile', 'ProfileController@show');
Route::patch('profile', 'ProfileController@update');
Route::delete('profile/oauth/{provider}', 'SocialAuthController@disconnect');

Route::get('impersonate/{user}', 'Auth\ImpersonationController@impersonate');
Route::get('stop-impersonating', 'Auth\ImpersonationController@stopImpersonating');

Route::get('oauth/{provider}/callback', 'SocialAuthController@callback');
Route::post('oauth/{provider}', 'SocialAuthController@redirect');

Route::get('waivers', 'WaiverController@index');
Route::post('waivers/{waiver}/reminder', 'WaiverController@reminder');
Route::post('waivers/{waiver}/refresh', 'WaiverController@refresh');
Route::delete('waivers/{waiver}', 'WaiverController@destroy');

Route::any('webhooks/adobesign', 'Webhooks\AdobeSignController');

Route::get('dashboard', 'User\DashboardController');
Route::get('payments', 'User\PaymentsController@index');
Route::post('payments', 'User\PaymentsController@store');

Route::get('ticket-items', 'TicketItemsController@index');

Route::resource('printers', 'PrinterController')->only('index', 'destroy');
Route::post('printers/{printer}/test', 'PrinterController@test')->name('printers.test');
Route::post('selected-printer', 'PrinterSelectionController@store');
Route::delete('selected-printer', 'PrinterSelectionController@destroy');

Route::get('checkin', 'CheckinController@index');
Route::get('checkin/all-leaders', 'CheckinController@allLeaders');
Route::post('checkin/{ticket}', 'CheckinController@create');
Route::delete('checkin/{ticket}', 'CheckinController@destroy');

// Route::get('tickets/{ticket}/wristband', 'TicketWristbandsController@show');
Route::get('tickets/{payload}/wristband', 'TicketWristbandsController@signedShow');
