<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Personnel\app\Models\DailyActivity;
use Illuminate\Http\Request;

class MonthlyActivityController extends Controller
{
    const INDEX_TITLE = 'فعالیت روزانه';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = verta();
        
        if ($request->has('month')) {
            if ($request->month === 'prev') {
                $date = $date->subMonth();
            } elseif ($request->month === 'next') {
                $date = $date->addMonth();
            }
        }

        $startOfMonth = $date->startMonth()->toCarbon();
        $endOfMonth = $date->endMonth()->toCarbon();

        // Get all users with their monthly activities
        $monthlyActivities = DailyActivity::with('user')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy('user_id')
            ->map(function ($activities) {
                $user = $activities->first()->user;
                $totalHours = $activities->sum('total_duration') / 3600;
                $averageDailyHours = $totalHours / $activities->count();
                
                // Calculate overtime (more than 8 hours per day)
                $overtimeMinutes = $activities->sum(function ($activity) {
                    $hours = $activity->total_duration / 3600;
                    return $hours > 8 ? ($hours - 8) * 60 : 0;
                });

                // Calculate late arrivals (more than 15 minutes after 9 AM)
                $lateArrivals = $activities->filter(function ($activity) {
                    return $activity->start_time && 
                           $activity->start_time->hour >= 9 && 
                           $activity->start_time->minute > 15;
                })->count();

                // Count incomplete activities
                $incompleteActivities = $activities->filter(function ($activity) {
                    return $activity->status === 'incorrect_entry';
                })->count();

                return [
                    'user' => $user,
                    'average_daily_hours' => round($averageDailyHours, 2),
                    'total_monthly_hours' => round($totalHours, 2),
                    'overtime_minutes' => round($overtimeMinutes),
                    'late_arrivals' => $lateArrivals,
                    'incomplete_activities' => $incompleteActivities,
                    'activities' => $activities
                ];
            });
        return view('admin::admin.monthly-activity.index', [
            'title' => 'گزارش فعالیت ماهانه',
            'monthlyActivities' => $monthlyActivities,
            'current_month' => $date->format('%B %Y'),
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth
        ]);
    }

    

    public function dailyDetails($userId,$startOfMonth, $endOfMonth)
    {
        $title= self::INDEX_TITLE;

        $activities = DailyActivity::with('user')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->get();

        return view('admin::admin.monthly-activity.daily-details', compact('activities','title'));
    }
}
