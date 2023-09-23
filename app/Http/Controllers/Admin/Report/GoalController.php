<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Project;
use App\Models\SaleGoal;
use Exception;
use Illuminate\Support\Collection;

class GoalController extends Controller
{
    public function index()
    {

        try {
            $title = trans('panel.goal-group.index');
            $admins = Admin::query()->orderBy('first_name')->get();

            $adminId = request('admin_id');
            $startDate = request('start_at');
            $endDate = request('end_at');

            $goals = [];

            if (is_numeric($adminId) && isValidDateFormat($startDate) && isValidDateFormat($endDate)) {
                $projects = $this->getPaidProjectsByAdminAndDateRange($adminId, $startDate, $endDate);
                $goals = $this->getAdminGoalsWithTotalSales($adminId, $startDate, $endDate, $projects);
            }

            return view('admin.report.goal.index', compact('title', 'admins', 'goals'));
        } catch (Exception $e) {
            report($e);

            return abort(500);
        }
    }

    /**
     * Get admin goals with total sales.
     */
    public function getAdminGoalsWithTotalSales(int $adminId, string $startDate, string $endDate, Collection $projects): Collection
    {
        // Get the goals matching the criteria
        $goals = SaleGoal::query()
            ->with('type')
            ->where('type_type', Admin::class)
            ->where('type_id', $adminId)
            ->where('start_at', '>=', $startDate)
            ->where('end_at', '<=', $endDate)
            ->orderBy('start_at')
            ->get();

        // Calculate total sales and project counts for each goal
        return $goals->map(function (SaleGoal $goal) use ($projects, $adminId) {
            $data = $goal;
            $salesData = $projects->whereBetween('created_at', [$goal->start_at, $goal->end_at]);

            $data['total_sales'] = $salesData->sum('price');
            $data['project_counts'] = $salesData->count();
            $data['routeProjectParams'] = [
                'admin_id' => $adminId,
                'start_at' => $goal->start_at,
                'end_at' => $goal->end_at,
                'status' => 'pay',
            ];

            return $goal;
        });
    }

    /**
     * Get paid projects by admin and date range.
     */
    public function getPaidProjectsByAdminAndDateRange(int $adminId, string $startDate, string $endDate): Collection
    {
        return Project::query()
            ->where('admin_id', $adminId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pay')
            ->get();
    }
}
