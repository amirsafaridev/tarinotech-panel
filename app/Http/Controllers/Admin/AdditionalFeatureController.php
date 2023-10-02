<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdditionalFeature\StoreRequest;
use App\Http\Requests\Admin\AdditionalFeature\UpdateRequest;
use App\Models\AdditionalFeature;
use App\Models\ProjectType;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdditionalFeatureController extends Controller
{
    public function index()
    {
        $title = 'امکانات جانبی';
        $routeData = route('admin.additional-features.data');
        $selects = ['id', 'title', 'type.title', 'created_at'];

        return view('admin.additional_feature.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $additionalFeatures = AdditionalFeature::query()
                ->select('additional_features.*')
                ->with('type');

            return DataTables::of($additionalFeatures)
                ->editColumn('created_at', function (AdditionalFeature $additionalFeature) {
                    return $additionalFeature->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (AdditionalFeature $additionalFeature) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.additional-features.edit', $additionalFeature->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'امکانات جانبی - جدید';
        $routeStore = route('admin.additional-features.store');
        $projectTypes = ProjectType::query()->get();

        return view('admin.additional_feature.create', compact('title', 'routeStore', 'projectTypes'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            AdditionalFeature::create($item);
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

    public function edit(AdditionalFeature $additionalFeature)
    {
        $title = 'امکانات جانبی - ویرایش';
        $routeUpdate = route('admin.additional-features.update', $additionalFeature->id);
        $routeDestroy = route('admin.additional-features.destroy', $additionalFeature->id);
        $projectTypes = ProjectType::query()->get();

        return view('admin.additional_feature.edit', compact('title', 'routeUpdate', 'routeDestroy', 'additionalFeature', 'projectTypes'));
    }

    public function update(UpdateRequest $request, AdditionalFeature $additionalFeature)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $additionalFeature->update($item);
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

    public function destroy(AdditionalFeature $additionalFeature)
    {
        try {
            $additionalFeature->delete();

            return redirect(route('admin.additional-features.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.additional-features.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->get('title');
        $item['project_type_id'] = $request->get('project_type_id');

        return $item;
    }
}
