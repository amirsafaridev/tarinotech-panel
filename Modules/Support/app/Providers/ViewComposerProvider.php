<?php

namespace Modules\Support\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admin\app\Models\Admin;
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
        view()->composer(['support::admin.group.create', 'support::admin.group.edit'], function ($view) {
            $projects = Project::query()
                ->select(['id', 'domain', 'title', 'base_id'])
                ->with('base')
                ->get()
                ->map(function (Project $item) {
                    $data = $item;
                    $data['optionTitle'] = sprintf('%s (%s) - (%s)', $item->title, $item->base->title, $item->domain);

                    return $data;
                });

            $admins = Admin::query()
                ->with('jobTitle')
                ->has('jobTitle')
                ->get()
                ->map(function (Admin $admin) {
                    $data = $admin;
                    $data['optionTitle'] = sprintf('%s %s - (%s)', $admin->first_name, $admin->last_name, $admin?->jobTitle->title);

                    return $data;

                });

            $view->with(compact('projects', 'admins'));
        });

        view()->composer(['support::admin.notify.edit'], function ($view) {

            $admins = Admin::query()
                ->with('jobTitle')
                ->has('jobTitle')
                ->get()
                ->map(function (Admin $admin) {
                    $data = $admin;
                    $data['optionTitle'] = sprintf('%s %s - (%s)', $admin->first_name, $admin->last_name, $admin?->jobTitle->title);

                    return $data;

                });

            $view->with(compact('admins'));
        });
    }
}
