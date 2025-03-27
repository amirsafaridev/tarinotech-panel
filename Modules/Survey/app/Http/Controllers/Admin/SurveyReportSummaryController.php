<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Traits\HasSurveyQuestionOptionsTrait;
use Modules\Survey\app\Traits\HasSurveyStatsTrait;

class SurveyReportSummaryController extends Controller
{
    use HasJsonCommonResponseTrait;
    use HasSurveyQuestionOptionsTrait;
    use HasSurveyStatsTrait;

    const SUMMARY_TITLE = 'خلاصه نتایج نظرسنجی';

    public function index(Survey $survey)
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
                $optionDistribution = $this->getQuestionOptionDistribution($question);
                $summary['options'] = $optionDistribution['options'];
                $summary['total_selections'] = $optionDistribution['total_selections'];
            }

            // For text questions, get some sample answers
            if ($question->question_type == QuestionTypeEnum::Text) {
                $summary['text_samples'] = $this->getQuestionTextSamples($question);
            }

            // For number questions, calculate summary statistics
            if ($question->question_type == QuestionTypeEnum::Number) {
                $numberAnswers = $question->answers()->whereNotNull('rating_value')->get();

                if ($numberAnswers->isNotEmpty()) {
                    $summary['number_stats'] = [
                        'min' => $numberAnswers->min('rating_value'),
                        'max' => $numberAnswers->max('rating_value'),
                        'avg' => round($numberAnswers->avg('rating_value'), 2),
                        'median' => $this->calculateMedian($numberAnswers->pluck('rating_value')->toArray()),
                    ];

                    // Calculate distribution
                    $summary['number_distribution'] = $this->calculateNumberDistribution($numberAnswers);
                }
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
}
