<?php

namespace Kaylyu\Wechatpay\ApiV3;

use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-wechatpay-api-v3');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-wechatpay-api-v3.php', 'laravel-wechatpay-api-v3'
        );

        $this->app->singleton('wechatpay.api.v3', function ($app) {
            return new Application(
                $app['config']['laravel-wechatpay-api-v3']
            );
        });
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}