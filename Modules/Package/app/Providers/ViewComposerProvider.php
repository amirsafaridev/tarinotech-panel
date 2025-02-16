<?php

namespace Modules\Package\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Project\app\Models\ProjectBase;

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
            $bases = ProjectBase::query()
                ->with('types')
                ->get();

            $view->with(compact('bases'));
        });
    }
}
