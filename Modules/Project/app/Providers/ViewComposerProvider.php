<?php

namespace Modules\Project\app\Providers;

use App\Enums\Database\Role\RoleName;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\app\Models\Admin;
use Modules\Package\app\Models\Package;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\BusinessDomain;
use Modules\Project\app\Models\Facility;
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
     */
    public function boot(): void
    {
        $this->getIndexComposer();

        $this->getWebComposer();

        $this->getSeoComposer();

        $this->getAdsComposer();

        $this->getBaseComposer();

        $this->getStatusComposer();

        $this->getOptionComposer();

        $this->getFacilityComposer();
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
                ->with(['base', 'statuses.type'])
                ->get();

            $statuses = ProjectStatus::query()
                ->adsBase()
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
            'project::admin.web.create',
            'project::admin.web.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with(['base', 'statuses.type'])
                ->get();

            $statuses = ProjectStatus::query()
                ->webBase()
                ->with('type')
                ->get();

            $packages = Package::query()
                ->get();

            $businessDomains = BusinessDomain::query()
                ->get();

            $facilities = Facility::query()
                ->where('base_id', ProjectBase::Web)
                ->get();

            $admins = $this->getAdminBaseOnRole();

            $view->with(compact('types', 'statuses', 'packages', 'businessDomains', 'admins', 'facilities'));
        });

        view()->composer([
            'project::admin.web.status.edit',
        ], function ($view) {
            $types = ProjectType::query()
                ->where('base_id', ProjectBase::Web)
                ->with(['base', 'statuses.type'])
                ->get();

            $statuses = ProjectStatus::query()
                ->webBase()
                ->with('type')
                ->get();

            $view->with(compact('types', 'statuses'));
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
                ->with(['base', 'statuses.type'])
                ->get();

            $statuses = ProjectStatus::query()
                ->seoBase()
                ->with('type')
                ->get();

            $packages = Package::query()
                ->whereHas('type', function ($q) {
                    $q->where('base_id', ProjectBase::Seo);
                })
                ->get();

            $admins = $this->getAdminBaseOnRole();

            $view->with(compact('types', 'statuses', 'packages', 'admins'));
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

    private function getOptionComposer()
    {
        view()->composer([
            'project::admin.option.create',
            'project::admin.option.edit',
        ], function ($view) {

            $bases = ProjectBaseModel::query()
                ->get();

            $view->with(compact('bases'));
        });
    }

    private function getFacilityComposer()
    {
        view()->composer([
            'project::admin.facility.create',
            'project::admin.facility.edit',
        ], function ($view) {

            $bases = ProjectBaseModel::query()
                ->get();

            $view->with(compact('bases'));
        });
    }

    private function getAdminBaseOnRole(): Collection
    {
        $admins = collect([]);
        if (hasAdminRole(RoleName::SUPER_ADMIN)) {
            $admins = Admin::query()
                ->latest()
                ->get();
        }

        return $admins;

    }
}
