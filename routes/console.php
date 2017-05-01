<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('passioncamp:deploy-rooms {organizationIds?*}', function ($organizationIds = []) {
    tap(
        empty($organizationIds) ? App\Organization::all() : App\Organization::whereIn('id', $organizationIds)->get(),
        function ($organizations) {
            $organizations->each(function ($organization) {
                dispatch(new App\Jobs\Organization\DeployRooms($organization));
            });
        }
    );
});

Artisan::command('pcc:balance-due', function () {
    $orders = App\Order::where('organization_id', 1)
        ->has('activeTickets')
        ->get()
        ->filter(function ($order) {
            return $order->balance > 0;
        });

    $userIds = $orders->pluck('user_id')->unique();
dd($userIds);
    $sent = $userIds->each(function ($userId) {
        dispatch(new App\Jobs\SendBalanceDueEmail(App\User::find($userId)));
    });

    $this->info('Orders: ' . $orders->count());
    $this->info('Users: ' . $userIds->count());
    $this->info('Sent: ' . $sent->count());
});
