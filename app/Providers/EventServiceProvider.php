<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\OrderItems\OrgItemUpdated::class => [
            \App\Listeners\CreateRooms::class,
        ],
        \App\Events\Waivers\EmailBounced::class => [
            \App\Listeners\Waivers\EmailBounced\CancelSignatureRequest::class,
            \App\Listeners\Waivers\EmailBounced\CancelWaiverRecord::class,
            \App\Listeners\Waivers\EmailBounced\SendNotification::class,
        ],
        \App\Events\OrgItemSaved::class => [
            \App\Listeners\DispatchDeployRoomsForOrganization::class,
        ],
        \App\Events\RoomDeleted::class => [
            \App\Listeners\DetachRoomTickets::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
