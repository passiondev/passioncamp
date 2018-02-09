<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('currency', function ($expression) {
            return "<?php echo money_format('%.2n', $expression); ?>";
        });
        Blade::directive('daydatetime', function ($expression) {
            return "<?php echo with($expression)->toDayDateTimeString(); ?>";
        });
        Blade::directive('ordinal', function ($expression) {
            return "<?php echo number_ordinal($expression); ?>";
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
