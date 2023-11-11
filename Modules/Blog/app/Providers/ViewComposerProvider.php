<?php

namespace Modules\Blog\app\Providers;

use App\Models\BlogCategory;
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
        view()->composer(['blog::admin.create', 'blog::admin.edit'], function ($view) {
            $categories = BlogCategory::orderBy('title')
                ->get();
            $view->with('categories', $categories);
        });
    }
}
