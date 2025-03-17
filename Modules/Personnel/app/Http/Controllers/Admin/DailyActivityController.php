<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Personnel\app\Models\DailyActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class DailyActivityController extends Controller
{
    const INDEX_TITLE = 'فعالیت روزانه';

    public function index()
    {
        $user = auth()->user();

        $title = self::INDEX_TITLE;

        $activities = DailyActivity::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();


        return view(
            'personnel::daily-activity.index',
            compact('title', 'activities')
        );
    }

    public function approveEdit(Request $request, DailyActivity $activity)
    {
        if (!$activity->edit_request || !$activity->edit_request_data) {
            return response()->json([
                'status' => 'error',
                'message' => 'درخواست ویرایشی برای این رکورد وجود ندارد'
            ], 400);
        }

        $editData = $activity->edit_request_data;
        $activityDate = Carbon::parse($activity->date);

        // تبدیل زمان‌های درخواستی به DateTime
        $startTime = Carbon::createFromFormat('H:i', $editData['requested_start_time'])
            ->setDate($activityDate->year, $activityDate->month, $activityDate->day);

        $endTime = Carbon::createFromFormat('H:i', $editData['requested_end_time'])
            ->setDate($activityDate->year, $activityDate->month, $activityDate->day);

        $activity->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_duration' => $endTime->diffInSeconds($startTime),
            'status' => DailyActivity::STATUS_INACTIVE,
            'edit_request' => false,
            'edit_request_data' => array_merge($editData, [
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ])
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'درخواست ویرایش با موفقیت تایید شد'
        ]);
    }

    public function rejectEdit(Request $request, DailyActivity $activity)
    {
        if (!$activity->edit_request || !$activity->edit_request_data) {
            return response()->json([
                'status' => 'error',
                'message' => 'درخواست ویرایشی برای این رکورد وجود ندارد'
            ], 400);
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500'
        ]);

        $activity->update([
            'edit_request' => false,
            'edit_request_data' => array_merge($activity->edit_request_data, [
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'reject_reason' => $request->reject_reason
            ])
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'درخواست ویرایش رد شد'
        ]);
    }
    public function toggle(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::now();
            $isPhysicalDay = false; // فعلا همه روزها غیرحضوری

            // چک کردن آخرین فعالیت امروز
            $lastActivity = DailyActivity::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->latest()
                ->first();

            // اگر فعالیت قبلی نداریم یا آخرین فعالیت پایان یافته، یک فعالیت جدید شروع میکنیم
            if (!$lastActivity || $lastActivity->status === DailyActivity::STATUS_INACTIVE) {
                $activity = DailyActivity::create([
                    'user_id' => $user->id,
                    'date' => $today->toDateString(),
                    'start_time' => $today->toDateTimeString(),
                    'status' => DailyActivity::STATUS_ACTIVE,
                    'is_physical_day' => $isPhysicalDay,
                    'location_data' => $isPhysicalDay ? [
                        'start' => [
                            'lat' => (float) $request->input('latitude'),
                            'lng' => (float) $request->input('longitude')
                        ]
                    ] : null
                ]);

                return response()->json([
                    'status' => 'active',
                    'message' => 'فعالیت جدید شروع شد',
                    'start_time' => $activity->start_time->format('H:i:s')
                ]);
            }

            // اگر فعالیت فعال داریم، آن را پایان میدهیم
            if ($lastActivity->status === DailyActivity::STATUS_ACTIVE) {
                $locationData = $lastActivity->location_data ?? [];
                if ($isPhysicalDay) {
                    $locationData['end'] = [
                        'lat' => (float) $request->input('latitude'),
                        'lng' => (float) $request->input('longitude')
                    ];
                }

                $lastActivity->update([
                    'end_time' => $today->toDateTimeString(),
                    'status' => DailyActivity::STATUS_INACTIVE,
                    'total_duration' => $today->diffInSeconds($lastActivity->start_time),
                    'location_data' => $locationData
                ]);

                return response()->json([
                    'status' => 'inactive',
                    'message' => 'فعالیت پایان یافت',
                    'end_time' => $lastActivity->end_time->format('H:i:s'),
                    'duration' => gmdate('H:i:s', $lastActivity->total_duration)
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('خطا در ثبت فعالیت: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'خطا در ثبت فعالیت. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    public function requestEdit(Request $request, DailyActivity $activity)
    {
        if (!$activity->canRequestEdit()) {
            return response()->json([
                'status' => 'error',
                'message' => 'امکان درخواست ویرایش برای این رکورد وجود ندارد'
            ], 403);
        }

        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:500'
        ]);

        $activity->update([
            'edit_request' => true,
            'edit_request_data' => [
                'requested_start_time' => $request->start_time,
                'requested_end_time' => $request->end_time,
                'reason' => $request->reason,
                'requested_at' => now()
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'درخواست ویرایش با موفقیت ثبت شد'
        ]);
    }

    private function isMobileDevice()
    {
        $userAgent = request()->header('User-Agent');
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent);
    }

    private function isPhysicalDay($user)
    {
        // این متد باید بر اساس تنظیمات سیستم و روزهای حضوری کاربر پیاده‌سازی شود
        // فعلاً به صورت مثال همه روزها رو حضوری در نظر می‌گیریم
        return false;
    }
}
