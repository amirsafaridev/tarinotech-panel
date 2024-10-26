<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Modules\Project\app\Http\Requests\Admin\Web\UpdateRequirementRequest;
use Modules\Project\app\Models\ProjectWeb;

class WebRequirementController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه وب - نیازمندی ها';

    public function index($projectId)
    {
        $title = self::INDEX_TITLE;

        $projectWeb = ProjectWeb::query()
            ->with('requirement')
            ->findOrFail($projectId);

        $requirement = $projectWeb->requirement ?? null;

        return view('project::admin.web.requirement', compact('title', 'projectId', 'requirement'));
    }

    public function update($projectId, UpdateRequirementRequest $request)
    {
        try {
            $projectWeb = ProjectWeb::query()->findOrFail($projectId);

            $data = $request->validated();

            //$data['internal_pages_content'] = json_encode($data['internal_pages_content']);
            //$data['contract_differences'] = json_encode($data['contract_differences']);

            $projectWeb->requirement()->updateOrCreate(
                ['project_web_id' => $projectWeb->id],
                $data
            );

            return $this->successResponse();

        } catch (Exception $exception) {

            report($exception);

            return $this->exceptionResponse($exception);
        }
    }
}
