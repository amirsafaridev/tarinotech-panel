<?php

namespace App\Http\ViewComposers\Admin\Project;

use Illuminate\Contracts\View\View;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectStatus;

class AdsProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $statuses = ProjectStatus::query()
            ->where('type_id', ProjectBase::Ads)
            ->get();

        $view->with(compact('statuses'));
    }
}
