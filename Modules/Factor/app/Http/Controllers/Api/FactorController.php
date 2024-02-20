<?php

namespace Modules\Factor\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\ProjectFilter;
use Modules\Factor\app\Filters\Factor\SearchFilter;
use Modules\Factor\app\Filters\Factor\SortFilter;
use Modules\Factor\app\Filters\Factor\StatusFilter;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Resources\Factor\FactorCollection;
use Modules\Factor\app\Resources\Factor\FactorResource;
use Modules\Project\app\Models\Project;

class FactorController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $userProjectsIds = Project::query()
                ->select(['id', 'user_id'])
                ->where('user_id', auth()->id())
                ->get()
                ->pluck('id')
                ->toArray();

            $factors = Factor::query()
                ->whereIntegerInRaw('project_id', $userProjectsIds)
                ->with(['project.type', 'items.category'])
                ->filter([
                    SearchFilter::class,
                    ProjectFilter::class,
                    SortFilter::class,
                    StatusFilter::class,
                    PriceFilter::class,
                ])
                ->paginate();

            $data = new FactorCollection($factors);

            return $this->successResponse($data, 'factor list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function single(int $identify)
    {
        try {

            $userProjectsIds = Project::query()
                ->select(['id', 'user_id'])
                ->where('user_id', auth()->id())
                ->get()
                ->pluck('id')
                ->toArray();

            $factor = Factor::query()
                ->whereIntegerInRaw('project_id', $userProjectsIds)
                ->where('identify', $identify)
                ->with(['project.type', 'items.category'])
                ->firstOrFail();

            $data = new FactorResource($factor);

            return $this->successResponse($data, 'factor single');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
