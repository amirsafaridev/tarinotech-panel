<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Facility\StoreRequest;
use App\Http\Requests\Admin\Facility\UpdateRequest;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Facility;
use Modules\Project\app\Models\ProjectBase;
use Yajra\DataTables\Facades\DataTables;

use function formatJalaliDateTime;
use function redirect;
use function report;
use function response;
use function route;
use function trans;
use function view;

class FacilityController extends Controller
{
    public function index()
    {
        $title = 'امکانات جانبی';
        $routeData = route('admin.facility.data');
        $selects = ['id', 'title', 'base.title', 'created_at'];

        return view('admin.facility.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $facilities = Facility::query()
                ->select('facilities.*')
                ->with('base');

            return DataTables::of($facilities)
                ->editColumn('created_at', function (Facility $facility) {
                    return $facility->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Facility $facility) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.facility.edit', $facility->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'امکانات جانبی - جدید';
        $routeStore = route('admin.facility.store');
        $projectBases = ProjectBase::query()->get();

        return view('admin.facility.create', compact('title', 'routeStore', 'projectBases'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $item = $this->itemProvider($request);
            Facility::create($item);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(Facility $facility)
    {
        $title = 'امکانات جانبی - ویرایش';
        $routeUpdate = route('admin.facility.update', $facility->id);
        $routeDestroy = route('admin.facility.destroy', $facility->id);
        $projectBases = ProjectBase::query()->get();

        return view('admin.facility.edit', compact('title', 'routeUpdate', 'routeDestroy', 'facility', 'projectBases'));
    }

    public function update(UpdateRequest $request, Facility $facility)
    {
        try {

            $item = $this->itemProvider($request);
            $facility->update($item);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {

            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function destroy(Facility $facility)
    {
        try {
            $facility->delete();

            return redirect(route('admin.facility.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.facility.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['project_base_id'] = $request->input('project_base_id');

        return $item;
    }
}
