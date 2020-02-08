<?php

namespace App\Providers;

use App;
use App\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Notifications\DatabaseNotification;
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
        App\Order::class => Policies\OrderPolicy::class,
        App\OrderItem::class => Policies\OrderItemPolicy::class,
        App\Ticket::class => Policies\TicketPolicy::class,
        App\User::class => Policies\UserPolicy::class,
        App\Room::class => Policies\RoomPolicy::class,
        App\Person::class => Policies\PersonPolicy::class,
        App\Waiver::class => Policies\WaiverPolicy::class,
        App\AccountUser::class => Policies\AccountUserPolicy::class,
        App\OrgItem::class => Policies\OrgItemPolicy::class,
        App\TransactionSplit::class => Policies\TransactionSplitPolicy::class,
        DatabaseNotification::class => Policies\DatabaseNotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('super', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('makeStripePayments', function (App\User $user, App\Organization $organization) {
            return (bool) $organization->setting('stripe_access_token');
        });

        Gate::define('print', function ($user) {
            return $user->isSuperAdmin() || 'pcc' == data_get($user, 'organization.slug');
        });

        Gate::define('can-see-money', function ($user) {
            if (\in_array($user->email, [
                'students@passioncitychurch.com',
                'passioncamp@268generation.com',
            ], true)) {
                return false;
            }

            return true;
        });
    }
}
