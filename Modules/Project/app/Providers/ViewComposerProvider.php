<?php

namespace Modules\Project\app\Providers;

use App\Models\Package;
use Illuminate\Support\ServiceProvider;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectStatus;
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
        view()->composer(['project::admin.index'], function ($view) {
            $types = ProjectType::query()
                ->with('base')
                ->get();

            $statuses = ProjectStatus::query()
                ->with('type')
                ->get();

            $view->with(compact('types', 'statuses'));
        });

        view()->composer(['project::admin.web.index', 'project::admin.web.create'], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with('base')
                ->get();

            $statuses = ProjectStatus::query()
                ->whereHas('type', function ($q) {
                    $q->where('base_id', ProjectBase::Web);
                })
                ->with('type')
                ->get();

            $packages = Package::query()
                ->get();

            $view->with(compact('types', 'statuses', 'packages'));
        });
    }
}
