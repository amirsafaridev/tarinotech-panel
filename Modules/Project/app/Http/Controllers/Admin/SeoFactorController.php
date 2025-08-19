<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Domain\Jobs\SeoProjectFactorMakeJob;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\Project;

class SeoFactorController extends Controller
{
    use HasJsonCommonResponseTrait;

    public function make($projectId)
    {
        try {
            $project = Project::findSeoTarget($projectId);

            $automateFactorCheck = Factor::query()
                ->where('project_id', $projectId)
                ->where('is_automate', true)
                ->exists();

            if ($automateFactorCheck) {
                return $this->errorBack('برای این پروژه قبلا فاکتور خودکار ساخته شده است.', route('admin.project.manage', $project->id));
            }

            $seoFactorMakeJob = resolve(SeoProjectFactorMakeJob::class);
            $seoFactorMakeJob->handle($project->target);

            return $this->successBack(route('admin.project.manage', $project->id), 'فاکتور خودکار با موفقیت ایجاد شد.');

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
    public function makeFacilitiesFactore($projectId, $userId ,$facility)
    {
        try {
            $project = Project::findSeoTarget($projectId);

            $seoFactorMakeJob = resolve(SeoProjectFactorMakeJob::class);
            $seoFactorMakeJob->handleFacilitiesFactore($project->target, $userId, $facility);


        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }
}
