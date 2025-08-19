<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Domain\Jobs\WebProjectFactorMakerJob;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\Project;

class WebFactorController extends Controller
{
    use HasJsonCommonResponseTrait;

    public function make($projectId)
    {
        try {
            $project = Project::findWebTarget($projectId);

            $automateFactorCheck = Factor::query()
                ->where('project_id', $projectId)
                ->where('is_automate', true)
                ->exists();

            if ($automateFactorCheck) {
                return $this->errorBack('برای این پروژه قبلا فاکتور خودکار ساخته شده است.', route('admin.project.manage', $project->id));
            }

            $autoMakeFactor = resolve(WebProjectFactorMakerJob::class);
            $autoMakeFactor->handle($project);

            return $this->successBack(route('admin.project.manage', $project->id), 'فاکتور خودکار با موفقیت ایجاد شد.');

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
    public function makeFacilitiesFactore($projectId, $userId, $facility)
    {

        try {
            $project = Project::findWebTarget($projectId);

            $seoFactorMakeJob = resolve(WebProjectFactorMakerJob::class);

            $seoFactorMakeJob->handleFacilitiesFactore($project, $userId, $facility);


        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);

        }

    }
}
