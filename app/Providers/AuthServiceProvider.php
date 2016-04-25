<?php

namespace App\Providers;

use App\User;
use App\Order;
use App\Ticket;
use App\OrderItem;
use App\Organization;
use App\Policies\UserPolicy;
use App\Policies\OrderPolicy;
use App\Policies\OrderItemPolicy;
use App\Policies\OrganizationPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Organization::class    => OrganizationPolicy::class,
        Order::class     => OrderPolicy::class,
        OrderItem::class => OrderItemPolicy::class,
        Ticket::class    => OrderItemPolicy::class,
        User::class    => UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('makeStripePayments', function (User $user, Organization $organization) {
            return (bool) $organization->setting('stripe_access_token');
        });
    }
}
