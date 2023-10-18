<?php

namespace App\Http\ViewComposers\Admin\Project;

use App\Models\ProjectBase;
use App\Models\ProjectStatus;
use Illuminate\Contracts\View\View;

class ProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $bases = ProjectBase::query()
            ->get();

        $statuses = collect([]);

        $baseId = request('base_id');
        if ($baseId && is_numeric($baseId)) {
            $statuses = ProjectStatus::query()
                ->where('project_base_id', $baseId)
                ->get();
        }

        $view->with(compact('bases', 'statuses'));
    }
}
