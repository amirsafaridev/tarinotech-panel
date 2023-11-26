<?php

namespace Modules\Blog\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\BlogCategory\app\Models\BlogCategory;

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
        view()->composer(['blog::admin.create', 'blog::admin.edit', 'blog::admin.index'], function ($view) {
            $categories = BlogCategory::orderBy('title')
                ->get();
            $view->with('categories', $categories);
        });
    }
}
