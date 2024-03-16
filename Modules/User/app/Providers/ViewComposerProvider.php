<?php

namespace Modules\User\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\app\Models\Communication;
use Modules\User\app\Models\KnowledgeWay;

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
        view()->composer(['user::admin.user.create', 'user::admin.user.edit'], function ($view) {
            $knowledgeWays = KnowledgeWay::query()
                ->get();

            $communications = Communication::query()
                ->get();
            $view->with(compact('knowledgeWays', 'communications'));
        });
    }
}
