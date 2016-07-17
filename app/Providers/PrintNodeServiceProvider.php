<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\PrintNode\CheckinPrintNodeClient;
use App\PrintNode\RoominglistPrintNodeClient;

class PrintNodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(\App\Http\ViewComposers\CheckInPrinterIndexComposer::class)
                    ->needs(\PrintNode\Client::class)
                    ->give(function () {
                        return CheckinPrintNodeClient::init();
                    });

        $this->app->when(\App\Http\ViewComposers\RoominglistPrinterIndexComposer::class)
                    ->needs(\PrintNode\Client::class)
                    ->give(function () {
                        return RoominglistPrintNodeClient::init();
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
