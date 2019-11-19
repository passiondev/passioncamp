<?php

namespace App\Providers;

use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Telescope::night();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                if ('request' == $entry->type && starts_with(array_get($entry->content, 'uri'), '/_debugbar')) {
                    return false;
                }

                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return \in_array($user->email, [
                'matt.floyd@268generation.com',
            ], true);
        });
    }
}
