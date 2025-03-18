<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Modules\Personnel\app\Http\Requests\Admin\PersonnelReport\StoreRequest;
use Modules\Admin\app\Services\LeaveRequestValidator;
use Exception;
use Modules\Admin\app\Models\PersonnelReport;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class PersonnelReportController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'لیست درخواست‌های مرخصی';
    const CREATE_TITLE = 'ثبت درخواست مرخصی';

    public function index(Request $request)
    {
        // تنظیم تاریخ جاری با در نظر گرفتن ماه قبل/بعد
        $currentDate = verta();

        if ($request->has('month')) {
            if ($request->month === 'prev') {
                $currentDate = $currentDate->subMonth();
            } elseif ($request->month === 'next') {
                $currentDate = $currentDate->addMonth();
            }
        }

        // محاسبه اولین و آخرین روز ماه
        $startOfMonth = clone $currentDate;
        $startOfMonth->startMonth();
        
        $endOfMonth = clone $currentDate;
        $endOfMonth->endMonth();

        // محاسبه شنبه اولین هفته ماه
        $firstDayOfWeek = $startOfMonth->dayOfWeek;
        $startOfWeek = clone $startOfMonth;
        if ($firstDayOfWeek > 0) {
            $startOfWeek->subDays($firstDayOfWeek);
        }

        // محاسبه جمعه آخرین هفته ماه
        $lastDayOfWeek = $endOfMonth->dayOfWeek;
        $endOfWeek = clone $endOfMonth;
        if ($lastDayOfWeek < 6) {
            $endOfWeek->addDays(6 - $lastDayOfWeek);
        }

      

        // تبدیل تاریخ‌های شمسی به میلادی برای کوئری
        $startDate = $startOfWeek->toCarbon()->startOfDay();
        $endDate = $endOfWeek->toCarbon()->endOfDay();


        // دریافت مرخصی‌های تایید شده در بازه زمانی
        $approvedLeaves = PersonnelReport::with('user')
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->get();

       
        // گروه‌بندی مرخصی‌ها بر اساس تاریخ
        $groupedLeaves = collect();
        foreach ($approvedLeaves as $leave) {
            $startDateVerta = verta($leave->start_date);
            $endDateVerta = verta($leave->end_date);
            
            // برای مرخصی‌های روزانه که بیش از یک روز هستند
            $currentDateLoop = clone $startDateVerta;
            while ($currentDateLoop <= $endDateVerta) {
                $dateKey = $currentDateLoop->format('Y/n/j');
                if (!$groupedLeaves->has($dateKey)) {
                    $groupedLeaves[$dateKey] = collect();
                }
                $groupedLeaves[$dateKey]->push($leave);
                $currentDateLoop->addDay();
            }
        }

        // تنظیم روزهای ماه
        $weekDays = [];
        $currentDay = clone $startOfWeek;
        $weeksOfMonth = [];
        $currentWeek = [];
        
        while ($currentDay <= $endOfWeek) {
            $dateKey = $currentDay->format('Y/n/j');
            $dayData = [
                'date' => clone $currentDay,
                'leaves' => $groupedLeaves->get($dateKey, collect()),
                'isHoliday' => $this->isHoliday($currentDay),
                'isCurrentMonth' => $currentDay->month === $currentDate->month
            ];
            
            $currentWeek[] = $dayData;
            
            if (count($currentWeek) === 7) {
                $weeksOfMonth[] = $currentWeek;
                $currentWeek = [];
            }
            
            $currentDay->addDay();
        }
        
        if (!empty($currentWeek)) {
            $weeksOfMonth[] = $currentWeek;
        }

        return view('personnel::admin.personnel-report.index', [
            'title' => 'تقویم مرخصی‌ها',
            'weeksOfMonth' => $weeksOfMonth,
            'currentMonth' => $currentDate->format('%B %Y'),
            'prevMonthUrl' => route('admin.personnel.personnel-report.index', ['month' => 'prev']),
            'nextMonthUrl' => route('admin.personnel.personnel-report.index', ['month' => 'next']),
        ]);
    }


    /**
     * Check if the given date is a holiday
     * 
     * @param Verta $date
     * @return bool
     */
    private function isHoliday(Verta $date)
    {
        // در Verta:
        // شنبه = 0
        // یکشنبه = 1
        // دوشنبه = 2
        // سه‌شنبه = 3
        // چهارشنبه = 4
        // پنج‌شنبه = 5
        // جمعه = 6
        return $date->dayOfWeek == 5 || $date->dayOfWeek == 6;
    }

    public function create()
    {
        $title = self::CREATE_TITLE;
        return view('personnel::admin.personnel-report.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['status'] = 'pending';

            // تبدیل تاریخ‌های شمسی به میلادی
            if ($data['type'] === 'daily') {
                $data['start_date'] = verta()->parse($data['start_date'])->toCarbon()->startOfDay();
                $data['end_date'] = verta()->parse($data['end_date'])->toCarbon()->endOfDay();
                $data['date'] = null;
                $data['start_time'] = null;
                $data['end_time'] = null;
            } else {
                $data['date'] = verta()->parse($data['date'])->toCarbon()->startOfDay();
                $data['start_date'] = null;
                $data['end_date'] = null;
            }

            // اعتبارسنجی قوانین مرخصی
            $validator = new LeaveRequestValidator(auth()->user(), $data);
            $validationResult = $validator->validate();

            if (!$validationResult['is_valid']) {
                return response()->json([
                    'result' => 'warning',
                    'message' => $validationResult['errors'][0]
                ]);
            }

            $report = PersonnelReport::create($data);

            return response()->json([
                'result' => 'success',
                'back' => route('admin.personnel.personnel-report.index'),
                'message' =>'درخواست مرخصی با موفقیت ثبت شد',
            ]);
            
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

   
}
