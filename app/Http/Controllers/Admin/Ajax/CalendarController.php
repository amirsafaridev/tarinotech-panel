<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ajax\CalcWorkDayRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function calculateWorkDaysWithFreeDays(CalcWorkDayRequest $request)
    {
        try {
            $currentDate = now();
            $totalWorkDays = 0;
            $totalFreeDays = 0;
            $totalFridays = 0;

            if ($request->filled('start_date') && isJalaliDate($request->input('start_date'))) {
                $currentDate = verta($request->input('start_date'))->toCarbon();
            }

            if ($currentDate->greaterThan(now())) {
                throw new Exception('Invalid start date provided');
            }

            $endDate = now()->addDays($request->get('days'));

            $freeDays = DB::table('free_days')->get();

            while ($currentDate->lte($endDate)) {
                $totalWorkDays++;
                $totalFreeDays += $freeDays->filter(function ($item) use ($currentDate) {
                    return str_contains($item->free_at, $currentDate->format('-m-d'));
                })->count();

                if ($currentDate->isFriday()) {
                    $totalFridays++;
                }
                $currentDate = $currentDate->addDay();
            }

            $addFreeDays = $totalFreeDays + $totalFridays;

            $finalDate = $currentDate->addDays($addFreeDays);

            return response()->json([
                'success' => true,
                'total_work_days' => $totalWorkDays,
                'total_free_days' => $addFreeDays,
                'final_date' => $finalDate->format('Y-m-d'),
                'final_date_jalali' => $finalDate->toJalali()->format('Y/m/d'),
                'message' => 'محاسبه شد.',
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
