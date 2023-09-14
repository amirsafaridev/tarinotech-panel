<?php

namespace App\Providers;

use App;
use App\Channel\SmsChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Notification::resolved(function ($service) {
            $service->extend('sms', function ($app) {
                return new SmsChannel();
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));
        if (App::isProduction()) {
            URL::forceScheme('https');
        }
    }
}
