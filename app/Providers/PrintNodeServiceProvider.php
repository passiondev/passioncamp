<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PrintNodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(\PrintNode\Client::class)
            ->needs(\PrintNode\Credentials::class)
            ->give(function () {
                return new \PrintNode\Credentials\ApiKey(config('services.printnode.key'));
            });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
