<?php

namespace Modules\Factor\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Package\app\Models\Package;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Project;
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

        view()->composer(['factor::admin.create'], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with(['base', 'statuses.type'])
                ->get();
            $packages = Package::query()
                ->get();

            $view->with(compact('types', 'packages'));
        });

        view()->composer(['factor::admin.status.create', 'factor::admin.status.edit'], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with(['base', 'statuses.type'])
                ->get();
            $view->with(compact('types'));
        });
    }
}
