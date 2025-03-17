<?php

namespace Modules\Dashboard\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Project;
use Modules\Personnel\app\Models\DailyActivity;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
class HomeController extends Controller
{
    public function index()
    {
        // Cache the dashboard data for 3 minutes (180 seconds)
        $data = cache()->remember('dashboard', 180, function () {
            $data['admins_count'] = Admin::count();

            $projects = Project::select('id', 'base_id')->get();

            $data['project_web_count'] = $projects->where('base_id', ProjectBase::Web)->count();
            $data['project_seo_count'] = $projects->where('base_id', ProjectBase::Seo)->count();
            $data['project_ads_count'] = $projects->where('base_id', ProjectBase::Ads)->count();

            return $data;
        });

        $title = trans('panel.dashboard.title');
        $user = auth()->user();
        $today = Carbon::now()->format('Y-m-d');
        
        $currentActivity = DailyActivity::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
        // Convert current time to Jalali
        $now = Verta::now();
        $persianDayOfWeek = $now->formatWord('l');
        $persianDate = $now->format('Y/m/d');
        $currentTime = $now->format('H:i:s');
        $isPhysicalDay = false;

        return view('dashboard::admin.index', compact('title', 'data','persianDayOfWeek','persianDate','currentTime','currentActivity','isPhysicalDay'));
    }

    public function redirect()
    {
        return Redirect::route('admin.dashboard.index');
    }
}
