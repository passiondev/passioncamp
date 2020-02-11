<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
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
                if ('request' == $entry->type && Str::startsWith(Arr::get($entry->content, 'uri'), '/_debugbar')) {
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
