<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Personnel\app\Models\DailyActivity;
use Illuminate\Http\Request;
use App\Traits\HasJsonCommonResponseTrait;


class DailyActivityController extends Controller
{
    use HasJsonCommonResponseTrait;
    const INDEX_TITLE = "ساعت فعال پرسنل";

    public function index()
    {
        $activities = DailyActivity::where('edit_request', true)
            ->with('user')
            ->orderBy('date', 'desc')
            ->get();
        $title = self::INDEX_TITLE;

        return view('admin::admin.daily-activity.index', compact('activities', 'title'));
    }
    public function approveEdit(DailyActivity $activity)
    {
        if (!$activity->edit_request || !$activity->edit_request_data) {
            return response()->json([
                'status' => 'error',
                'message' => 'درخواست ویرایشی برای این رکورد وجود ندارد'
            ], 400);
        }

        $editData = $activity->edit_request_data;


        $activity->update([
            'edit_request' => false,
            'edit_request_data' => array_merge($editData, [
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ])
        ]);
        return $this->successBack(route('admin.admin.daily-activity.index'), 'درخواست ویرایش با موفقیت تایید شد');
    }

    public function rejectEdit(Request $request, DailyActivity $activity)
    {
        if (!$activity->edit_request || !$activity->edit_request_data) {
            return 
            response()->json([
                'result' => 'error',
                'back' => route('admin.admin.daily-activity.index'),
                'message' => 'درخواست ویرایشی برای این رکورد وجود ندارد',
            ]);
        }

        $request->validate([
            'reject_reason' => 'required'
        ]);

        $activity->update([
            'edit_request' => false,
            'status' => DailyActivity::STATUS_REJECT,
            'edit_request_data' => array_merge($activity->edit_request_data, [
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'reject_reason' => $request->reject_reason
            ])
        ]);

        return response()->json([
            'result' => 'success',
            'back' => route('admin.personnel.daily-activity.index'),
            'message' => trans('panel.success_update'),
        ]);
    }
}
