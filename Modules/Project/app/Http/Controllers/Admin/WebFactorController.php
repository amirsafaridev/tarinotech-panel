<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Domain\Jobs\WebProjectFactorMakerJob;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Modules\Project\app\Models\Project;

class WebFactorController extends Controller
{
    use HasJsonCommonResponse;

    public function make($projectId)
    {
        try {
            $project = Project::findWebTarget($projectId);

            $autoMakeFactor = resolve(WebProjectFactorMakerJob::class);
            $autoMakeFactor->handle($project);

            return $this->successDestroyBack(route('admin.project.manage', $project->id));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
}
