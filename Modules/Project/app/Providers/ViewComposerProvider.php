<?php

namespace Modules\Project\app\Providers;

use App\Models\Package;
use Illuminate\Support\ServiceProvider;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectBase as ProjectBaseModel;
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
        $this->getIndexComposer();

        $this->getWebComposer();

        $this->getSeoComposer();

        $this->getAdsComposer();

        $this->getBaseComposer();

        $this->getStatusComposer();
    }

    private function getIndexComposer(): void
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
    }

    private function getAdsComposer(): void
    {
        view()->composer([
            'project::admin.ads.index',
            'project::admin.ads.create',
            'project::admin.ads.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Ads)
                ->with(['base', 'statuses'])
                ->get();

            $statuses = ProjectStatus::query()
                ->whereHas('type', function ($q) {
                    $q->where('base_id', ProjectBase::Ads);
                })
                ->with('type')
                ->get();

            $view->with(compact('types', 'statuses'));
        });
    }

    private function getWebComposer(): void
    {
        view()->composer([
            'project::admin.web.index',
            'project::admin.web.create',
            'project::admin.web.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with(['base', 'statuses'])
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

    private function getSeoComposer(): void
    {
        view()->composer([
            'project::admin.seo.index',
            'project::admin.seo.create',
            'project::admin.seo.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Seo)
                ->with(['base', 'statuses'])
                ->get();

            $statuses = ProjectStatus::query()
                ->whereHas('type', function ($q) {
                    $q->where('base_id', ProjectBase::Seo);
                })
                ->with('type')
                ->get();

            $view->with(compact('types', 'statuses'));
        });
    }

    private function getBaseComposer()
    {
        view()->composer([
            'project::admin.type.create',
            'project::admin.type.edit',
        ], function ($view) {

            $bases = ProjectBaseModel::query()
                ->get();

            $view->with(compact('bases'));
        });
    }

    private function getStatusComposer()
    {
        view()->composer([
            'project::admin.status.create',
            'project::admin.status.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->with('base')
                ->get();

            $view->with(compact('types'));
        });
    }
}
