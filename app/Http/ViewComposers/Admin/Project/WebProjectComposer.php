<?php

namespace App\Http\ViewComposers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Package;
use App\Models\ProjectStatus;
use App\Models\ProjectType;
use Illuminate\Contracts\View\View;

class WebProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $projectTypes = ProjectType::query()
            ->where('project_base_id', ProjectBase::Web)
            ->get();

        $packages = Package::query()
            ->get();

        $statuses = ProjectStatus::query()
            ->where('project_base_id', ProjectBase::Web)
            ->get();

        $view->with(compact('projectTypes', 'packages', 'statuses'));
    }
}
