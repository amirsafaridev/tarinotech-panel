<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Survey\app\Exports\Admin\ResponseExport;
use Modules\Survey\app\Filters\Response\DateFilter;
use Modules\Survey\app\Filters\Response\SearchFilter;
use Modules\Survey\app\Filters\Response\SortFilter;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyResponseController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پاسخ‌های نظرسنجی';

    const SHOW_TITLE = 'جزئیات پاسخ';

    public function index(Survey $survey)
    {
        $title = self::INDEX_TITLE;

        $responses = SurveyResponse::query()
            ->select([
                'survey_responses.id',
                'survey_responses.respondent_type',
                'survey_responses.respondent_id',
                'survey_responses.respondent_email',
                'survey_responses.respondent_name',
                'survey_responses.ip_address',
                'survey_responses.created_at',
                'survey_responses.started_at',
                'survey_responses.completed_at',
                DB::raw('COUNT(survey_answers.id) as answers_count'),
            ])
            ->where('survey_id', $survey->id)
            ->leftJoin('survey_answers', 'survey_responses.id', '=', 'survey_answers.response_id')
            ->groupBy(
                'survey_responses.id',
                'survey_responses.respondent_type',
                'survey_responses.respondent_id',
                'survey_responses.respondent_email',
                'survey_responses.respondent_name',
                'survey_responses.ip_address',
                'survey_responses.created_at'
            )
            ->filter([
                SortFilter::class,
                DateFilter::class,
                SearchFilter::class,
            ]);

        if (request('export')) {
            return $this->export($survey, $responses->get());
        }

        $responses = $responses->paginate(20)
            ->withQueryString();

        $totalResponses = $responses->total();
        $completionRate = $this->calculateCompletionRate($survey, $responses);
        $averageResponseTime = $this->calculateAverageResponseTime($responses);

        return view('survey::admin.response.index', compact(
            'title',
            'survey',
            'responses',
            'totalResponses',
            'completionRate',
            'averageResponseTime'
        ));
    }

    public function show(Survey $survey, SurveyResponse $response): View
    {
        $title = self::SHOW_TITLE;

        $response->load(['answers.question', 'answers.options.questionOption']);

        return view('survey::admin.response.show', compact('title', 'survey', 'response'));
    }

    public function destroy(Survey $survey, SurveyResponse $response): RedirectResponse
    {
        try {
            $response->delete();

            return $this->successDestroyBack(route('admin.survey.response.index', $survey->id));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function export(Survey $survey, Collection $responses)
    {
        try {
            $survey->load('questions.options');

            $fileName = 'پاسخ‌های-نظرسنجی-'.$survey->id.'-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new ResponseExport($survey, $responses), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات. لطفاً دوباره تلاش کنید.');
        }
    }

    private function calculateCompletionRate(Survey $survey, LengthAwarePaginator $responses): float
    {
        if ($responses->isEmpty()) {
            return 0;
        }

        $requiredQuestionsCount = $survey->questions()->where('is_required', true)->count();

        if ($requiredQuestionsCount === 0) {
            return 100;
        }

        $completedCount = 0;

        foreach ($responses as $response) {
            $answeredRequiredCount = SurveyAnswer::query()
                ->whereIn('survey_question_id', function ($query) use ($survey) {
                    $query->select('id')
                        ->from('survey_questions')
                        ->where('survey_id', $survey->id)
                        ->where('is_required', true);
                })
                ->where('response_id', $response->id)
                ->count();

            if ($answeredRequiredCount >= $requiredQuestionsCount) {
                $completedCount++;
            }
        }

        return round(($completedCount / count($responses)) * 100, 1);
    }

    private function calculateAverageResponseTime(LengthAwarePaginator $responses): string
    {
        $responseItems = collect($responses->items());
        $responsesWithTimes = $responseItems->filter(function ($response) {
            return $response->getCompletionTimeInSeconds() !== null;
        });

        if ($responsesWithTimes->isEmpty()) {
            return 'اطلاعات موجود نیست';
        }

        $totalSeconds = $responsesWithTimes->sum(function ($response) {
            return $response->getCompletionTimeInSeconds();
        });

        $count = $responsesWithTimes->count();
        $averageSeconds = $totalSeconds / $count;
        $minutes = floor($averageSeconds / 60);
        $seconds = round($averageSeconds % 60);

        return "$minutes دقیقه و $seconds ثانیه";
    }
}
