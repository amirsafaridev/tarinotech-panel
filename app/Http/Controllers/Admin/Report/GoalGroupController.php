<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\Admin\Report\Goal\GoalGroup;
use App\Http\Controllers\Controller;
use App\Models\SaleGoal;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\app\Models\Project;

class GoalGroupController extends Controller
{
    public function index()
    {

        try {
            $title = 'گزارش اهداف گروهی';

            $startDate = request('start_at', '');
            $endDate = request('end_at', '');
            $action = request('action');

            $goals = [];

            if (isValidDateFormat($startDate) && isValidDateFormat($endDate)) {
                $projects = $this->getPaidProjectsRange($startDate, $endDate);
                $goals = $this->getGroupGoalsWithTotalSales($startDate, $endDate, $projects);

                if ($action === 'excel' && $goals->isNotEmpty()) {
                    return $this->exportToExcel($goals, sprintf('group_goals_%s_%s.xlsx', $startDate, $endDate));
                }
            }

            return view('admin.report.goal-group.index', compact('title', 'goals'));
        } catch (Exception $e) {
            report($e);

            return abort(500);
        }
    }

    private function exportToExcel(Collection $goals, string $excelFileName)
    {
        return Excel::download(new GoalGroup($goals), $excelFileName);
    }

    /**
     * Get group goals with total sales.
     */
    public function getGroupGoalsWithTotalSales(string $startDate, string $endDate, Collection $projects): Collection
    {
        // Get the goals matching the criteria
        $goals = SaleGoal::query()
            ->with('type')
            ->whereNull(['type_type', 'type_id'])
            ->where('start_at', '>=', $startDate)
            ->where('end_at', '<=', $endDate)
            ->orderBy('start_at')
            ->get();

        // Calculate total sales and project counts for each goal
        return $goals->map(function (SaleGoal $goal) use ($projects) {
            $data = $goal;
            $salesData = $projects->whereBetween('created_at', [$goal->start_at, $goal->end_at]);

            $data['total_sales'] = $salesData->sum('price');
            $data['project_counts'] = $salesData->count();
            $data['routeProjectParams'] = [
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
    public function getPaidProjectsRange(string $startDate, string $endDate): Collection
    {
        return Project::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pay')
            ->get();
    }
}
