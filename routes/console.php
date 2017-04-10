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
