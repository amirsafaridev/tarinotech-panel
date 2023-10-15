<?php

namespace App\Providers;

use App\Http\ViewComposers\Admin\Project\SeoProjectComposer;
use App\Http\ViewComposers\Admin\Project\WebProjectComposer;
use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['admin.project.web.*'], WebProjectComposer::class);
        view()->composer(['admin.project.seo.*'], SeoProjectComposer::class);
    }
}
