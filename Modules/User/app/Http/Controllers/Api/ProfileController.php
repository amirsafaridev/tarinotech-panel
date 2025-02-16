<?php

namespace Modules\User\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\app\Models\PresenterProject;
use Modules\Contract\app\Models\UserSignable;
use Modules\Contract\app\Resources\UserSignable\SignableResource;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Resources\Project\ProjectResource;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Http\Requests\Api\User\UpdateRequest;
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

            $signables = UserSignable::query()
                ->where('user_id', $user->id)
                ->whereHas('target')
                ->with(['target.project', 'attachments'])
                ->latest()
                ->get();

            return $this->successResponse([
                'user' => new UserResource($user),
                'projects' => ProjectResource::collection($projects),
                'signables' => SignableResource::collection($signables),
            ]);
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function update(UpdateRequest $request)
    {
        try {
            auth()->user()->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'en_first_name' => $request->input('en_first_name'),
                'en_last_name' => $request->input('en_last_name'),
            ]);

            return $this->successResponse(null, 'با موفقیت به روز شد!');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->successResponse(null, 'با موفقیت خارج شدید!');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
