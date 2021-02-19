<?php

namespace NotificationChannels\jsksms;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class JsksmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(JsksmsApi::class, static function ($app) {
            return new JsksmsApi(new HttpClient());
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('jsksms', function ($app) {
                return new JsksmsChannel($app[JsksmsApi::class], $this->app['config']['services.jsksms']);
            });
        });
    }
}
