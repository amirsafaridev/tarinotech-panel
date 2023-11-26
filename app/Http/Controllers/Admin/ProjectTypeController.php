<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectType\StoreRequest;
use App\Http\Requests\Admin\ProjectType\UpdateRequest;
use App\Models\ProjectBase;
use App\Models\ProjectType;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها';
        $routeData = route('admin.project.type.data');
        $selects = ['id', 'title', 'base.title', 'created_at'];

        return view('admin.project_type.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $roles = ProjectType::query()
                ->select('project_types.*')
                ->with('base');

            return DataTables::of($roles)
                ->editColumn('created_at', function ($role) {
                    return $role->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function ($role) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.type.edit', $role->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'انواع پزوژه ها - ایجاد';
        $routeStore = route('admin.project.type.store');
        $projectBases = ProjectBase::query()->get();

        return view('admin.project_type.create', compact('title', 'routeStore', 'projectBases'));
    }

    public function store(StoreRequest $request)
    {
        try {

            $item = $this->itemProvider($request);
            ProjectType::create($item);

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

    public function edit(ProjectType $projectType)
    {
        $title = 'انواع پزوژه ها - ویرایش';
        $routeUpdate = route('admin.project.type.update', $projectType->id);
        $routeDestroy = route('admin.project.type.destroy', $projectType->id);
        $projectBases = ProjectBase::query()->get();

        return view('admin.project_type.edit', compact('title', 'routeUpdate', 'routeDestroy', 'projectType', 'projectBases'));
    }

    public function update(UpdateRequest $request, ProjectType $projectType)
    {
        try {

            $item = $this->itemProvider($request);
            $projectType->update($item);

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

    public function destroy(ProjectType $projectType)
    {
        try {
            $projectType->delete();

            return redirect(route('admin.project.type.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.project.type.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['base_id'] = $request->input('base_id');
        $item['note'] = $request->input('note');

        return $item;
    }
}
