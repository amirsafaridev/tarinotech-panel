<?php

namespace Modules\Ajax\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\DB;
use Modules\Ajax\app\Http\Requests\CalcWorkDayRequest;

class CalendarController extends Controller
{
    public function calcFreeDays(CalcWorkDayRequest $request)
    {
        try {
            $totalWorkDays = 0;
            $totalFreeDays = 0;

            $currentDate = Verta::parse($request->input('start_date'))->toCarbon();

            if ($currentDate->greaterThan(now())) {
                throw new Exception('Invalid start date provided');
            }

            if ($request->has('end_date')) {
                $endDate = Verta::parse($request->input('end_date'))->toCarbon();

            } else {
                $endDate = $currentDate->copy()->addDays($request->get('days'));
            }

            $freeDays = DB::table('free_days')->get();

            while ($currentDate->lte($endDate)) {
                $totalWorkDays++;

                $exist = $freeDays->where('free_at', $currentDate->format('Y-m-d'))->first();
                if ($exist) {
                    $totalFreeDays++;
                }
                $currentDate = $currentDate->addDay();
            }

            $finalDate = $currentDate->addDays($totalFreeDays);

            return response()->json([
                'success' => true,
                'total_work_days' => $totalWorkDays,
                'total_free_days' => $totalFreeDays,
                'total' => $totalFreeDays + $totalWorkDays,
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
