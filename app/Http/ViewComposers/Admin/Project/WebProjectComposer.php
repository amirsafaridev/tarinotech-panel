<?php

namespace App\Http\ViewComposers\Admin\Project;

use Illuminate\Contracts\View\View;
use Modules\Package\app\Models\Package;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectType;

class WebProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $types = ProjectType::query()
            ->with('statuses')
            ->where('base_id', ProjectBase::Web)
            ->get();

        $packages = Package::query()
            ->get();

        $view->with(compact('types', 'packages'));
    }
}
