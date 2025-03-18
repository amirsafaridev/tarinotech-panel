<?php

namespace Modules\Admin\app\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PersonnelReport;

class LeaveRequestValidator
{
    private Admin $user;
    private array $data;
    private array $errors = [];
    private bool $isValid = true;

    public function __construct(Admin $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function validate(): array
    {
        // اعتبارسنجی زمان ثبت درخواست
        $this->validateRequestTime();

        // اعتبارسنجی محدودیت‌های ماهانه
        $this->validateMonthlyLimits();

        // اعتبارسنجی محدودیت‌های مرخصی ساعتی
        $this->validateHourlyLimits();

        return [
            'is_valid' => $this->isValid,
            'errors' => $this->errors
        ];
    }

    private function validateRequestTime(): void
    {
        $now = Carbon::now();
        $requestDate = $this->getRequestDate();

        if ($this->data['type'] === 'hourly') {
            // مرخصی کمتر از یک روز - حداقل 24 ساعت قبل
            if ($requestDate->diffInHours($now) < 24) {
                $this->addError('برای مرخصی کمتر از یک روز، باید حداقل 24 ساعت قبل درخواست ثبت شود.');
            }
        } else {
            $startDate = Carbon::parse($this->data['start_date']);
            $endDate = Carbon::parse($this->data['end_date']);
            $daysDiff = $startDate->diffInDays($endDate) + 1;

            if ($daysDiff > 1) {
                // مرخصی بیشتر از یک روز - حداقل 7 روز قبل
                if ($requestDate->diffInDays($now) < 7) {
                    $this->addError('برای مرخصی بیشتر از یک روز، باید حداقل 7 روز کاری قبل درخواست ثبت شود.');
                }
            } else {
                // مرخصی یک روز کامل - حداقل 48 ساعت قبل
                if ($requestDate->diffInHours($now) < 48) {
                    $this->addError('برای مرخصی یک روز کامل، باید حداقل 48 ساعت قبل درخواست ثبت شود.');
                }
            }
        }
    }

    private function validateMonthlyLimits(): void
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $nextMonth = Carbon::now()->endOfMonth();

        // محاسبه مجموع مرخصی‌های تایید شده در ماه جاری
        $monthlyLeave = PersonnelReport::where('user_id', $this->user->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($currentMonth, $nextMonth) {
                $query->whereBetween('start_date', [$currentMonth, $nextMonth])
                    ->orWhereBetween('end_date', [$currentMonth, $nextMonth]);
            })
            ->get();

        $totalHours = 0;
        $totalDailyLeaves = 0;
        $totalHourlyLeaves = 0;

        foreach ($monthlyLeave as $leave) {
            if ($leave->type === 'daily') {
                $totalDailyLeaves += $leave->start_date->diffInDays($leave->end_date) + 1;
                $totalHours += ($leave->start_date->diffInDays($leave->end_date) + 1) * 9; // هر روز 9 ساعت
            } else {
                $totalHourlyLeaves++;
                $totalHours += $leave->total_hours;
            }
        }

        // اعمال محدودیت‌های ماهانه
        if ($this->data['type'] === 'daily') {
            $requestDays = Carbon::parse($this->data['start_date'])->diffInDays(Carbon::parse($this->data['end_date'])) + 1;
            
            // بررسی محدودیت 2 روز مرخصی روزانه در ماه
            if ($totalDailyLeaves + $requestDays > 2) {
                $this->addError('شما نمی‌توانید بیش از 2 روز مرخصی روزانه در ماه داشته باشید.');
            }

            // بررسی محدودیت مجموع ساعت مرخصی (20 ساعت)
            $requestHours = $requestDays * 9;
            if ($totalHours + $requestHours > 20) {
                $this->addError('مجموع مرخصی‌های شما در ماه نمی‌تواند از 20 ساعت بیشتر باشد.');
            }
        } else {
            // بررسی محدودیت تعداد مرخصی‌های ساعتی (4 بار)
            if ($totalHourlyLeaves >= 4) {
                $this->addError('شما نمی‌توانید بیش از 4 بار مرخصی ساعتی در ماه داشته باشید.');
            }

            // بررسی محدودیت مجموع ساعت مرخصی (20 ساعت)
            if ($totalHours + $this->getRequestHours() > 20) {
                $this->addError('مجموع مرخصی‌های شما در ماه نمی‌تواند از 20 ساعت بیشتر باشد.');
            }
        }
    }

    private function validateHourlyLimits(): void
    {
        if ($this->data['type'] === 'hourly') {
            $startTime = Carbon::parse($this->data['start_time']);
            $endTime = Carbon::parse($this->data['end_time']);
            $hoursDiff = $endTime->diffInHours($startTime);

            // بررسی حداقل 3 ساعت
            if ($hoursDiff < 3) {
                $this->addError('هر مرخصی ساعتی باید حداقل 3 ساعت باشد.');
            }

            // بررسی حداکثر 3 ساعت در روز
            if ($hoursDiff > 3) {
                $this->addError('هر مرخصی ساعتی نمی‌تواند بیشتر از 3 ساعت در روز باشد.');
            }
        }
    }

    private function getRequestDate(): Carbon
    {
        if ($this->data['type'] === 'daily') {
            return Carbon::parse($this->data['start_date']);
        }
        return Carbon::parse($this->data['date']);
    }

    private function getRequestHours(): int
    {
        if ($this->data['type'] === 'hourly') {
            return Carbon::parse($this->data['end_time'])->diffInHours(Carbon::parse($this->data['start_time']));
        }
        return 9; // هر روز کامل معادل 9 ساعت
    }

    private function addError(string $message): void
    {
        $this->isValid = false;
        $this->errors[] = $message;
        
    }
} 