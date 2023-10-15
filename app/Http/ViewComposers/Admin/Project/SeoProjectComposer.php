<?php

namespace App\Http\ViewComposers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Models\ProjectStatus;
use Illuminate\Contracts\View\View;

class SeoProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $statuses = ProjectStatus::query()
            ->where('project_base_id', ProjectBase::Seo)
            ->get();

        $view->with(compact('statuses'));
    }
}
