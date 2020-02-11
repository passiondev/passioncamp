<?php

namespace App\Providers;

use App\Billing\PaymentGateway;
use App\Billing\StripePaymentGateway;
use App\Http\Controllers\RegisterController;
use HelloSign\Client as HelloSignClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Mandrill;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');

        $this->bootMacros();

        Schema::defaultStringLength(191);

        Horizon::auth(function ($request) {
            return $request->user() && 'matt.floyd@268generation.com' == $request->user()->email;
        });

        Collection::macro('sometimes', function ($condition, $method, ...$parameters) {
            return $condition ? \call_user_func_array([(new static($this->items)), $method], $parameters) : $this;
        });

        Request::macro('intended', function ($url) {
            if ($url != $this->fullUrl()) {
                $this->session()->put('url.intended', $url);
            }
        });

        Builder::macro('addSubSelect', function ($column, $query) {
            if (null === $this->getQuery()->columns) {
                $this->select($this->getQuery()->from.'.*');
            }

            return $this->selectSub($query->limit(1)->getQuery(), $column);
        });

        Builder::macro('orderBySub', function ($query, $direction = 'asc') {
            return $this->orderByRaw("({$query->limit(1)->toSql()}) {$direction}");
        });

        view()->composer(['ticket.partials.form', 'ticket.partials.form-horizontal'], function ($view) {
            $gradeOptions = [];

            foreach (range(6, 12) as $grade) {
                $gradeOptions[$grade] = number_ordinal($grade);
            }

            $view->with('gradeOptions', $gradeOptions);
        });

        view()->composer(['user.partials.form', 'user.edit', 'user.create'], function ($view) {
            $organizationOptions = [];

            \App\Organization::with('church')->each(function ($organization) use (&$organizationOptions) {
                $organizationOptions[$organization->church->id] = $organization->church->name.' - '.$organization->church->location;
            });

            $view->with('organizationOptions', collect($organizationOptions)->sort()->toArray());
        });

        view()->composer('checkin.printer.index', \App\Http\ViewComposers\CheckInPrinterIndexComposer::class);
        view()->composer('roominglist.printer.index', \App\Http\ViewComposers\RoominglistPrinterIndexComposer::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(Mandrill::class, function () {
            return new Mandrill(config('services.mandrill.key'));
        });

        $this->app->bind('stripe.conference', function () {
            return new StripePaymentGateway(config('settings.stripe.secret'));
        });

        $this->app->bind('stripe.pcc', function () {
            return new StripePaymentGateway(config('settings.stripe.pcc.secret'));
        });

        $this->app->bind(PaymentGateway::class, 'stripe.conference');
        $this->app->when(RegisterController::class)
            ->needs(PaymentGateway::class)
            ->give(function ($app) {
                return $app->make('stripe.pcc');
            });

        $this->app->singleton(HelloSignClient::class, function ($app) {
            $client = new HelloSignClient(config('passioncamp.hellosign_api_key'));

            // if ($app->isLocal()) {
            //     $client->enableDebugMode();
            // }

            return $client;
        });
    }

    private function bootMacros()
    {
        require base_path('resources/macros/blade.php');
    }
}
