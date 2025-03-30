<?php

namespace Modules\Survey\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Project\app\Models\Project;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Resources\Survey\SurveyResource;

class SurveyController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $user = auth()->user();
            $userId = $user->id;

            // Get surveys through metas relationship with different models
            $surveys = Survey::query()
                ->where('is_active', true)
                ->whereHas('metas', function (Builder $metaQuery) use ($userId) {
                    $metaQuery->where(function (Builder $query) use ($userId) {
                        // Handle different surveyable types
                        // Case 1: Project
                        $query->where('surveyable_type', Project::class)
                            ->whereIn('surveyable_id', function ($subQuery) use ($userId) {
                                $subQuery->select('id')
                                    ->from('projects')
                                    ->where('user_id', $userId);
                            });

                        // Case 2: Future model type can be added here
                        // $query->orWhere('surveyable_type', AnotherModel::class)
                        //      ->whereIn('surveyable_id', function ($subQuery) use ($userId) {
                        //          $subQuery->select('id')
                        //                  ->from('another_models')
                        //                  ->where('user_id', $userId);
                        //      });
                    });
                })
                // Filter by date range
                ->where(function ($query) {
                    $query->whereNull('start_date')
                        ->orWhere('start_date', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->withCount('questions')
                ->get();

            $surveys = $surveys->map(function ($survey) use ($user) {
                $hasParticipated = $survey->responses()
                    ->where('respondent_type', get_class($user))
                    ->where('respondent_id', $user->id)
                    ->exists();

                $survey->has_participated = $hasParticipated;

                return $survey;
            });

            return $this->successResponse(
                SurveyResource::collection($surveys),
            );
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
