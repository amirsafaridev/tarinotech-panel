<?php

namespace Modules\Package\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Project\app\Models\ProjectType;

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
        view()->composer([
            'package::admin.create',
            'package::admin.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->with('base')
                ->get();

            $view->with(compact('types'));
        });
    }
}
