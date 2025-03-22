<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Exports\Admin\SurveyReportExport;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Traits\HasSurveyResponseTrendTrait;
use Modules\Survey\app\Traits\HasSurveyStatsTrait;

class SurveyReportController extends Controller
{
    use HasJsonCommonResponseTrait;
    use HasSurveyResponseTrendTrait;
    use HasSurveyStatsTrait;

    const INDEX_TITLE = 'گزارش نظرسنجی';

    /**
     * Display the main report dashboard.
     *
     * @return View
     */
    public function index(Survey $survey)
    {
        $title = self::INDEX_TITLE;

        $stats = $this->getBasicStats($survey);

        $responseTrend = $this->getResponseTrendData($survey);

        $questionCompletionRates = $this->getQuestionCompletionRates($survey);

        $topQuestions = $questionCompletionRates->sortByDesc('completion_rate')->take(5);
        $bottomQuestions = $questionCompletionRates->sortBy('completion_rate')->take(5);

        return view('survey::admin.report.index', compact(
            'title',
            'survey',
            'stats',
            'responseTrend',
            'topQuestions',
            'bottomQuestions'
        ));
    }

    public function export(Survey $survey)
    {
        try {
            $fileName = 'Survey-Report-'.$survey->id.'-'.Carbon::now()->format('Y-m-d').'.xlsx';

            $survey->load(['questions.options', 'questions.answers.options.questionOption']);
            $stats = $this->getBasicStats($survey);
            $questionData = $this->prepareQuestionDataForExport($survey);

            return Excel::download(
                new SurveyReportExport($survey, $stats, $questionData),
                $fileName
            );
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن گزارش');
        }
    }

    /**
     * Get question completion rates.
     *
     * @return Collection
     */
    private function getQuestionCompletionRates(Survey $survey)
    {
        $totalResponses = $survey->responses()->count();

        if ($totalResponses === 0) {
            return collect();
        }

        return $survey->questions()
            ->with('answers')
            ->get()
            ->map(function ($question) use ($totalResponses) {
                $answersCount = $question->answers->count();
                $completionRate = round(($answersCount / $totalResponses) * 100, 1);

                return [
                    'id' => $question->id,
                    'text' => $question->question_text,
                    'answers_count' => $answersCount,
                    'completion_rate' => $completionRate,
                    'is_required' => $question->is_required,
                ];
            });
    }

    /**
     * Prepare question data for export.
     *
     * @return array
     */
    private function prepareQuestionDataForExport(Survey $survey)
    {
        $questionData = [];
        $totalResponses = $survey->responses()->count();

        foreach ($survey->questions as $question) {
            $questionInfo = [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => QuestionTypeEnum::getDescription($question->question_type),
                'required' => $question->is_required ? 'بله' : 'خیر',
                'answers_count' => $question->answers->count(),
                'response_rate' => $totalResponses > 0
                    ? round(($question->answers->count() / $totalResponses) * 100, 1).'%'
                    : '0%',
            ];

            // For choice questions, include option breakdown
            if (in_array($question->question_type, [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple])) {
                $optionStats = [];

                foreach ($question->options as $option) {
                    $count = DB::table('survey_answer_options')
                        ->join('survey_answers', 'survey_answers.id', '=', 'survey_answer_options.answer_id')
                        ->where('survey_answer_options.survey_question_option_id', $option->id)
                        ->where('survey_answers.survey_question_id', $question->id)
                        ->count();

                    $optionStats[$option->option_text] = [
                        'count' => $count,
                        'percentage' => $question->answers->count() > 0
                            ? round(($count / $question->answers->count()) * 100, 1).'%'
                            : '0%',
                    ];
                }

                $questionInfo['options'] = $optionStats;
            }

            // For text questions, include some example answers
            if ($question->question_type == QuestionTypeEnum::Text) {
                $textSamples = SurveyAnswer::where('survey_question_id', $question->id)
                    ->whereNotNull('answer_text')
                    ->orderBy(DB::raw('RAND()'))
                    ->take(10)
                    ->pluck('answer_text')
                    ->toArray();

                $questionInfo['text_samples'] = $textSamples;
            }

            $questionData[] = $questionInfo;
        }

        return $questionData;
    }
}
