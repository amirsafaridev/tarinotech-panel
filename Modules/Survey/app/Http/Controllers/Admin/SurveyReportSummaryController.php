<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\View\View;
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

    /**
     * Display summary of the survey results.
     *
     * @return View
     */
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

            $questionsSummary[] = $summary;
        }

        return view('survey::admin.report.summary', compact(
            'title',
            'survey',
            'stats',
            'questionsSummary'
        ));
    }
}
