<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Exports\Admin\Report\Goal\GoalPerson;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SaleGoal;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\app\Models\Admin;

class GoalReportController extends Controller
{
    const INDEX_TITLE = 'پرسنل - گزارش هدف های فروش';

    public function index()
    {

        try {
            $title = self::INDEX_TITLE;

            $admins = Admin::query()
                ->orderBy('first_name')
                ->get();

            $adminId = request('admin_id');
            $startDate = request('start_at');
            $endDate = request('end_at');
            $action = request('action');

            $goals = [];

            if (is_numeric($adminId) && isValidDateFormat($startDate) && isValidDateFormat($endDate)) {
                $projects = $this->getPaidProjectsByAdminAndDateRange($adminId, $startDate, $endDate);
                $goals = $this->getAdminGoalsWithTotalSales($adminId, $startDate, $endDate, $projects);
                if ($action === 'excel' && $goals->isNotEmpty()) {
                    return $this->exportToExcel($goals, sprintf('personal_goals_%s_%s.xlsx', $startDate, $endDate));
                }
            }

            return view('admin::admin.goal.report', compact('title', 'admins', 'goals'));
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }
    }

    private function exportToExcel(Collection $goals, string $excelFileName)
    {
        return Excel::download(new GoalPerson($goals), $excelFileName);
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
            ->get();
    }
}
