<?php

namespace Modules\Content\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\app\Models\BlogCategory;

use function view;

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
        view()->composer(['content::admin.blog.create', 'content::admin.blog.edit', 'content::admin.blog.index'], function ($view) {
            $categories = BlogCategory::query()
                ->orderBy('title')
                ->get();
            $view->with('categories', $categories);
        });
    }
}
