<?php

namespace App\Providers;

use App\Services\Printing\PrintManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Contracts\Printing\Factory as PrintingFactory;
use App\Contracts\Printing\Printer as PrinterContract;

class PrintNodeServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(PrintManager::class, function ($app) {
            return new PrintManager($app);
        });

        $this->app->singleton(PrinterContract::class, function ($app) {
            return $app->make(PrintManager::class)->driver();
        });

        $this->app->alias(
            PrintManager::class,
            PrintingFactory::class
        );
    }

    public function provides()
    {
        return [
            PrintManager::class,
            PrintingFactory::class,
            PrinterContract::class,
        ];
    }
}
