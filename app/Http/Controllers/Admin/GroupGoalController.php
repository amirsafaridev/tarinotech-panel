<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SaleGoal;
use Exception;
use Modules\Admin\app\Http\Requests\Admin\SaveRequest;

class GroupGoalController extends Controller
{
    public function index()
    {

        try {
            $title = trans('panel.goal-group.index');
            $vertaRangeDateCollection = Helper::vertaRangeDateByMonth(6);

            // Get group record for prepare old value
            $fromDate = Helper::vertaInstanceToGregorian($vertaRangeDateCollection->get('from_at'));
            $toDate = Helper::vertaInstanceToGregorian($vertaRangeDateCollection->get('to_at'));
            $olds = SaleGoal::query()
                ->whereDate('start_at', '>=', $fromDate)
                ->whereDate('end_at', '<=', $toDate)
                ->whereNull(['type_id', 'type_type'])
                ->get();

            // Check date in generated dates by verta
            $dateItems = Helper::checkDateInGeneratedDatesByVerta($vertaRangeDateCollection, $olds);

            return view('admin.group_goal.index', compact('title', 'dateItems'));

        } catch (Exception $e) {
            report($e);

            return to_route('admin.group_goal.index')->with('error', $e->getMessage());
        }

    }

    public function save(SaveRequest $request)
    {
        try {

            SaleGoal::query()->updateOrCreate([
                'start_at' => $request->input('start'),
                'end_at' => $request->input('end'),
                'type_id' => null,
                'type_type' => null,
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
