<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SaleGoal;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Modules\Admin\app\Http\Requests\Admin\GoalSaveRequest;
use Modules\Admin\app\Models\Admin;

use function report;
use function response;
use function trans;
use function view;

class GoalController extends Controller
{
    use HasJsonCommonResponse;

    const COUNT_NEXT_MONTH = 6;

    const INDEX_TITLE = 'پرسنل - هدف های فروش';

    public function index(Admin $admin)
    {

        try {
            $title = self::INDEX_TITLE;
            $vertaRangeDateCollection = Helper::vertaRangeDateByMonth(self::COUNT_NEXT_MONTH);

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

            return view('admin::admin.goal.index', compact('title', 'admin', 'dateItems'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);

        }

    }

    public function save(GoalSaveRequest $request, Admin $admin)
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
