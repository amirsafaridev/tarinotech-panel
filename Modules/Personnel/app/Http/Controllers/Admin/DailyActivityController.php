<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Personnel\app\Models\DailyActivity;
use Illuminate\Http\Request;


class DailyActivityController extends Controller
{
    const INDEX_TITLE = 'فعالیت روزانه';

    public function index()
    {
        $user = auth()->user();
      
       $title= self::INDEX_TITLE;
      
        $activities = DailyActivity::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
            ->get();

        return view('personnel::daily-activity.index',
        compact('title', 'activities'));
    }

    
    public function toggle(Request $request)
    {
        try {
            $user = auth()->user();
            $today = now();
            $isPhysicalDay = $this->isPhysicalDay($user); // فعلا همه روزها غیرحضوری

            // چک کردن آخرین فعالیت امروز
            $lastActivity = DailyActivity::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->latest()
                ->first();

            // اگر فعالیت قبلی نداریم یا آخرین فعالیت پایان یافته، یک فعالیت جدید شروع میکنیم
            if (!$lastActivity || $lastActivity->status === DailyActivity::STATUS_INACTIVE ||  $lastActivity->status === DailyActivity::STATUS_REJECT) {
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
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'required'
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
            'result' => 'success',
            'back' => route('admin.admin.daily-activity.index'),
            'message' => trans('panel.success_update'),
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