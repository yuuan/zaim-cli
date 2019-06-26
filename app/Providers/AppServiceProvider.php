<?php

namespace App\Providers;

use App\Zaim;
use App\Zaim\Client as ZaimClient;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHttpClient();
        $this->registerZaimHandlers();
    }

    /**
     * Register a HTTP client.
     *
     * @return void
     */
    private function registerHttpClient(): void
    {
        $this->app->bind(HttpClient::class, function ($app) {
            return new HttpClient;
        });
    }

    /**
     * Register Zaim handlers.
     *
     * @return void
     */
    private function registerZaimHandlers(): void
    {
        $this->app->bind(ZaimClient::class, function ($app) {
            return new ZaimClient($app->make(HttpClient::class));
        });

        $this->app->bind(Zaim::class, function ($app) {
            return new Zaim($app->make(ZaimClient::class));
        });
    }
}
