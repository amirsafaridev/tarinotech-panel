<?php

namespace Modules\Survey\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Illuminate\View\View;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyResponse;
use Modules\Survey\app\Traits\HasSurveyQuestionOptionsTrait;
use Modules\Survey\app\Traits\HasSurveyResponseTrendTrait;
use Modules\Survey\app\Traits\HasSurveyStatsTrait;

class SurveyReportChartController extends Controller
{
    use HasJsonCommonResponseTrait;
    use HasSurveyQuestionOptionsTrait;
    use HasSurveyResponseTrendTrait;
    use HasSurveyStatsTrait;

    const CHARTS_TITLE = 'نمودارهای نظرسنجی';

    /**
     * Display chart visualizations for survey data.
     *
     * @return View
     */
    public function index(Survey $survey)
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
        $choiceDistribution = $this->getChoiceDistribution($survey);

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
     * Get responses distribution by hour of day.
     *
     * @return array
     */
    private function getResponsesByHour(Survey $survey)
    {
        $responsesByHour = SurveyResponse::select([
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count'),
        ])
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
}
