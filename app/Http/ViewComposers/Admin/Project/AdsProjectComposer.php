<?php

namespace App\Http\ViewComposers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Models\ProjectStatus;
use Illuminate\Contracts\View\View;

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
