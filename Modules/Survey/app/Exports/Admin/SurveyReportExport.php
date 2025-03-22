<?php

namespace Modules\Survey\app\Exports\Admin;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Survey\app\Models\Survey;

class SurveyReportExport implements WithMultipleSheets
{
    protected Survey $survey;

    protected array $stats;

    protected array $questionData;

    public function __construct(Survey $survey, array $stats, array $questionData)
    {
        $this->survey = $survey;
        $this->stats = $stats;
        $this->questionData = $questionData;
    }

    public function sheets(): array
    {
        $sheets = [
            new SurveyOverviewSheet($this->survey, $this->stats),
            new SurveyQuestionsSheet($this->survey, $this->stats, $this->questionData),
            new SurveyResponsesSheet($this->survey),
        ];

        // Add individual question sheets for each question
        foreach ($this->questionData as $index => $question) {
            $sheets[] = new QuestionDetailSheet($this->survey, $question, $index + 1);
        }

        return $sheets;
    }
}
