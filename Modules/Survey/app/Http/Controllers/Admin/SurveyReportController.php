<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
//use Modules\Survey\app\Exports\Admin\SurveyReportExport;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Models\SurveyResponse;

class SurveyReportController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'گزارش نظرسنجی';

    const SUMMARY_TITLE = 'خلاصه نتایج نظرسنجی';

    const QUESTION_TITLE = 'گزارش سوال';

    const CHARTS_TITLE = 'نمودارهای نظرسنجی';

    /**
     * Display the main report dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Survey $survey)
    {
        $title = self::INDEX_TITLE;

        // Get basic statistics
        $stats = $this->getBasicStats($survey);

        // Get response trend data (responses over time)
        $responseTrend = $this->getResponseTrendData($survey);

        // Get completion rate by question
        $questionCompletionRates = $this->getQuestionCompletionRates($survey);

        // Get top 5 questions with highest and lowest response rates
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

    /**
     * Display summary of the survey results.
     *
     * @return \Illuminate\View\View
     */
    public function summary(Survey $survey)
    {
        $title = self::SUMMARY_TITLE;

        // Get basic statistics
        $stats = $this->getBasicStats($survey);

        // Load questions with their answers
        $questions = $survey->questions()
            ->with(['options', 'answers'])
            ->orderBy('order')
            ->get();

        // Process question data for summary
        $questionsSummary = [];

        foreach ($questions as $question) {
            $summary = [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->question_type,
                'type_name' => QuestionTypeEnum::getDescription($question->question_type),
                'total_answers' => $question->answers->count(),
                'is_required' => $question->is_required,
                'response_rate' => $stats['total_responses'] > 0
                    ? round(($question->answers->count() / $stats['total_responses']) * 100, 1)
                    : 0,
            ];

            // For choice questions, calculate option distribution
            if (in_array($question->question_type, [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple])) {
                $optionCounts = [];
                $totalSelections = 0;

                foreach ($question->options as $option) {
                    $count = DB::table('survey_answer_options')
                        ->join('survey_answers', 'survey_answers.id', '=', 'survey_answer_options.answer_id')
                        ->where('survey_answer_options.survey_question_option_id', $option->id)
                        ->where('survey_answers.survey_question_id', $question->id)
                        ->count();

                    $optionCounts[] = [
                        'id' => $option->id,
                        'text' => $option->option_text,
                        'color' => $option->color_code,
                        'count' => $count,
                    ];

                    $totalSelections += $count;
                }

                // Calculate percentages
                if ($totalSelections > 0) {
                    foreach ($optionCounts as &$option) {
                        $option['percentage'] = round(($option['count'] / $totalSelections) * 100, 1);
                    }
                } else {
                    foreach ($optionCounts as &$option) {
                        $option['percentage'] = 0;
                    }
                }

                $summary['options'] = $optionCounts;
                $summary['total_selections'] = $totalSelections;
            }

            // For text questions, get some sample answers
            if ($question->question_type == QuestionTypeEnum::Text) {
                $textAnswers = SurveyAnswer::where('survey_question_id', $question->id)
                    ->whereNotNull('answer_text')
                    ->orderBy(DB::raw('RAND()'))
                    ->take(5)
                    ->pluck('answer_text')
                    ->toArray();

                $summary['text_samples'] = $textAnswers;
            }

            $questionsSummary[] = $summary;
        }

        return view('survey::admin.report.summary', compact(
            'title',
            'survey',
            'stats',
            'questionsSummary'
        ));
    }

    /**
     * Display detailed report for a specific question.
     *
     * @return \Illuminate\View\View
     */
    public function questionReport(Survey $survey, SurveyQuestion $question)
    {
        $title = self::QUESTION_TITLE.': '.$question->question_text;

        // Load question with options and answers
        $question->load(['options', 'answers.options.questionOption']);

        // Get basic stats
        $stats = $this->getBasicStats($survey);

        // Question-specific stats
        $totalAnswers = $question->answers->count();
        $responseRate = $stats['total_responses'] > 0
            ? round(($totalAnswers / $stats['total_responses']) * 100, 1)
            : 0;

        $questionData = [
            'id' => $question->id,
            'text' => $question->question_text,
            'type' => $question->question_type,
            'type_name' => QuestionTypeEnum::getDescription($question->question_type),
            'total_answers' => $totalAnswers,
            'response_rate' => $responseRate,
            'is_required' => $question->is_required,
        ];

        // For choice questions, calculate detailed option distribution
        if (in_array($question->question_type, [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple])) {
            $optionStats = [];

            foreach ($question->options as $option) {
                $count = DB::table('survey_answer_options')
                    ->join('survey_answers', 'survey_answers.id', '=', 'survey_answer_options.answer_id')
                    ->where('survey_answer_options.survey_question_option_id', $option->id)
                    ->where('survey_answers.survey_question_id', $question->id)
                    ->count();

                $optionStats[] = [
                    'id' => $option->id,
                    'text' => $option->option_text,
                    'color' => $option->color_code,
                    'count' => $count,
                    'percentage' => $totalAnswers > 0 ? round(($count / $totalAnswers) * 100, 1) : 0,
                ];
            }

            $questionData['options'] = $optionStats;

            // Get selections breakdown by demographic (like time period)
            // Responses by month for this question
            $monthlyResponses = DB::table('survey_answers')
                ->select(DB::raw('DATE_FORMAT(survey_answers.created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
                ->where('survey_question_id', $question->id)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month')
                ->map(function ($item) {
                    return $item->count;
                })
                ->toArray();

            $questionData['monthly_responses'] = $monthlyResponses;
        }

        // For text questions, get all text answers paginated
        if ($question->question_type == QuestionTypeEnum::Text) {
            $textAnswers = SurveyAnswer::where('question_id', $question->id)
                ->whereNotNull('answer_text')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $questionData['text_answers'] = $textAnswers;

            // Get word frequency for text analysis
            $wordFrequency = $this->analyzeTextResponses($question);
            $questionData['word_frequency'] = array_slice($wordFrequency, 0, 20);
        }

        return view('survey::admin.report.question', compact(
            'title',
            'survey',
            'question',
            'stats',
            'questionData'
        ));
    }

    /**
     * Display chart visualizations for survey data.
     *
     * @return \Illuminate\View\View
     */
    public function charts(Survey $survey)
    {
        $title = self::CHARTS_TITLE;

        // Get basic statistics
        $stats = $this->getBasicStats($survey);

        // Get response trend data (responses over time)
        $responseTrend = $this->getResponseTrendData($survey);

        // Get question completion rates
        $questionCompletionRates = $this->getQuestionCompletionRates($survey);

        // Get responses by time of day
        $responsesByHour = $this->getResponsesByHour($survey);

        // Get choice distribution for multiple choice questions
        $choiceQuestions = $survey->questions()
            ->whereIn('question_type', [QuestionTypeEnum::Single, QuestionTypeEnum::Multiple])
            ->with('options')
            ->orderBy('order')
            ->get();

        $choiceDistribution = [];
        foreach ($choiceQuestions as $question) {
            $optionData = [];
            $totalSelections = 0;

            foreach ($question->options as $option) {
                $count = DB::table('survey_answer_options')
                    ->join('survey_answers', 'survey_answers.id', '=', 'survey_answer_options.answer_id')
                    ->where('survey_answer_options.question_option_id', $option->id)
                    ->where('survey_answers.question_id', $question->id)
                    ->count();

                $optionData[] = [
                    'id' => $option->id,
                    'text' => $option->option_text,
                    'color' => $option->color_code,
                    'count' => $count,
                ];

                $totalSelections += $count;
            }

            // Calculate percentages
            if ($totalSelections > 0) {
                foreach ($optionData as &$option) {
                    $option['percentage'] = round(($option['count'] / $totalSelections) * 100, 1);
                }
            } else {
                foreach ($optionData as &$option) {
                    $option['percentage'] = 0;
                }
            }

            $choiceDistribution[] = [
                'id' => $question->id,
                'text' => $question->question_text,
                'options' => $optionData,
                'total' => $totalSelections,
            ];
        }

        return view('survey::admin.report.charts', compact(
            'title',
            'survey',
            'stats',
            'responseTrend',
            'questionCompletionRates',
            'responsesByHour',
            'choiceDistribution'
        ));
    }

    /**
     * Export survey report to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Survey $survey)
    {
        try {
            $fileName = 'Survey-Report-'.$survey->id.'-'.Carbon::now()->format('Y-m-d').'.xlsx';

            // Get survey data for export
            $survey->load(['questions.options', 'questions.answers.options.questionOption']);
            $stats = $this->getBasicStats($survey);
            $questionData = $this->prepareQuestionDataForExport($survey);

            /*return Excel::download(
                new SurveyReportExport($survey, $stats, $questionData),
                $fileName
            );*/
        } catch (Exception $exception) {
            report($exception);

            /*return back()->with('danger', 'خطا در هنگام صادر کردن گزارش');*/
        }
    }

    /**
     * Get basic statistics for a survey.
     *
     * @return array
     */
    private function getBasicStats(Survey $survey)
    {
        $totalResponses = $survey->responses()->count();
        $totalQuestions = $survey->questions()->count();

        // Calculate average response time (placeholder - would need start/end time tracking)
        $averageResponseTime = 'اطلاعات موجود نیست';

        // Calculate completion rate
        $requiredQuestionsCount = $survey->questions()->where('is_required', true)->count();
        $completedResponses = 0;

        if ($requiredQuestionsCount > 0) {
            foreach ($survey->responses as $response) {
                $answeredRequiredCount = SurveyAnswer::whereIn('survey_question_id', function ($query) use ($survey) {
                    $query->select('id')
                        ->from('survey_questions')
                        ->where('survey_id', $survey->id)
                        ->where('is_required', true);
                })
                    ->where('response_id', $response->id)
                    ->count();

                if ($answeredRequiredCount >= $requiredQuestionsCount) {
                    $completedResponses++;
                }
            }
        } else {
            // If no required questions, all responses are considered complete
            $completedResponses = $totalResponses;
        }

        $completionRate = $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0;

        // Calculate average answers per response
        $totalAnswers = SurveyAnswer::whereIn('response_id', function ($query) use ($survey) {
            $query->select('id')
                ->from('survey_responses')
                ->where('survey_id', $survey->id);
        })->count();

        $averageAnswersPerResponse = $totalResponses > 0 ? round($totalAnswers / $totalResponses, 1) : 0;

        // Get first and last response dates
        $firstResponse = $survey->responses()->oldest('created_at')->first();
        $lastResponse = $survey->responses()->latest('created_at')->first();

        // Calculate response rate for questions
        $questionResponseRates = [];
        foreach ($survey->questions as $question) {
            $answerCount = $question->answers->count();
            $responseRate = $totalResponses > 0 ? round(($answerCount / $totalResponses) * 100, 1) : 0;

            $questionResponseRates[] = [
                'id' => $question->id,
                'text' => $question->question_text,
                'answers' => $answerCount,
                'response_rate' => $responseRate,
            ];
        }

        return [
            'total_responses' => $totalResponses,
            'total_questions' => $totalQuestions,
            'completion_rate' => $completionRate,
            'average_response_time' => $averageResponseTime,
            'average_answers_per_response' => $averageAnswersPerResponse,
            'first_response_date' => $firstResponse ? $firstResponse->created_at : null,
            'last_response_date' => $lastResponse ? $lastResponse->created_at : null,
            'question_response_rates' => $questionResponseRates,
        ];
    }

    /**
     * Get response trend data (responses over time).
     *
     * @return array
     */
    private function getResponseTrendData(Survey $survey)
    {
        // Get responses by day
        $responsesByDay = SurveyResponse::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('survey_id', $survey->id)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();

        // Fill in days with no responses
        $firstResponse = $survey->responses()->oldest('created_at')->first();
        $lastResponse = $survey->responses()->latest('created_at')->first();

        if ($firstResponse && $lastResponse) {
            $startDate = Carbon::parse($firstResponse->created_at)->startOfDay();
            $endDate = Carbon::parse($lastResponse->created_at)->startOfDay();

            $responseTrend = [];
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');
                $responseTrend[$dateStr] = $responsesByDay[$dateStr] ?? 0;
                $currentDate->addDay();
            }

            // Format for charts
            $chartData = [];
            foreach ($responseTrend as $date => $count) {
                $chartData[] = [
                    'date' => $date,
                    'count' => $count,
                    'jalali_date' => Carbon::parse($date)->toJalali()->format('Y/m/d'),
                ];
            }

            return $chartData;
        }

        return [];
    }

    /**
     * Get question completion rates.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getQuestionCompletionRates(Survey $survey)
    {
        $totalResponses = $survey->responses()->count();

        if ($totalResponses === 0) {
            return collect([]);
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
     * Get responses distribution by hour of day.
     *
     * @return array
     */
    private function getResponsesByHour(Survey $survey)
    {
        $responsesByHour = SurveyResponse::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->where('survey_id', $survey->id)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        // Fill in hours with no responses
        $result = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $result[] = [
                'hour' => $hour,
                'count' => $responsesByHour[$hour] ?? 0,
                'display' => sprintf('%02d:00', $hour),
            ];
        }

        return $result;
    }

    /**
     * Analyze text responses for a question.
     *
     * @return array
     */
    private function analyzeTextResponses(SurveyQuestion $question)
    {
        // Get all text answers
        $textAnswers = SurveyAnswer::where('question_id', $question->id)
            ->whereNotNull('answer_text')
            ->pluck('answer_text')
            ->toArray();

        if (empty($textAnswers)) {
            return [];
        }

        // Simple word frequency analysis
        $allText = implode(' ', $textAnswers);
        $allText = strtolower($allText);

        // Remove common punctuation
        $allText = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $allText);

        // Split into words
        $words = preg_split('/\s+/', $allText, -1, PREG_SPLIT_NO_EMPTY);

        // Remove common stop words (add more if needed)
        $stopWords = ['and', 'the', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'is', 'are', 'was', 'were', 'be', 'been', 'being'];
        $words = array_filter($words, function ($word) use ($stopWords) {
            return ! in_array($word, $stopWords) && strlen($word) > 2;
        });

        // Count word frequencies
        $wordFrequency = array_count_values($words);

        // Sort by frequency
        arsort($wordFrequency);

        // Convert to format suitable for charts
        $result = [];
        foreach ($wordFrequency as $word => $count) {
            $result[] = [
                'word' => $word,
                'count' => $count,
            ];
        }

        return $result;
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
                        ->where('survey_answer_options.question_option_id', $option->id)
                        ->where('survey_answers.question_id', $question->id)
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
                $textSamples = SurveyAnswer::where('question_id', $question->id)
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
