<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\PersonnelReport\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\PersonnelReport\UpdateRequest;
use Modules\Admin\app\Services\LeaveRequestValidator;
use Exception;
use Modules\Admin\app\Models\PersonnelReport;
use Carbon\Carbon;

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

        return view('admin::admin.personnel-report.index', compact('title', 'personnelReports'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;
        return view('admin::admin.personnel-report.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // اعتبارسنجی درخواست
            $validator = new LeaveRequestValidator(Auth::user(), $request->validated());
            $validationResult = $validator->validate();

            if (!$validationResult['is_valid']) {
                return $this->errorResponse(implode('<br>', $validationResult['errors']));
            }

            $inputs = $request->validated();
            $inputs['user_id'] = Auth::id();
            $inputs['status'] = 'pending';

            // تبدیل تاریخ‌ها به فرمت مناسب
            if ($inputs['type'] === 'daily') {
                $inputs['start_date'] = verta()->parse($inputs['start_date'])->toCarbon();
                $inputs['end_date'] = verta()->parse($inputs['end_date'])->toCarbon();
            } else {
                $inputs['date'] = verta()->parse($inputs['date'])->toCarbon();
                $inputs['start_time'] = $inputs['start_time'];
                $inputs['end_time'] = $inputs['end_time'];
            }

            PersonnelReport::create($inputs);
            DB::commit();

            return $this->successResponse('درخواست مرخصی با موفقیت ثبت شد');

        } catch (Exception $exception) {
            DB::rollBack();
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
        return view('admin::admin.personnel-report.edit', compact('title', 'personnelReport'));
    }

    public function update(UpdateRequest $request, PersonnelReport $personnelReport)
    {
        try {
            if ($personnelReport->user_id !== Auth::id()) {
                return $this->errorResponse('شما دسترسی به این درخواست را ندارید');
            }

            if ($personnelReport->status !== 'pending') {
                return $this->errorResponse('این درخواست قابل ویرایش نیست');
            }

            DB::beginTransaction();

            // اعتبارسنجی درخواست
            $validator = new LeaveRequestValidator(Auth::user(), $request->validated());
            $validationResult = $validator->validate();

            if (!$validationResult['is_valid']) {
                return $this->errorResponse(implode('<br>', $validationResult['errors']));
            }

            $inputs = $request->validated();

            // تبدیل تاریخ‌ها به فرمت مناسب
            if ($inputs['type'] === 'daily') {
                $inputs['start_date'] = verta()->parse($inputs['start_date'])->toCarbon();
                $inputs['end_date'] = verta()->parse($inputs['end_date'])->toCarbon();
            } else {
                $inputs['date'] = verta()->parse($inputs['date'])->toCarbon();
                $inputs['start_time'] = $inputs['start_time'];
                $inputs['end_time'] = $inputs['end_time'];
            }

            $personnelReport->update($inputs);
            DB::commit();

            return $this->successResponse('درخواست مرخصی با موفقیت بروزرسانی شد');

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
            return $this->successResponse('درخواست مرخصی با موفقیت حذف شد');

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
