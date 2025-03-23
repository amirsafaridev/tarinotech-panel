<?php

namespace Modules\Survey\app\Traits;

use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyAnswer;

trait HasSurveyStatsTrait
{
    /**
     * Get basic statistics for a survey.
     */
    protected function getBasicStats(Survey $survey): array
    {
        $totalResponses = $survey->responses()->count();
        $totalQuestions = $survey->questions()->count();

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
            'first_response_date' => $firstResponse?->created_at,
            'last_response_date' => $lastResponse?->created_at,
            'question_response_rates' => $questionResponseRates,
        ];
    }
}
