<?php

namespace App\Providers;

use App;
use App\Channel\SmsChannel;
use Illuminate\Pagination\Paginator;
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
        if (App::isProduction()) {
            URL::forceRootUrl('https://portal.tarinotech.com');
            URL::forceScheme('https');

            $_SERVER['SERVER_NAME'] = 'portal.tarinotech.com';
            $_SERVER['HTTP_HOST'] = 'portal.tarinotech.com';

        }
        Paginator::useBootstrapFour();
    }
}
