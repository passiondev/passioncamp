<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\OrderItems\OrgItemUpdated' => [
            'App\Listeners\CreateRooms',
        ],
        'App\Events\Waivers\EmailBounced' => [
            'App\Listeners\Waivers\EmailBounced\CancelSignatureRequest',
            'App\Listeners\Waivers\EmailBounced\CancelWaiverRecord',
            'App\Listeners\Waivers\EmailBounced\SendNotification',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\User\SometimesUpdateEmail',
            'App\Listeners\User\SometimesUpdateAccess',
            'App\Listeners\User\SendRegistrationEmail',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
