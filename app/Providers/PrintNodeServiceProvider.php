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
        // $this->app->singleton(\PrintNode\Client::class, function ($app) {
        //     $credentials = new \PrintNode\Credentials\ApiKey(config('services.printnode.key'));

        //     return new \PrintNode\Client($credentials);
        // });
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
