<?php

namespace App\Providers;

use App\Services\PdfService;
use Illuminate\Support\ServiceProvider;

class PdfServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PdfService::class, function ($app) {
            return new PdfService();
        });
    }

    public function boot()
    {
        //
    }
}
