<?php

namespace Modules\User\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Admin\app\Models\PresenterProject;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Resources\Project\ProjectResource;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Resources\User\UserResource;

class ProfileController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $user = auth()->user();
            $projectsQuery = Project::query()
                ->with('type');

            if ($user->user_type === UserType::Primary) {
                $projectsQuery->where('user_id', $user->id);
            } else {
                $presenterProjectIds = PresenterProject::query()
                    ->where('user_id', $user->id)
                    ->pluck('project_id');

                $projectsQuery->whereIn('id', $presenterProjectIds);
            }

            $projects = $projectsQuery
                ->latest()
                ->get();

            return $this->successResponse([
                'user' => new UserResource($user),
                'projects' => ProjectResource::collection($projects),
            ]);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'با موفقیت خارج شدید!');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
