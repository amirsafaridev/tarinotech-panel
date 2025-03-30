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

        // Load question with its answers
        $question->load(['answers']);

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

        // Handling different question types
        switch ($question->question_type) {
            // Number type specific analysis
            case QuestionTypeEnum::Number:
                $numberAnswers = $question->answers()->whereNotNull('rating_value')->get();

                $questionData['number_stats'] = [
                    'min' => $numberAnswers->min('rating_value'),
                    'max' => $numberAnswers->max('rating_value'),
                    'avg' => round($numberAnswers->avg('rating_value'), 2),
                    'median' => $this->calculateMedian($numberAnswers->pluck('rating_value')->toArray()),
                ];

                // Optional settings from the question
                $questionData['settings'] = $question->settings ?? [];

                // Distribution of numbers
                $questionData['number_distribution'] = $this->calculateNumberDistribution($numberAnswers);

                break;

            case QuestionTypeEnum::Single:
            case QuestionTypeEnum::Multiple:
                // Existing choice question implementation
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

                // Monthly responses trend
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
                break;

            case QuestionTypeEnum::Text:
            case QuestionTypeEnum::ShortText:
                // Existing text question implementation
                $textAnswers = SurveyAnswer::where('survey_question_id', $question->id)
                    ->whereNotNull('answer_text')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                $questionData['text_answers'] = $textAnswers;
                $questionData['settings'] = $question->settings ?? [];

                // Get text statistics
                $allTextAnswers = SurveyAnswer::where('survey_question_id', $question->id)
                    ->whereNotNull('answer_text')
                    ->pluck('answer_text')
                    ->toArray();

                if (! empty($allTextAnswers)) {
                    $wordCounts = array_map(function ($text) {
                        return count(preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY));
                    }, $allTextAnswers);

                    $questionData['text_stats'] = [
                        'avg_words' => round(array_sum($wordCounts) / count($wordCounts), 1),
                        'max_words' => max($wordCounts),
                        'min_words' => min($wordCounts),
                    ];
                }

                // Get word frequency for text analysis
                $wordFrequency = $this->analyzeTextResponses($question);
                $questionData['word_frequency'] = array_slice($wordFrequency, 0, 20);
                break;
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
     * Calculate median of a number array
     */
    private function calculateMedian(array $numbers): ?float
    {
        if (empty($numbers)) {
            return null;
        }

        sort($numbers);
        $count = count($numbers);
        $middle = floor(($count - 1) / 2);

        if ($count % 2 == 0) {
            return ($numbers[$middle] + $numbers[$middle + 1]) / 2;
        }

        return $numbers[$middle];
    }

    /**
     * Calculate distribution of numbers
     */
    private function calculateNumberDistribution($numberAnswers)
    {
        if ($numberAnswers->isEmpty()) {
            return [];
        }

        $numbers = $numberAnswers->pluck('rating_value');
        $min = $numbers->min();
        $max = $numbers->max();
        $range = $max - $min;

        // Create 5 buckets for distribution
        $bucketCount = 5;
        $bucketSize = $range / $bucketCount;

        $distribution = [];
        for ($i = 0; $i < $bucketCount; $i++) {
            $lowerBound = $min + ($i * $bucketSize);
            $upperBound = $min + (($i + 1) * $bucketSize);

            $count = $numbers->filter(function ($value) use ($lowerBound, $upperBound) {
                return $value >= $lowerBound && $value < $upperBound;
            })->count();

            $distribution[] = [
                'range' => sprintf('%.2f - %.2f', $lowerBound, $upperBound),
                'count' => $count,
                'percentage' => round(($count / $numberAnswers->count()) * 100, 1),
            ];
        }

        return $distribution;
    }

    /**
     * Analyze text responses for a question.
     *
     * @return array
     */
    private function analyzeTextResponses(SurveyQuestion $question)
    {
        // Existing text analysis implementation remains the same
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
