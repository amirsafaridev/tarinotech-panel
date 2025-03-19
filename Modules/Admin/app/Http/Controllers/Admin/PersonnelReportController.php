<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\app\Models\PersonnelReport;

class PersonnelReportController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'لیست درخواست‌های مرخصی';

    public function index()
    {
        $title = self::INDEX_TITLE;

        // دریافت درخواست‌های مرخصی کاربر با مرتب‌سازی بر اساس تاریخ ایجاد
        $personnelReports = PersonnelReport::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                // اضافه کردن اطلاعات مورد نیاز برای نمایش
                $report->type_text = $report->type === 'daily' ? 'روزانه' : 'ساعتی';
                $report->status_text = match ($report->status) {
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
    public function approve(PersonnelReport $personnelReport)
    {



        $personnelReport->update([
            'diterminant_user_id' => auth()->id(),
            'status' => 'approved',
        ]);

        return $this->successBack(route('admin.admin.personnel-report.index'), 'درخواست ویرایش با موفقیت تایید شد');
    }

    public function reject(PersonnelReport $personnelReport)
    {

        $personnelReport->update([
            'diterminant_user_id' => auth()->id(),
            'status' => 'rejected',
        ]);

        return $this->successBack(route('admin.admin.personnel-report.index'), 'درخواست ویرایش با موفقیت رد شد');
    }
}
