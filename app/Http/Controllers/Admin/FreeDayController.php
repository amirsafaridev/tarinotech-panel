<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FreeDay\StoreRequest;
use App\Http\Requests\Admin\FreeDay\UpdateRequest;
use App\Models\FreeDay;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FreeDayController extends Controller
{
    public function index()
    {
        $title = 'تقویم تعطیلات';
        $routeData = route('admin.free-day.data');
        $selects = ['id', 'title', 'free_at', 'created_at'];

        return view('admin.free_day.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $freeDays = FreeDay::query();

            return DataTables::of($freeDays)
                ->editColumn('free_at', function (FreeDay $freeDay) {
                    return verta($freeDay->free_at)->format(formatJalaliMonth());
                })
                ->editColumn('created_at', function (FreeDay $freeDay) {
                    return $freeDay->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (FreeDay $freeDay) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.free-day.edit', $freeDay->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'تقویم تعطیلات - جدید';
        $routeStore = route('admin.free-day.store');

        return view('admin.free_day.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            FreeDay::create($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(FreeDay $freeDay)
    {
        $title = 'تقویم تعطیلات - ویرایش';
        $routeUpdate = route('admin.free-day.update', $freeDay->id);
        $routeDestroy = route('admin.free-day.destroy', $freeDay->id);

        return view('admin.free_day.edit', compact('title', 'routeUpdate', 'routeDestroy', 'freeDay'));
    }

    public function update(UpdateRequest $request, FreeDay $freeDay)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $freeDay->update($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function destroy(FreeDay $freeDay)
    {
        try {
            $freeDay->delete();

            return redirect(route('admin.free-day.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.free-day.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->get('title');
        $item['free_at'] = Helper::toGregorian($request->input('free_at'));

        return $item;
    }
}
