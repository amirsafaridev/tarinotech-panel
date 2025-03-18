<?php

namespace Modules\Admin\app\Services;

use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\DB;

class LeaveRequestValidator
{
    private User $user;
    private array $data;
    private array $errors = [];
    private bool $isValid = true;

    public function __construct(User $user, array $data)
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

        // محاسبه مرخصی‌های روزانه در ماه جاری
        $dailyLeaves = DB::table('personnel_reports')
            ->where('user_id', $this->user->id)
            ->where('type', 'daily')
            ->where('status', 'approved')
            ->whereBetween('start_date', [$currentMonth, $nextMonth])
            ->count();

        // محاسبه مرخصی‌های ساعتی در ماه جاری
        $hourlyLeaves = DB::table('personnel_reports')
            ->where('user_id', $this->user->id)
            ->where('type', 'hourly')
            ->where('status', 'approved')
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->get();

        $totalHourlyHours = $hourlyLeaves->sum(function ($leave) {
            return Carbon::parse($leave->end_time)->diffInHours(Carbon::parse($leave->start_time));
        });

        // محاسبه کل ساعت‌های مرخصی (روزانه + ساعتی)
        $totalLeaveHours = ($dailyLeaves * 9) + $totalHourlyHours;

        if ($this->data['type'] === 'daily') {
            // بررسی محدودیت مرخصی روزانه (2 روز)
            if ($dailyLeaves >= 2) {
                $this->addError('شما در این ماه حداکثر 2 روز مرخصی روزانه می‌توانید داشته باشید.');
            }

            // بررسی محدودیت ترکیبی (18 ساعت)
            if ($totalLeaveHours + 9 > 20) {
                $this->addError('مجموع مرخصی‌های شما در این ماه نمی‌تواند از 20 ساعت تجاوز کند.');
            }
        } else {
            // بررسی محدودیت مرخصی ساعتی (20 ساعت)
            if ($totalLeaveHours + $this->getRequestHours() > 20) {
                $this->addError('مجموع مرخصی‌های شما در این ماه نمی‌تواند از 20 ساعت تجاوز کند.');
            }

            // بررسی تعداد مرخصی‌های ساعتی (4 بار)
            if ($hourlyLeaves->count() >= 4) {
                $this->addError('شما در این ماه حداکثر 4 بار مرخصی ساعتی می‌توانید داشته باشید.');
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