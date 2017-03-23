<?php

namespace App\Providers;

use App;
use App\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        App\Organization::class => Policies\OrganizationPolicy::class,
        App\Order::class        => Policies\OrderPolicy::class,
        App\OrderItem::class    => Policies\OrderItemPolicy::class,
        App\Ticket::class       => Policies\TicketPolicy::class,
        App\User::class         => Policies\UserPolicy::class,
        App\Room::class         => Policies\RoomPolicy::class,
        App\Person::class       => Policies\PersonPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('makeStripePayments', function (App\User $user, App\Organization $organization) {
            return (bool) $organization->setting('stripe_access_token');
        });
    }
}
