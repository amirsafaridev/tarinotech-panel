<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Modules\Personnel\app\Http\Requests\Admin\PersonnelReport\StoreRequest;
use Modules\Personnel\app\Http\Requests\Admin\PersonnelReport\UpdateRequest;
use Illuminate\Support\Facades\DB;

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
    const EDIT_TITLE = 'ویرایش درخواست مرخصی';
    public function index()
    {
        $title = self::INDEX_TITLE;
        
        // دریافت درخواست‌های مرخصی کاربر با مرتب‌سازی بر اساس تاریخ ایجاد
        $personnelReports = PersonnelReport::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                // اضافه کردن اطلاعات مورد نیاز برای نمایش
                $report->type_text = $report->type === 'daily' ? 'روزانه' : 'ساعتی';
                $report->status_text = match($report->status) {
                    'pending' => 'در انتظار تایید',
                    'approved' => 'تایید شده',
                    'rejected' => 'رد شده',
                    default => 'نامشخص'
                };
                
                // محاسبه مدت زمان مرخصی
                if ($report->type === 'daily') {
                    $report->duration = $report->start_date->diffInDays($report->end_date) + 1 . ' روز';
                } else {
                    $report->duration = $report->total_hours . ' ساعت';
                }

                // تبدیل تاریخ‌ها به فرمت شمسی
                $report->created_at_formatted = verta($report->created_at)->format('Y/m/d H:i');
                
                if ($report->type === 'daily') {
                    $report->leave_date = verta($report->start_date)->format('Y/m/d') . ' تا ' . 
                                        verta($report->end_date)->format('Y/m/d');
                } else {
                    $report->leave_date = verta($report->date)->format('Y/m/d') . ' از ' . 
                                        $report->start_time . ' تا ' . $report->end_time;
                }

                return $report;
            });

        return view('personnel::admin.personnel-report.index', compact('title', 'personnelReports'));
    }
    public function leaveCalender(Request $request)
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
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhereBetween('date', [$startDate, $endDate]);
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

        return view('personnel::admin.personnel-report.leave-calender', [
            'title' => 'تقویم مرخصی‌ها',
            'weeksOfMonth' => $weeksOfMonth,
            'currentMonth' => $currentDate->format('%B %Y'),
            'prevMonthUrl' => route('admin.personnel.personnel-report.leave-calender', ['month' => 'prev']),
            'nextMonthUrl' => route('admin.personnel.personnel-report.leave-calender', ['month' => 'next']),
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

    public function edit(PersonnelReport $personnelReport)
    {
        if ($personnelReport->user_id !== Auth::id()) {
            return $this->errorResponse('شما دسترسی به این درخواست را ندارید');
        }

        if ($personnelReport->status !== 'pending') {
            return $this->errorResponse('این درخواست قابل ویرایش نیست');
        }

        $title = self::EDIT_TITLE;
        return view('personnel::admin.personnel-report.edit', compact('title', 'personnelReport'));
    }

    public function update(UpdateRequest $request, PersonnelReport $personnelReport)
    {
        try {
            if ($personnelReport->user_id !== Auth::id()) {
                return response()->json([
                    'result' => 'warning',
                    'message' => 'شما دسترسی به این درخواست را ندارید'
                ]);
            }

            if ($personnelReport->status !== 'pending') {
                return response()->json([
                    'result' => 'warning',
                    'message' => 'این درخواست قابل ویرایش نیست'
                ]);
            }

            DB::beginTransaction();
            
            // اعتبارسنجی درخواست
            $validator = new LeaveRequestValidator(Auth::user(), $request->validated());
            $validationResult = $validator->validate();

            if (!$validationResult['is_valid']) {
                      return response()->json([
                    'result' => 'warning',
                    'message' => $validationResult['errors'][0]
                ]);
            }

            $inputs = $request->validated();

            // تبدیل تاریخ‌ها به فرمت مناسب
            if ($inputs['type'] === 'daily') {
                $inputs['start_date'] = verta()->parse($inputs['start_date'])->toCarbon()->startOfDay();
                $inputs['end_date'] = verta()->parse($inputs['end_date'])->toCarbon()->endOfDay();

                // تنظیم فیلدهای مرخصی ساعتی به null
                $inputs['date'] = null;
                $inputs['start_time'] = null;
                $inputs['end_time'] = null;
            } else {
                $inputs['date'] = verta()->parse($inputs['date'])->toCarbon()->startOfDay();

                // تنظیم فیلدهای مرخصی روزانه به null
                $inputs['start_date'] = null;
                $inputs['end_date'] = null;
            }

            $personnelReport->update($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.personnel.personnel-report.index'),
                'message' =>'درخواست مرخصی با موفقیت بروزرسانی شد',
            ]);

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(PersonnelReport $personnelReport)
    {
        try {
            if ($personnelReport->user_id !== Auth::id()) {
                return $this->errorResponse('شما دسترسی به این درخواست را ندارید');
            }

            if ($personnelReport->status !== 'pending') {
                return $this->errorResponse('این درخواست قابل حذف نیست');
            }

            $personnelReport->delete();
            return $this->successDestroyBack(route('admin.personnel.personnel-report.index'));

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
