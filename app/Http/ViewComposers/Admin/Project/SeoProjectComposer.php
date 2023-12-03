<?php

namespace App\Http\ViewComposers\Admin\Project;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectStatus;

class SeoProjectComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $statuses = ProjectStatus::query()
            ->whereHas('type', function (Builder $q) {
                return $q->where('base_id', ProjectBase::Seo);
            })
            ->get();

        $view->with(compact('statuses'));
    }
}
