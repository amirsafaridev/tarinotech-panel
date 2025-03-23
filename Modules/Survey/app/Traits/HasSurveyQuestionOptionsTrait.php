<?php

namespace Modules\Survey\app\Traits;

use DB;
use Illuminate\Support\Collection;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;
use Modules\Survey\app\Models\SurveyQuestion;

trait HasSurveyQuestionOptionsTrait
{
    /**
     * Get distribution data for multiple choice questions.
     */
    protected function getChoiceDistribution(Survey $survey): array
    {
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
                    ->where('survey_answer_options.survey_question_option_id', $option->id)
                    ->where('survey_answers.survey_question_id', $question->id)
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

        return $choiceDistribution;
    }

    /**
     * Get option distribution for a single question.
     */
    protected function getQuestionOptionDistribution(SurveyQuestion $question): array
    {
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

        return [
            'options' => $optionCounts,
            'total_selections' => $totalSelections,
        ];
    }

    /**
     * Get text answers for a question.
     */
    protected function getQuestionTextSamples(SurveyQuestion $question, int $limit = 5): array
    {
        return SurveyAnswer::where('survey_question_id', $question->id)
            ->whereNotNull('answer_text')
            ->orderBy(DB::raw('RAND()'))
            ->take($limit)
            ->pluck('answer_text')
            ->toArray();
    }

    /**
     * Get question completion rates.
     */
    protected function getQuestionCompletionRates(Survey $survey): Collection
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
}
