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

            return $this->successBack(route('admin.project.manage', $project->id), 'فاکتور خودکار با موفقیت ایجاد شد.');

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
}
