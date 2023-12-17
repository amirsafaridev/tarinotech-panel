<?php

namespace Modules\Factor\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Project\app\Models\Project;

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
        view()->composer(['factor::admin.create', 'factor::admin.edit'], function ($view) {

            $projects = Project::query()
                ->select(['id', 'domain', 'title', 'base_id'])
                ->with('base')
                ->get()
                ->map(function (Project $item) {
                    $data = $item;
                    $data['optionTitle'] = sprintf('%s (%s) - (%s)', $item->title, $item->base->title, $item->domain);

                    return $data;
                });

            $view->with(compact('projects'));
        });
    }
}
