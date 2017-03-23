<?php

Route::get('/', function () {
    if (Auth::user()->isSuperAdmin()) {
        return redirect()->action('Super\DashboardController');
    }

    return redirect()->action('Account\DashboardController');
})->middleware('auth');

Route::get('admin', 'Super\DashboardController');

Route::resource('admin/organizations', 'Super\OrganizationController');

Route::get('admin/hotels', 'Super\HotelController@index');
Route::get('admin/hotels/{hotel}', 'Super\HotelController@show');

Route::get('admin/tickets', 'Super\TicketController@index');

Route::get('admin/users', 'Super\UserController@index');
Route::get('admin/users/create', 'Super\UserController@create');
Route::post('admin/users', 'Super\UserController@store');
Route::get('admin/users/{user}/edit', 'Super\UserController@edit');
Route::put('admin/users/{user}/update', 'Super\UserController@update');
// Route::post('user/{user}/password/reset', 'User\PasswordController@reset');

Route::get('admin/organizations/{organization}/item/create', 'Super\OrganizationItemController@create');
Route::post('admin/organizations/{organization}/item/store', 'Super\OrganizationItemController@store');
Route::get('admin/organizations/{organization}/item/{item}/edit', 'Super\OrganizationItemController@edit');
Route::put('admin/organizations/{organization}/item/{item}/update', 'Super\OrganizationItemController@update');

Route::get('admin/organizations/{organization}/users/create', 'Super\OrganizationUserController@create');
Route::post('admin/organizations/{organization}/users', 'Super\OrganizationUserController@store');

Route::get('admin/organizations/{organization}/payments', 'Super\OrganizationPaymentController@index');
Route::post('admin/organizations/{organization}/payments/store', 'Super\OrganizationPaymentController@store');


Route::get('account/dashboard', 'Account\DashboardController');

Route::get('account/payments', 'Account\PaymentController@index');
Route::post('account/payments', 'Account\PaymentController@store');

Route::get('account/settings', 'Account\SettingsController@index');

Route::get('account/users/create', 'Account\UserController@create');
Route::post('account/users', 'Account\UserController@store');

Route::get('orders', 'OrderController@index');
Route::get('orders/{order}', 'OrderController@show');

Route::get('orders/{order}/tickets/create', 'OrderTicketController@create');
Route::post('orders/{order}/tickets', 'OrderTicketController@store');

Route::get('orders/{order}/transactions/create', 'OrderTransactionController@create');
Route::post('orders/{order}/transactions', 'OrderTransactionController@store');

Route::post('orders/{order}/notes', 'OrderNoteController@store');

Route::get('tickets', 'TicketController@index');
Route::get('tickets/create', 'Account\TicketController@create');
Route::post('tickets', 'Account\TicketController@store');
// Route::get('tickets/{ticket}', 'TicketController@show');
Route::get('tickets/{ticket}/edit', 'TicketController@edit');
Route::patch('tickets/{ticket}', 'TicketController@update');
Route::delete('tickets/{ticket}', 'TicketController@delete');
Route::patch('tickets/{ticket}/cancel', 'TicketController@cancel');

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

Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register/{user}/{hash}', 'Auth\RegisterController@showRegistrationForm')->name('complete.registration');
Route::post('register/{user}/{hash}', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('impersonate/{user}', 'Auth\ImpersonationController@impersonate');
Route::get('stop-impersonating', 'Auth\ImpersonationController@stopImpersonating');
