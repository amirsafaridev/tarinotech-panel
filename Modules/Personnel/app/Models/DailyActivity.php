<?php

namespace Modules\Personnel\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\app\Models\Admin;
use Carbon\Carbon;

class DailyActivity extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_INCORRECT = 'incorrect_entry';
    const STATUS_ABSENT = 'absent';
    const STATUS_REJECT = 'reject';

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'total_duration',
        'status',
        'is_physical_day',
        'location_data',
        'edit_request',
        'edit_request_data'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_duration' => 'integer',
        'is_physical_day' => 'boolean',
        'location_data' => 'json',
        'edit_request' => 'boolean',
        'edit_request_data' => 'json'
    ];

    protected $attributes = [
        'status' => self::STATUS_INACTIVE,
        'is_physical_day' => false,
        'edit_request' => false
    ];

    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Tehran') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Tehran') : null;
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value ? Carbon::parse($value)->timezone('Asia/Tehran') : null;
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value ? Carbon::parse($value)->timezone('Asia/Tehran') : null;
    }

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

    public function canRequestEdit()
    {
        return !$this->edit_request && $this->edit_request_data === null && $this->status !== self::STATUS_ABSENT;
    }

    public function markAsIncorrect()
    {
        $this->update([
            'status' => self::STATUS_INCORRECT,
            'total_duration' => null
        ]);
    }

    public static function createAbsentRecord($userId, $date)
    {
        return self::create([
            'user_id' => $userId,
            'date' => $date,
            'status' => self::STATUS_ABSENT
        ]);
    }

    public function isLocationValid($latitude, $longitude)
    {
        if (!$this->is_physical_day) {
            return true;
        }

        // محدوده مجاز شرکت - این مقادیر باید از تنظیمات خوانده شوند
        $officeLat = config('personnel.office.latitude', 0);
        $officeLng = config('personnel.office.longitude', 0);
        $maxDistance = config('personnel.office.max_distance', 0.5); // کیلومتر

        return $this->calculateDistance($latitude, $longitude, $officeLat, $officeLng) <= $maxDistance;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // کیلومتر

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            // اگر تاریخ تنظیم نشده باشد، تاریخ امروز را قرار میدهیم
            if (!$activity->date) {
                $activity->date = now()->toDateString();
            }

            // اگر وضعیت تنظیم نشده باشد، وضعیت پیش‌فرض را قرار میدهیم
            if (!$activity->status) {
                $activity->status = self::STATUS_INACTIVE;
            }

            // اگر این رکورد خودش یک رکورد غیبت نیست، برای روز قبل چک میکنیم
            if ($activity->status !== self::STATUS_ABSENT) {
                $yesterday = Carbon::yesterday();
                $hasYesterdayRecord = self::where('user_id', $activity->user_id)
                    ->whereDate('date', $yesterday)
                    ->exists();

                if (!$hasYesterdayRecord) {
                    // ایجاد رکورد غیبت برای روز قبل
                    self::create([
                        'user_id' => $activity->user_id,
                        'date' => $yesterday->toDateString(),
                        'status' => self::STATUS_ABSENT,
                        'is_physical_day' => false
                    ]);
                }
            }
        });

        static::saving(function ($activity) {
            // اگر end_time نداشته باشیم و status فعال نباشد، وضعیت را incorrect می‌کنیم
            if ($activity->status === self::STATUS_INACTIVE && !$activity->end_time) {
                $activity->status = self::STATUS_INCORRECT;
            }
        });
    }
}
