<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Log\app\Traits\HasSingleLogTrack;
use Modules\Project\app\Http\Requests\Admin\WebStatus\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;

class WebStatusController extends Controller
{
    use HasJsonCommonResponseTrait;
    use HasSingleLogTrack;

    const INDEX_TITLE = 'پروژه های وب - ویرایش وضعیت';

    public function index($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = self::INDEX_TITLE;

        $logs = $this->trackChanges('status_id', $project, $projectId);

        return view('project::admin.web.status.edit', compact('title', 'project', 'logs'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = $this->getOrFailProject($projectId);

            $project->update($this->initialProjectData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    private function initialProjectData(Request $request): array
    {

        return [
            'status_id' => $request->input('status_id'),
            'type_id' => $request->input('type_id'),
        ];
    }

    private function getOrFailProject($projectId): Project
    {
        return Project::query()
            ->whereHasMorph('target', [ProjectWeb::class])
            ->with('target.options')
            ->findOrFail($projectId);
    }
}
