<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Domain\Jobs\SeoProjectFactorMakeJob;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Modules\Project\app\Models\Project;

class SeoFactorController extends Controller
{
    use HasJsonCommonResponse;

    public function make($projectId)
    {
        try {
            $project = Project::findSeoTarget($projectId);

            $seoFactorMakeJob = resolve(SeoProjectFactorMakeJob::class);
            $seoFactorMakeJob->handle($project->target);

            return $this->successDestroyBack(route('admin.project.manage', $project->id));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
}
