<?php

namespace Modules\Survey\app\Traits;

use Carbon\Carbon;
use DB;
use Modules\Survey\app\Models\Survey;
use Modules\Survey\app\Models\SurveyResponse;

trait HasSurveyResponseTrendTrait
{
    /**
     * Get response trend data (responses over time).
     */
    protected function getResponseTrendData(Survey $survey): array
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
}
