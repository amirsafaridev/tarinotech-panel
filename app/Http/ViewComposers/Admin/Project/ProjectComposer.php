<?php

namespace App\Http\ViewComposers\Admin\Project;

use Illuminate\Contracts\View\View;
use Modules\Project\app\Models\ProjectBase;
use Modules\Project\app\Models\ProjectStatus;

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

        $typeId = request('type_id');
        if ($typeId && is_numeric($typeId)) {
            $statuses = ProjectStatus::query()
                ->where('type_id', $typeId)
                ->get();
        }

        $view->with(compact('bases', 'statuses'));
    }
}
