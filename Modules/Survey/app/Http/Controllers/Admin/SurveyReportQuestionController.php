<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Illuminate\View\View;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyQuestion;
use Modules\Survey\app\Traits\HasSurveyStatsTrait;

class SurveyReportQuestionController extends Controller
{
    use HasJsonCommonResponseTrait;
    use HasSurveyStatsTrait;

    const QUESTION_TITLE = 'گزارش سوال';

    /**
     * Display detailed report for a specific question.
     *
     * @return View
     */
    public function index(Survey $survey, SurveyQuestion $question)
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
            $textAnswers = SurveyAnswer::where('survey_question_id', $question->id)
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
     * Analyze text responses for a question.
     *
     * @return array
     */
    private function analyzeTextResponses(SurveyQuestion $question)
    {
        // Get all text answers
        $textAnswers = SurveyAnswer::where('survey_question_id', $question->id)
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
}
