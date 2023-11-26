<?php

namespace App\Http\ViewComposers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Models\Package;
use App\Models\ProjectType;
use Illuminate\Contracts\View\View;

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
