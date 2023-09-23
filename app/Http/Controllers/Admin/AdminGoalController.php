<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Goal\SaveRequest;
use App\Models\Admin;
use App\Models\SaleGoal;
use Exception;

class AdminGoalController extends Controller
{
    public function index(Admin $admin)
    {

        try {
            $title = trans('panel.admin_goal.index');
            $vertaRangeDateCollection = Helper::vertaRangeDateByMonth(6);

            // Get admin record for prepare old value
            $fromDate = Helper::vertaInstanceToGregorian($vertaRangeDateCollection->get('from_at'));
            $toDate = Helper::vertaInstanceToGregorian($vertaRangeDateCollection->get('to_at'));
            $olds = SaleGoal::query()
                ->whereDate('start_at', '>=', $fromDate)
                ->whereDate('end_at', '<=', $toDate)
                ->where('type_id', $admin->id)
                ->where('type_type', Admin::class)
                ->get();

            // Check date in generated dates by verta
            $dateItems = Helper::checkDateInGeneratedDatesByVerta($vertaRangeDateCollection, $olds);

            return view('admin.admin_goal.index', compact('title', 'admin', 'dateItems'));

        } catch (Exception $e) {
            report($e);

            return to_route('admin.admin.goal', $admin->id)->with('error', $e->getMessage());
        }

    }

    public function save(SaveRequest $request, Admin $admin)
    {
        try {

            $admin->goals()->updateOrCreate([
                'start_at' => $request->input('start'),
                'end_at' => $request->input('end'),
            ], [
                'profitability' => $request->input('profitability'),
                'profitability_dollar' => $request->input('profitability_dollar'),
            ]);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
