<?php

namespace App\Providers;

use AsiaYo\adapters\mock\ExchangeRateDao;
use AsiaYo\ports\GetExchangeRate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(GetExchangeRate::class, fn (Application $app) => new ExchangeRateDao);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
